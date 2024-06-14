<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookingRequest;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\BookingDetail;
use Illuminate\Support\Facades\DB;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Barber;
use App\Models\CustomerPoint;
use App\Models\ShopProduct;
use App\Models\ShopService;
use App\Models\StockHistory;
use App\Models\StockOnHand;
use App\Models\StockOut;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected $layout = 'admin::pages.booking.';
    function __construct()
    {
        $this->middleware('permission:booking-view', ['only' => ['index']]);
        $this->middleware('permission:booking-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:booking-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:booking-delete', ['only' => ['delete', 'restore', 'destroy']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        if (!$req->status) {
            return redirect()->route('admin-booking-list', 1);
        }
        $startDate = Carbon::now();
        $data['firstMonthDay'] = $req->from_date ? $req->from_date : $startDate->firstOfMonth()->toDate()->format('Y-m-d');
        $data['lastMonthDay'] =  $req->to_date ? $req->to_date : $startDate->lastOfMonth()->toDate()->format('Y-m-d');
        $from = $data['firstMonthDay'];
        $to = $data['lastMonthDay'];
        $shop_id = $req->shop_id ? $req->shop_id : '';
        $barber_id = $req->barber_id ? $req->barber_id : '';
        $payment_status = $req->payment_status ? $req->payment_status : '';
        if ($req->status != 'trash') {
            $query = new Booking;
        } else {
            $query = Booking::onlyTrashed();
        }
        $data['data'] = $query->with(['shop'])->with('barber')
            ->when(filled(request('from_date')), function ($q) use ($from, $to) {
                $q->whereBetween(DB::raw('DATE(booking_date)'), [$from, $to]);
            })
            ->when(filled(request('shop_id')), function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            })
            ->when(filled(request('barber_id')), function ($q) use ($barber_id) {
                $q->where('barber_id', $barber_id);
            })
            ->when(filled(request('payment_status')), function ($q) use ($payment_status) {
                $q->where('payment_status', $payment_status);
            })
            ->orderBy('booking_date', 'desc')
            ->paginate(50);
        $data['shops'] = Shop::where('status', 1)->get();
        $data['barbers'] = Barber::where('status', 1)->get();
        foreach ($data['data'] as $item) {
            $item->customer = Customer::where('id', $item->customer_id)->first();
            $item->service =  BookingDetail::with(['service'])->where('booking_id', $item->id)->get();
        }
        return view($this->layout . 'bookings', $data);
    }
    public function product(Request $req)
    {
        $from = $req->from_date ? $req->from_date : '';
        $to = $req->to_date ? $req->to_date : '';
        $shop_id = $req->shop_id ? $req->shop_id : '';
        $barber_id = $req->barber_id ? $req->barber_id : '';

        $data['data'] = Order::with(['shop'])
            ->when(filled(request('from_date')), function ($q) use ($from, $to) {
                $q->whereBetween(DB::raw('DATE(order_date)'), [$from, $to]);
            })
            ->when(filled(request('shop_id')), function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            })
            ->when(filled(request('barber_id')), function ($q) use ($barber_id) {
                $q->where('barber_id', $barber_id);
            })
            ->paginate(50);
        $data['shops'] = Shop::where('status', 1)->get();
        $data['barbers'] = Barber::where('status', 1)->get();
        foreach ($data['data'] as $item) {
            $item->orders =  OrderDetail::with(['product'])->where('order_id', $item->id)->get();
        }
        return view($this->layout . 'index', $data);
    }
    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->route('admin-booking-list', 1);
        }
        $data['data'] = Booking::with(["customer", "shop", "barber"])->find($id);
        $data['data']->bookingDetail = BookingDetail::with(["service", "product"])->where('booking_id', $id)->orderBy('id', 'asc')->get();
        return view($this->layout . 'editBooking', $data);
    }
    public function saveOld(BookingRequest $req)
    {
        $totalPoint = 0;
        $totalCustomerPoint = 0;
        $dataCarts = isset($req->dataCarts) && $req->dataCarts ? json_decode($req->dataCarts) : [];
        // dd($dataCarts);
        DB::beginTransaction();
        try {
            $status = "Update success.";
            $itemBooking = [
                "customer_id" => $req->customer_id,
                "total_price" => $req->subTotal,
                "total_discount" => $req->total_discount,
                "total_commission" => $req->commissionTotal,
            ];
            // dd($req->id);
            $dataBooking = Booking::find($req->id);
            // $dataBookingDetail = BookingDetail::where('booking_id', $dataBooking->id)->get();
            // dd(count($dataBooking->bookingDetail));
            //deleteCustomerPoint
            // $deleteCustomerPoint = CustomerPoint::where('shop_id', $dataBooking->shop_id)->where('customer_id', $dataBooking->customer_id)->first();
            // if ($deleteCustomerPoint) {
            //     $itemPointDelete = [
            //         "total_point" => $deleteCustomerPoint->total_point -  $dataBooking->total_point,
            //         "total_receving_point" => $deleteCustomerPoint->total_receving_point -  $dataBooking->total_point,
            //         "used_point"    => $deleteCustomerPoint->used_point -  $dataBooking->total_point,
            //         "count_of_using_service" => $deleteCustomerPoint->count_of_using_service - count($dataBookingDetail)
            //     ];
            //     $deleteCustomerPoint->update($itemPointDelete);
            // }

            foreach ($dataCarts as $val) {
                if ($val->product_type == "service") {
                    $product = ShopService::where('shop_id', $dataBooking->shop_id)->where('service_id', $val->product_id)->first();
                } else {
                    $product = ShopProduct::where('shop_id', $dataBooking->shop_id)->where('product_id', $val->product_id)->first();
                }
                $totalPoint += isset($product->point) && $product->point ? (int)$product->point : 0;
                $totalCustomerPoint += isset($product->point) && $product->point ? (int)$product->point : 0;
                $itemBookingDetail = [
                    "booking_id" => $req->id,
                    "service_id" => $val->product_type == "service" ? $val->product_id : null,
                    "product_id" => $val->product_type == "product" ? $val->product_id : null,
                    "price" => $val->itemData->price,
                    "qty" => $val->product_qty,
                    "type" => $val->product_type,
                    "point" => isset($product->point) && $product->point ? $product->point : 0,
                    "product_discount" => $val->product_type == "product" ? $val->itemData->discount : 0,
                    "product_discount_type" => $val->product_type == "product" ? $val->itemData->discountType : null,
                    "service_discount" => $val->product_type == "service" ? $val->itemData->discount : 0,
                    "service_discount_type" => $val->product_type == "service" ? $val->itemData->discountType : null,
                    "product_commission" => $val->product_type == "product" ? $val->itemData->commission : 0,
                    "product_commission_type" => $val->product_type == "product" ? ($val->itemData->commissionType ?? 'khr') : null,
                    "service_commission" => $val->product_type == "service" ? $val->itemData->commission : 0,
                    "service_commission_type" => $val->product_type == "service" ? ($val->itemData->commissionType ?? 'khr') : null,
                ];
                $bookingDetail = BookingDetail::find($val->id);

                //stockProgress
                if ($val->product_type == "product") {

                    //addAndUpdateStockOnHand
                    $stockOhHand = StockOnHand::where(function ($q) use ($req, $val) {
                        $q->where('shop_id', $req->shop_id);
                        $q->where('product_id', $val->product_id);
                    })->first();
                    if ($stockOhHand) {
                        $bookingDetailQty = isset($bookingDetail->qty) && $bookingDetail->qty ? $bookingDetail->qty : 0;
                        $stockOhHand->update([
                            "current_stock" => isset($stockOhHand->current_stock) && $stockOhHand->current_stock ? ($stockOhHand->current_stock + $bookingDetailQty) - (int)$val->product_qty : 0
                        ]);
                    }
                    //endAddAndUpdateStockOnHand

                    $stockHistory = StockHistory::where(function ($q) use ($req, $val) {
                        $q->where('booking_id', $req->id);
                        $q->where('shop_id', $req->shop_id);
                        $q->where('product_id', $val->product_id);
                        $q->where('status', 'stock_out');
                    })->first();

                    $stockOut = isset($stockHistory->stock_id) && $stockHistory->stock_id ? StockOut::find($stockHistory->stock_id) : null;

                    // $newUser = \App\UserInfo::updateOrCreate([
                    //     'user_id'   => Auth::user()->id,
                    // ],[
                    //     'about'     => $request->get('about'),
                    //     'sec_email' => $request->get('sec_email'),
                    //     'gender'    => $request->get("gender"),
                    //     'country'   => $request->get('country'),
                    //     'dob'       => $request->get('dob'),
                    //     'address'   => $request->get('address'),
                    //     'mobile'    => $request->get('cell_no')
                    // ]);


                    $itemStockOut = [
                        "product_id" => $val->product_id,
                        "shop_id" => $req->shop_id,
                        "to_id" => $req->customer_id,
                        "qty" => (int)$val->product_qty,
                        "type" => "customer",
                        "status" => Auth::user()->id
                    ];

                    $itemStockHistory = [
                        "booking_id" => $dataBooking->id,
                        "stock_id" => isset($stockOut) && $stockOut ? $stockOut->id : null,
                        "product_id" =>  $val->product_id,
                        "current_stock" => $stockOhHand->current_stock,
                        "stock_in" => 0,
                        "stock_out" => (int)$val->product_qty,
                        "shop_id" => $req->shop_id,
                        "to_id" => $req->customer_id,
                        "qty" => (int)$val->product_qty,
                        "status" => "stock_out",
                        "request_by" => Auth::user()->id
                    ];
                    if ($stockOut) {
                        $stockOut->update($itemStockOut);
                    } else {
                        $createStockOut = StockOut::create($itemStockOut);
                        $itemStockHistory['stock_id'] =  $createStockOut->id;
                    }
                    if ($stockHistory) {
                        $stockHistory->update($itemStockHistory);
                    } else {
                        StockHistory::create($itemStockHistory);
                    }
                }
                //endStockProgress


                if ($val->id) {
                    // BookingDetail::find($val->id)->update($itemBookingDetail);
                    $bookingDetail->update($itemBookingDetail);
                } else {
                    BookingDetail::create($itemBookingDetail);
                }
            }

            $itemBooking["total_point"] = $totalPoint;
            $dataBooking->update($itemBooking);


            //deleteBookingWithReturnProductStock
            $this->deleteBookingWithReturnProductStock($req);
            //endDeleteBookingWithReturnProductStock

            // if (count($req->bookingDelete) > 0) {
            //     foreach ($req->bookingDelete as $bookDetail_id) {
            //         $dataBookingDelete = BookingDetail::find($bookDetail_id);
            //         $qtyBookingDelete = $dataBookingDelete->qty ?? 0;
            //         //addAndUpdateStockOnHand
            //         $stockOhHandUpdate = StockOnHand::where(function ($q) use ($req, $dataBookingDelete) {
            //             $q->where('shop_id', $req->shop_id);
            //             $q->where('product_id', $dataBookingDelete->product_id);
            //         })->first();
            //         if ($stockOhHandUpdate) {
            //             $stockOhHandUpdate->update([
            //                 "current_stock" => $stockOhHandUpdate->current_stock + $qtyBookingDelete
            //             ]);
            //             $stockHistory = StockHistory::where(function ($q) use ($req, $dataBookingDelete) {
            //                 $q->where('booking_id', $req->id);
            //                 $q->where('shop_id', $req->shop_id);
            //                 $q->where('product_id', $dataBookingDelete->product_id);
            //                 $q->where('status', 'stock_out');
            //             })->first();
            //             if($stockHistory){
            //                 $stockOut = isset($stockHistory->stock_id) && $stockHistory->stock_id ? StockOut::find($stockHistory->stock_id) : null;
            //                 $stockOut->delete();
            //                 $stockHistory->delete();
            //             }

            //         }
            //         //endAddAndUpdateStockOnHand
            //         $dataBookingDelete->delete();
            //     }
            // }

            //createAndUpdateCustomerPoint
            $this->createAndUpdateCustomerPoint($dataBooking, $totalCustomerPoint, $dataCarts);
            // $numberBookingDetail = isset($dataBooking) && $dataBooking ? count($dataBooking->bookingDetail) : 0;
            // $CustomerPoint = CustomerPoint::where('shop_id', $dataBooking->shop_id)->where('customer_id', $dataBooking->customer_id)->first();
            // if ($CustomerPoint) {
            //     $itemAddBookingPoint = [
            //         "total_point" => ($CustomerPoint->total_point - $dataBooking->total_point) +  $totalCustomerPoint,
            //         "total_receving_point" => ($CustomerPoint->total_receving_point - $dataBooking->total_point) +  $totalCustomerPoint,
            //         "used_point"    => ($CustomerPoint->used_point - $dataBooking->total_point) +  $totalCustomerPoint,
            //         "count_of_using_service" => ($CustomerPoint->count_of_using_service - $numberBookingDetail) + count($dataCarts)
            //     ];
            //     $CustomerPoint->update($itemAddBookingPoint);
            // }
            //endCreateAndUpdateCustomerPoint

            DB::commit();
            Session::flash('success', $status);
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function save(BookingRequest $req)
    {
        $totalPoint = 0;
        $totalCustomerPoint = 0;
        $dataCarts = isset($req->dataCarts) && $req->dataCarts ? json_decode($req->dataCarts) : [];

        DB::beginTransaction();
        try {
            $status = "Update success.";
            $itemBooking = [
                "customer_id" => $req->customer_id,
                "total_price" => $req->subTotal,
                "total_discount" => $req->total_discount,
                "total_commission" => $req->commissionTotal,
            ];

            $dataBooking = Booking::find($req->id);

            foreach ($dataCarts as $val) {
                if ($val->product_type == "service") {
                    $product = ShopService::where('shop_id', $dataBooking->shop_id)->where('service_id', $val->product_id)->first();
                } else {
                    $product = ShopProduct::where('shop_id', $dataBooking->shop_id)->where('product_id', $val->product_id)->first();
                }
                $totalPoint += isset($product->point) && $product->point ? (int)$product->point : 0;
                $totalCustomerPoint += isset($product->point) && $product->point ? (int)$product->point : 0;
                $itemBookingDetail = [
                    "booking_id" => $req->id,
                    "service_id" => $val->product_type == "service" ? $val->product_id : null,
                    "product_id" => $val->product_type == "product" ? $val->product_id : null,
                    "price" => $val->itemData->price,
                    "qty" => $val->product_qty,
                    "type" => $val->product_type,
                    "point" => isset($product->point) && $product->point ? $product->point : 0,
                    "product_discount" => $val->product_type == "product" ? $val->itemData->discount : 0,
                    "product_discount_type" => $val->product_type == "product" ? $val->itemData->discountType : null,
                    "service_discount" => $val->product_type == "service" ? $val->itemData->discount : 0,
                    "service_discount_type" => $val->product_type == "service" ? $val->itemData->discountType : null,
                    "product_commission" => $val->product_type == "product" ? $val->itemData->commission : 0,
                    "product_commission_type" => $val->product_type == "product" ? ($val->itemData->commissionType ?? 'khr') : null,
                    "service_commission" => $val->product_type == "service" ? $val->itemData->commission : 0,
                    "service_commission_type" => $val->product_type == "service" ? ($val->itemData->commissionType ?? 'khr') : null,
                ];
                $bookingDetail = BookingDetail::find($val->id);

                //stockProgressSaveBooking
                $this->stockProgressSaveBooking($req, $bookingDetail, $val, $dataBooking);

                if ($val->id) {
                    $bookingDetail->update($itemBookingDetail);
                } else {
                    BookingDetail::create($itemBookingDetail);
                }
            }

            $itemBooking["total_point"] = $totalPoint;
            $dataBooking->update($itemBooking);


            //deleteBookingWithReturnProductStock
            $this->deleteBookingWithReturnProductStock($req);

            //createAndUpdateCustomerPoint
            $this->createAndUpdateCustomerPoint($dataBooking, $totalCustomerPoint, $dataCarts);

            DB::commit();
            Session::flash('success', $status);
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'error', 'error' => $e->getMessage()]);
        }
    }

    public function stockProgressSaveBooking($req, $bookingDetail, $val, $dataBooking)
    {
        if ($val->product_type == "product") {
            
            //addAndUpdateStockOnHand
            $stockOhHand = StockOnHand::where(function ($q) use ($req, $val) {
                $q->where('shop_id', $req->shop_id);
                $q->where('product_id', $val->product_id);
            })->first();
            $currentStock = isset($stockOhHand->current_stock) && $stockOhHand->current_stock ? $stockOhHand->current_stock : 0;
            if ($stockOhHand) {
                $bookingDetailQty = isset($bookingDetail->qty) && $bookingDetail->qty ? $bookingDetail->qty : 0;
                $stockOhHand->update([
                    "current_stock" => $currentStock ? ($currentStock + $bookingDetailQty) - (int)$val->product_qty : 0
                ]);
            }

            $stockHistory = StockHistory::where(function ($q) use ($req, $val) {
                $q->where('booking_id', $req->id);
                $q->where('shop_id', $req->shop_id);
                $q->where('product_id', $val->product_id);
                $q->where('status', 'stock_out');
            })->first();

            $stockOut = isset($stockHistory->stock_id) && $stockHistory->stock_id ? StockOut::find($stockHistory->stock_id) : null;

            $itemStockOut = [
                "product_id" => $val->product_id,
                "shop_id" => $req->shop_id,
                "to_id" => $req->customer_id,
                "qty" => (int)$val->product_qty,
                "type" => "customer",
                "status" =>1,
                "request_by" => Auth::user()->id,
            ];

            $itemStockHistory = [
                "booking_id" => $dataBooking->id,
                "stock_id" => isset($stockOut) && $stockOut ? $stockOut->id : null,
                "product_id" =>  $val->product_id,
                "current_stock" => $currentStock >= (int)$val->product_qty ? ($currentStock + $bookingDetailQty) - (int)$val->product_qty : 0,
                "stock_in" => 0,
                "stock_out" => (int)$val->product_qty,
                "shop_id" => $req->shop_id,
                "to_id" => $req->customer_id,
                "qty" => (int)$val->product_qty,
                "status" => "stock_out",
                "request_by" => Auth::user()->id
            ];
            if ($stockOut) {
                $stockOut->update($itemStockOut);
            } else {
                $createStockOut = StockOut::create($itemStockOut);
                $itemStockHistory['stock_id'] =  $createStockOut->id;
            }
            if ($stockHistory) {
                $stockHistory->update($itemStockHistory);
            } else {
                StockHistory::create($itemStockHistory);
            }
        }
    }

    public function createAndUpdateCustomerPoint($dataBooking, $totalCustomerPoint, $dataCarts)
    {
        $numberBookingDetail = isset($dataBooking) && $dataBooking ? count($dataBooking->bookingDetail) : 0;
        $CustomerPoint = CustomerPoint::where('shop_id', $dataBooking->shop_id)->where('customer_id', $dataBooking->customer_id)->first();
        if ($CustomerPoint) {
            $itemAddBookingPoint = [
                "total_point" => ($CustomerPoint->total_point - $dataBooking->total_point) +  $totalCustomerPoint,
                "total_receving_point" => ($CustomerPoint->total_receving_point - $dataBooking->total_point) +  $totalCustomerPoint,
                "used_point"    => ($CustomerPoint->used_point - $dataBooking->total_point) +  $totalCustomerPoint,
                "count_of_using_service" => ($CustomerPoint->count_of_using_service - $numberBookingDetail) + count($dataCarts)
            ];
            $CustomerPoint->update($itemAddBookingPoint);
        }
    }

    public function deleteBookingWithReturnProductStock($req)
    {
        //deleteBooking
        if (count($req->bookingDelete) > 0) {
            foreach ($req->bookingDelete as $bookDetail_id) {
                $dataBookingDelete = BookingDetail::find($bookDetail_id);
                $qtyBookingDelete = $dataBookingDelete->qty ?? 0;
                //addAndUpdateStockOnHand
                $stockOhHandUpdate = StockOnHand::where(function ($q) use ($req, $dataBookingDelete) {
                    $q->where('shop_id', $req->shop_id);
                    $q->where('product_id', $dataBookingDelete->product_id);
                })->first();
                if ($stockOhHandUpdate) {
                    $stockOhHandUpdate->update([
                        "current_stock" => $stockOhHandUpdate->current_stock + $qtyBookingDelete
                    ]);
                    $stockHistory = StockHistory::where(function ($q) use ($req, $dataBookingDelete) {
                        $q->where('booking_id', $req->id);
                        $q->where('shop_id', $req->shop_id);
                        $q->where('product_id', $dataBookingDelete->product_id);
                        $q->where('status', 'stock_out');
                    })->first();
                    if ($stockHistory) {
                        $stockOut = isset($stockHistory->stock_id) && $stockHistory->stock_id ? StockOut::find($stockHistory->stock_id) : null;
                        $stockOut->delete();
                        $stockHistory->delete();
                    }
                }
                //endAddAndUpdateStockOnHand
                $dataBookingDelete->delete();
            }
        }
    }

    public function restore($id)
    {
        $data = Booking::withTrashed()->where('id', $id)->first();
        DB::beginTransaction();
        try {
            $bookingTotalPoint = $data->total_point ? (int)$data->total_point : 0;
            $customerPoint = CustomerPoint::where('customer_id', $data->customer_id)->first();
            if ($customerPoint) {
                $total_point = $customerPoint->total_point ? (int)$customerPoint->total_point : 0;
                $total_receving_point = $customerPoint->total_receving_point ? (int)$customerPoint->total_receving_point : 0;
                $customerPoint->update([
                    "total_point" => $total_point ? $total_point + $bookingTotalPoint  : 0,
                    "total_receving_point" => $total_receving_point  ? $total_receving_point + $bookingTotalPoint  : 0,
                ]);
            }
            $data->restore();
            DB::commit();
            Session::flash('success', 'Restore move to trash!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Restore to trash unsuccess!');
            return redirect()->back();
        }
    }
    public function destroy(Request $request)
    {
        $item = Booking::withTrashed()->where('id', $request->id)->first();
        DB::beginTransaction();
        try {
            $bookingDetail = BookingDetail::where('booking_id', $item->id)->get();
            foreach ($bookingDetail as $val) {
                $val->delete();
            }
            $item->forceDelete();
            DB::commit();
            Session::flash('success', 'Delete success!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Delete unsuccess!');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $item = Booking::where('id', $id)->first();
        DB::beginTransaction();
        try {
            $bookingTotalPoint = $item->total_point ? (int)$item->total_point : 0;
            $customerPoint = CustomerPoint::where('customer_id', $item->customer_id)->first();
            if ($customerPoint) {
                $total_point = $customerPoint->total_point ? (int)$customerPoint->total_point : 0;
                $total_receving_point = $customerPoint->total_receving_point ? (int)$customerPoint->total_receving_point : 0;
                $customerPoint->update([
                    "total_point" => $total_point >= $bookingTotalPoint ? $total_point - $bookingTotalPoint  : 0,
                    "total_receving_point" => $total_receving_point >= $bookingTotalPoint ? $total_receving_point - $bookingTotalPoint  : 0,
                ]);
            }
            $item->delete();
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    public function report(Request $req)
    {
        $itemSelect = ["id", "name", "phone"];
        $data = BookingDetail::with([
            "product" => function ($product) {
                $product->select("id", "name");
            },
            "service" => function ($service) {
                $service->select("id", "name");
            },
            "booking" => function ($booking) use ($itemSelect) {
                $booking->with([
                    "shop" => function ($query) use ($itemSelect) {
                        $query->select($itemSelect);
                    },
                    "barber" => function ($query) use ($itemSelect) {
                        $query->select($itemSelect);
                    },
                    "customer" => function ($query) use ($itemSelect) {
                        $query->select($itemSelect);
                    },
                ]);
            }
        ])->where(function ($query) use ($req) {
            if ($req->from_date && !$req->to_date) {
                $query->whereDate('created_at', '>=', $req->from_date);
            } elseif ($req->from_date && $req->to_date) {
                $query->whereDate('created_at', '>=', $req->from_date);
                $query->whereDate('created_at', '<=', $req->to_date);
            }
            $query->whereHas("booking", function ($queryBooking) use ($req) {
                if ($req->shop_id) {
                    $queryBooking->where('shop_id', $req->shop_id);
                }
                if ($req->barber_id) {
                    $queryBooking->where('barber_id', $req->barber_id);
                }
                if ($req->status) {
                    $queryBooking->where('payment_status', $req->status);
                }
            });
        })->orderBy('id', 'desc')->get();
        // dd($data);
        return response()->json($data);
    }
}
