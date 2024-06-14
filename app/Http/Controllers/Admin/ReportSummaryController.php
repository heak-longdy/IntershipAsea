<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\BookingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Shop;
use Carbon\Carbon;

class ReportSummaryController extends Controller
{
    protected $layout = 'admin::pages.reportSummary.';
    function __construct()
    {
        $this->middleware('permission:report-summary-view', ['only' => ['index']]);
    }
    public function index(Request $req)
    {
        $data['setting'] = Setting::first();
        $data['status'] = $req->status;
        $data['from_date'] = $req->from_date ? $req->from_date : Carbon::now()->format('Y-m-d');
        $data['to_date'] =  $req->to_date ? $req->to_date : Carbon::now()->format('Y-m-d');
        $data['productSelectType'] =  $req->product_select_type;
        $data['dataProduct'] = $req->product_select_type == "service" &&  $req->product_or_service ? Service::find($req->product_or_service) : Product::find($req->product_or_service);
        if (!$req->status) {
            return redirect()->route('admin-report-summary-list', 'shop');
        }
        $data['data'] = [];
        $queryData = $this->query($req, $data['from_date'], $data['to_date']);
        if ($req->status == "barber") {
            $data['data'] = $this->formatListingBarberData($queryData);
        } else {
            $data['data'] = $this->formatListingShopData($queryData);
        }
        return view($this->layout . 'index', $data);
    }

    public function query($req, $from_date, $to_date)
    {
        $select = ['id', 'customer_id', 'shop_id', 'barber_id', 'booking_date', 'pay_way', 'payment_status', 'invoice_number', 'payment_date'];
        $query = Booking::select($select)->with([
            "shop" => function ($qShop) {
                $qShop->select('id', 'name', 'nick_name', 'phone');
            },
            "barber" => function ($qBarber) {
                $qBarber->select('id', 'name', 'phone');
            },
            "bookingDetail" => function ($qBookingDetail) use ($req) {
                if ($req->product_select_type == "product" || $req->product_select_type == "service") {
                    $qBookingDetail->where('type', $req->product_select_type);  
                    if ($req->product_select_type == "product" && $req->product_or_service) {
                        $qBookingDetail->where('product_id', $req->product_or_service);
                    } else if ($req->product_select_type == "service" && $req->product_or_service) {
                        $qBookingDetail->where('service_id', $req->product_or_service);
                    }
                }
            }
        ])->where(function ($query) use ($from_date, $to_date) {
            if ($from_date && $to_date) {
                $query->whereBetween(DB::raw('DATE(booking_date)'), [$from_date, $to_date]);
            }
            if ($from_date && !$to_date) {
                $query->whereDate('booking_date', $from_date);
            }
        })->orderBy('booking_date', 'desc');
        $dataGet = $query->get();

        return $dataGet;
    }

    private function formatListingShopData($dataGet)
    {
        $data = [];
        //calculatorDataListing
        if (isset($dataGet) && count($dataGet) > 0) {
            foreach ($dataGet as $item) {
                $item->nID = 0;
                $item->totalPrice = 0;
                $item->totalDiscount = 0;
                $item->totalAfterDiscount = 0;
                $item->totalCommission = 0;
                $item->totalInCome = 0;
                if (isset($item->bookingDetail) && count($item->bookingDetail) > 0) {
                    foreach ($item->bookingDetail as $itemDetail) {
                        $qty = $itemDetail?->qty ?? 1;
                        $price = $itemDetail?->price ?? 0;
                        $item->nID += 1;
                        $item->totalPrice += $price * $qty;
                        $item->totalDiscount += $this->discount($itemDetail);
                        $item->totalCommission += $this->commission($itemDetail);
                    }
                }
                $item->totalAfterDiscount = isset($item->totalPrice) && $item->totalPrice ? $item->totalPrice - $item->totalDiscount : 0;
                $item->totalInCome = $item->totalPrice - ($item->totalDiscount + $item->totalCommission);
                $data[$item->shop_id][] = $item;
            }
        }
        $dataFormat['listing'] = [];
        $dataFormat['total'] = (object)[
            "nID" => 0,
            "totalPrice" => 0,
            "totalDiscount" => 0,
            "totalAfterDiscount" => 0,
            "totalCommission" => 0,
            "totalInCome" => 0
        ];
        foreach ($data as $key => $valItem) {
            $shopData = Shop::select('id', 'name')->find($key);
            $item = (object) [
                "shop" => $shopData ?? (object)['name' => null],
                "nID" => 0,
                "totalPrice" => 0,
                "totalDiscount" => 0,
                "totalAfterDiscount" => 0,
                "totalCommission" => 0,
                "totalInCome" => 0,
            ];
            foreach ($data[$key] as $val) {

                $item->nID += $val->nID;
                $item->totalPrice += $val->totalPrice;
                $item->totalDiscount += $val->totalDiscount;
                $item->totalAfterDiscount += $val->totalAfterDiscount;
                $item->totalCommission += $val->totalCommission;
                $item->totalInCome += $val->totalInCome;
            }

            $dataFormat['listing'][] = $item;
            $dataFormat['total']->nID += $item->nID;
            $dataFormat['total']->totalPrice += $item->totalPrice;
            $dataFormat['total']->totalDiscount += $item->totalDiscount;
            $dataFormat['total']->totalAfterDiscount += $item->totalAfterDiscount;
            $dataFormat['total']->totalCommission += $item->totalCommission;
            $dataFormat['total']->totalInCome += $item->totalInCome;
        }
        //shopSort
        usort($dataFormat['listing'], function ($a, $b) {
            return strcasecmp($a->shop?->name, $b->shop?->name);
        });

        return (object) $dataFormat;
    }

    private function formatListingBarberData($dataGet)
    {
        $data = [];
        //calculatorDataListing
        if (isset($dataGet) && count($dataGet) > 0) {
            foreach ($dataGet as $item) {
                $item->nID = 0;
                $item->totalPrice = 0;
                $item->totalDiscount = 0;
                $item->totalAfterDiscount = 0;
                $item->totalCommission = 0;
                $item->totalInCome = 0;
                if (isset($item->bookingDetail) && count($item->bookingDetail) > 0) {
                    foreach ($item->bookingDetail as $itemDetail) {
                        $qty = $itemDetail?->qty ?? 1;
                        $price = $itemDetail?->price ?? 0;
                        $item->totalPrice += $price * $qty;
                        $item->nID += 1;
                        $item->totalDiscount += $this->discount($itemDetail);
                        $item->totalCommission += $this->commission($itemDetail);
                    }
                }
                $item->totalAfterDiscount = isset($item->totalPrice) && $item->totalPrice ? $item->totalPrice - $item->totalDiscount : 0;
                $item->totalInCome = $item->totalPrice - ($item->totalDiscount + $item->totalCommission);
                $data[$item->barber_id][] = $item;
            }
        }

        $dataFormat['listing'] = [];
        $dataFormat['total'] = (object)[
            "nID" => 0,
            "totalPrice" => 0,
            "totalDiscount" => 0,
            "totalAfterDiscount" => 0,
            "totalCommission" => 0,
            "totalInCome" => 0
        ];
        foreach ($data as $key => $valItem) {

            $barberData = Barber::select('id', 'shop_id', 'name')->find($key);
            $item = (object) [
                "barber" => $barberData ?? (object)['name' => null],
                "shop" => isset($barberData?->shop) && $barberData?->shop ? $barberData->shop()?->select('id', 'name')->first() : (object)['name' => null],
                "nID" =>  0,
                "totalPrice" => 0,
                "totalDiscount" => 0,
                "totalAfterDiscount" => 0,
                "totalCommission" => 0,
                "totalInCome" => 0,
            ];
            foreach ($data[$key] as $val) {
                $item->nID += $val->nID;
                $item->totalPrice += $val->totalPrice;
                $item->totalDiscount += $val->totalDiscount;
                $item->totalAfterDiscount += $val->totalAfterDiscount;
                $item->totalCommission += $val->totalCommission;
                $item->totalInCome += $val->totalInCome;
            }

            $dataFormat['listing'][] = $item;
            $dataFormat['total']->nID += $item->nID;
            $dataFormat['total']->totalPrice += $item->totalPrice;
            $dataFormat['total']->totalDiscount += $item->totalDiscount;
            $dataFormat['total']->totalAfterDiscount += $item->totalAfterDiscount;
            $dataFormat['total']->totalCommission += $item->totalCommission;
            $dataFormat['total']->totalInCome += $item->totalInCome;
        }
        //shopSort
        usort($dataFormat['listing'], function ($a, $b) {
            return strcasecmp($a->barber?->name, $b->barber?->name);
        });

        return (object) $dataFormat;
    }

    private function commission($item)
    {
        $price = 0;
        if ($item->type == "service" && $item->service_commission) {
            if ($item?->service_commission_type == "percent") {
                $price = ($item?->price * $item?->service_commission / 100);
            } else if ($item?->service_commission_type == "khr") {
                $price = $item?->service_commission;
            }
        }
        if ($item->type == "product" && $item?->product_commission) {
            if ($item?->product_commission_type == "percent") {
                $price = ($item?->price * $item?->product_commission / 100);
            } else if ($item?->product_commission_type == "khr") {
                $price = $item?->product_commission;
            }
        }
        return $price;
    }
    private function discount($item)
    {
        $price = 0;
        if ($item->type == "service") {
            if ($item?->service_discount_type == "percent" && $item?->service_discount) {
                $price = ($item?->price * $item?->service_discount / 100);
            } else if ($item?->service_discount_type == "khr" && $item?->service_discount) {
                $price = $item?->service_discount;
            }
        } else if ($item->type == "product") {
            if ($item?->product_discount_type == "percent" && $item?->product_discount) {
                $price = ($item?->price * $item?->product_discount / 100);
            } else if ($item?->product_discount_type == "khr" && $item?->product_discount) {
                $price = $item?->product_discount;
            }
        }
        return $price;
    }
}
