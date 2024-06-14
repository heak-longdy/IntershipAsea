<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Shop;
use App\Models\WalletHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:dashboard-view', ['only' => ['index']]);
    }

    public function index(Request $req)
    {
        $data['shop'] = Shop::find(request('shop_id'));
        $startDate = Carbon::now();
        $data['firstMonthDay'] = $req->from_date ? $req->from_date : $startDate->firstOfMonth()->toDate()->format('Y-m-d');
        $data['lastMonthDay'] =  $req->to_date ? $req->to_date : $startDate->lastOfMonth()->toDate()->format('Y-m-d');
        $from = $data['firstMonthDay'];
        $to = $data['lastMonthDay'];

        $data['total_top_up'] = $this->filterSumTotal(WalletHistory::class, $from, $to, 'status', '', 'amount', request('shop_id'));
        $data['customer'] = $this->filterCount(Customer::class, $from, $to, 'trash', 'status', '', null);
        $data['shopData'] = $this->filterCountID(Shop::class, $from, $to, 'trash', 'status', '', request('shop_id'));
        $data['brand'] = $this->filterCount(Brand::class, $from, $to, 'trash', 'status', '', null);
        $data['barber'] = $this->filterCount(Barber::class, $from, $to, 'trash', 'status', '', request('shop_id'));
        $data['booking'] = $this->filterBooking(Booking::class, $from, $to, 'nonTrash', 'status', '', request('shop_id'));
        // return ($data['booking']);
        return view("admin::pages.dashboard", $data);
    }

    private function filterCount($model, $first, $last, $type, $typeStatus, $status, $shop_id)
    {
        return $model::where(function ($q) use ($first, $last, $typeStatus, $status, $shop_id) {
            if ($first && $last) {
                $q->whereDate('created_at', '>=', $first);
                $q->whereDate('created_at', '<=', $last);
            }
            if ($status) {
                $q->where($typeStatus, $status);
            }
            if ($shop_id) {
                $q->where('shop_id', $shop_id);
            }
        })->count();
    }

    private function filterCountID($model, $first, $last, $type, $typeStatus, $status, $shop_id)
    {
        return $model::where(function ($q) use ($first, $last, $typeStatus, $status, $shop_id) {
            if ($first && $last) {
                $q->whereDate('created_at', '>=', $first);
                $q->whereDate('created_at', '<=', $last);
            }
            if ($status) {
                $q->where($typeStatus, $status);
            }
            if ($shop_id) {
                $q->where('id', $shop_id);
            }
        })->count();
    }

    private function filterSumTotal($model, $first, $last, $typeStatus, $status, $totalType, $shop_id)
    {
        return $model::where(function ($q) use ($first, $last, $typeStatus, $status, $shop_id) {
            if ($first && $last) {
                $q->whereDate('created_at', '>=', $first);
                $q->whereDate('created_at', '<=', $last);
            }
            if ($status) {
                $q->where($typeStatus, $status);
            }
            if ($shop_id) {
                $q->where('shop_id', $shop_id);
            }
        })->sum($totalType);
    }

    private function filterBooking($model, $first, $last, $type, $typeStatus, $status, $shop_id)
    {

        $titleDay = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        $days = [];
        $times = [];
        foreach ($titleDay as $index => $day) {
            $item = [
                "name" => $day,
                "total" => 0
            ];
            $days[$day] = $item;
        }

        //generateTime
        $times = $this->cartBookingTime($first, $shop_id);

        $invoiceData = [];
        $totalPrice = [];
        $totalCommission = [];
        $totalDiscount = [];
        $totalIncome = [];
        $totalBookingAll = 0;
        $totalProductBooking = 0;
        $totalServiceBooking = 0;
        $totalPayLiabilities = 0;
        $totalCommissionExpenses = 0;
        $totalCompanyIncome = 0;
        $totalPendingPayment = 0;
        $totalPromotionExpenses = 0;
        $dataBooking =  $model::with(["bookingDetail"])->where(function ($q) use ($first, $last, $typeStatus, $status, $shop_id) {
            if ($first && $last) {
                $q->whereDate('booking_date', '>=', $first);
                $q->whereDate('booking_date', '<=', $last);
            }
            if ($status) {
                $q->where($typeStatus, $status);
            }
            if ($shop_id) {
                $q->where('shop_id', $shop_id);
            }
        })->get();
        if (count($dataBooking) > 0) {
            foreach ($dataBooking as $item) {

                $companyIncomePrice = (float)$item->total_price - ((float)$item->total_discount + (float)$item->total_commission);
                $item->total_income = $companyIncomePrice;

                $item->date = Carbon::parse($item->booking_date)->dayName;
                $item->timeNumber = (int)Carbon::parse($item->booking_date)->format('Hi');
                array_push($invoiceData, $item->invoice_number);
                array_push($totalPrice, $item->total_price);
                array_push($totalCommission, $item->total_commission);
                array_push($totalDiscount, $item->total_discount ?? 0);
                array_push($totalIncome, $companyIncomePrice);
                $totalBookingAll += $item->total_price;
                if ($item->payment_status == "Paid") {
                    $totalPayLiabilities += $item->total_price;
                } else if ($item->payment_status == "Pending") {
                    $totalPendingPayment += $item->total_price;
                }
                $totalCommissionExpenses += $item->total_commission;
                $totalCompanyIncome += $companyIncomePrice;
                $totalPromotionExpenses += $item->total_discount;

                if (count($item->bookingDetail) > 0) {
                    foreach ($item->bookingDetail as $itemDetail) {
                        $qty = isset($itemDetail->qty) && $itemDetail->qty ? $itemDetail->qty : 1;
                        if ($itemDetail->type == "product") {
                            $totalProductBooking += $itemDetail->price * $qty;
                        } else {
                            $totalServiceBooking += $itemDetail->price * $qty;
                        }
                        if ($itemDetail->type == "service") {
                            $price =  $itemDetail->price;
                            // if ($itemDetail->service_discount_type == "percent" && $itemDetail->service_discount) {
                            //     $price = $itemDetail->price - ($itemDetail->price * $itemDetail?->service_discount / 100);
                            // } else if ($itemDetail?->service_discount_type == "khr" && $itemDetail?->service_discount) {
                            //     $price = $itemDetail->price - $itemDetail?->service_discount;
                            // }
                        }
                        //chartBookingTime
                        foreach ($times as $index => $time) {
                            if ($item->timeNumber >= (int)$time->form_time && $item->timeNumber <= (int)$time->to_date && $itemDetail->type == "service") {
                                //if ($item->timeNumber >= (int)$time->form_time && $item->timeNumber <= (int)$time->to_date) {
                                $time->serviceCount += 1;
                                $time->total += $price * $qty;
                                $time->totalLastWeek += $price * $qty;
                            }
                        }
                    }
                }

                //barChart
                if ($days[$item->date]) {
                    $days[$item->date]['total'] += $item->total_price;
                }
            }
        }
        $data['totalBookingAll'] = $totalBookingAll;
        $data['totalProductBooking'] = $totalProductBooking;
        $data['totalServiceBooking'] = $totalServiceBooking;
        $data['totalPayLiabilities'] = $totalPayLiabilities;
        $data['dataBooking'] = $dataBooking;
        $data['invoiceData'] = $invoiceData;
        $data['totalPrice'] = $totalPrice;
        $data['totalCommission'] = $totalCommission;
        $data['totalDiscount'] = $totalDiscount;
        $data['totalIncome'] = $totalIncome;
        $data['totalCommissionExpenses'] = $totalCommissionExpenses;
        $data['totalCompanyIncome'] = $totalCompanyIncome;
        $data['totalPendingPayment'] = $totalPendingPayment;
        $data['totalPromotionExpenses'] = $totalPromotionExpenses;
        $data['days'] = $days;
        $data['times'] = $times;
        return (object) $data;
    }

    public function cartBookingTime($firstDate, $shop_id)
    {
        $formDate = $this->getDate($firstDate);
        $dateLast2Week = $this->getDate($firstDate)->subWeek()->format('Y-m-d');
        $dateLast3Week = $this->getDate($dateLast2Week)->subWeek()->format('Y-m-d');
        $currentDate = Carbon::now();
        $times = [];
        for ($i = 0; $i <= 23; $i++) {
            $nameDate = (($i >= 0 && $i <= 9 ? '0' . $i : $i) . ':00') . '-' . ((($i + 1) >= 0 && ($i + 1) <= 9 ? '0' . ($i + 1) : (($i + 1 == 24 ? '00' : $i + 1))) . ':00');
            $itemTime = [
                'form_time' => $i . '00',
                'to_date'   => ($i + 1) . '00',
                'serviceCount' => 0,
                'name'  => $nameDate,
                'total' => 0,
                'totalLastWeek' => 0,
                'totalLastWeek2' => 0,
                'totalLastWeek3' => 0,
            ];
            $times[$i] = (object)$itemTime;
        }
        $dataLast2Week = $this->bookingTimeFilter($dateLast2Week, $formDate->format('Y-m-d'), $shop_id);

        if (count($dataLast2Week) > 0) {
            foreach ($dataLast2Week as $l2item) {
                $timeNumber2 = (int)Carbon::parse($l2item->booking_date)->format('Hi');
                if (count($l2item->bookingDetail) > 0) {
                    foreach ($l2item->bookingDetail as $itemDetail) {
                        $qty = $itemDetail->qty ? $itemDetail->qty : 1;
                        if ($itemDetail->type == "service") {
                            $price =  $itemDetail->price;
                            // if ($itemDetail->service_discount_type == "percent" && $itemDetail->service_discount) {
                            //     $price = $itemDetail->price - ($itemDetail->price * $itemDetail?->service_discount / 100);
                            // } else if ($itemDetail?->service_discount_type == "khr" && $itemDetail?->service_discount) {
                            //     $price = $itemDetail->price - $itemDetail?->service_discount;
                            // }
                        }
                        //chartBookingTime
                        foreach ($times as $index => $time) {
                            if ($timeNumber2 >= (int)$time->form_time && $timeNumber2 <= (int)$time->to_date && $itemDetail->type == "service") {
                                //if ($timeNumber2 >= (int)$time->form_time && $timeNumber2 <= (int)$time->to_date) {
                                $time->serviceCount += 1;
                                $time->totalLastWeek2 += ($price * $qty);
                            }
                        }
                    }
                }
            }
        }

        //dataLast3Week

        $dataLast3Week = $this->bookingTimeFilter($dateLast3Week, $dateLast2Week, $shop_id);

        if (count($dataLast3Week) > 0) {
            foreach ($dataLast3Week as $l3item) {
                $timeNumber3 = (int)Carbon::parse($l3item->booking_date)->format('Hi');
                if (count($l3item->bookingDetail) > 0) {
                    foreach ($l3item->bookingDetail as $itemDetail) {
                        $qty = $itemDetail->qty ? $itemDetail->qty : 1;
                        if ($itemDetail->type == "service") {
                            $price =  $itemDetail->price;
                            // if ($itemDetail->service_discount_type == "percent" && $itemDetail->service_discount) {
                            //     $price = $itemDetail->price - ($itemDetail->price * $itemDetail?->service_discount / 100);
                            // } else if ($itemDetail?->service_discount_type == "khr" && $itemDetail?->service_discount) {
                            //     $price = $itemDetail->price - $itemDetail?->service_discount;
                            // }
                        }
                        //chartBookingTime
                        foreach ($times as $index => $time) {
                            if ($timeNumber3 >= (int)$time->form_time && $timeNumber3 <= (int)$time->to_date && $itemDetail->type == "service") {
                                // if ($timeNumber3 >= (int)$time->form_time && $timeNumber3 <= (int)$time->to_date) {
                                $time->serviceCount += 1;
                                $time->totalLastWeek3 += ($price * $qty);
                            }
                        }
                    }
                }
            }
        }
        return $times;
    }

    public function bookingTimeFilter($first, $last, $shop_id)
    {
        return Booking::with(["bookingDetail"])->where(function ($q) use ($first, $last, $shop_id) {
            if ($first && $last) {
                $q->whereDate('booking_date', '>=', $first);
                $q->whereDate('booking_date', '<=', $last);
            }
            if ($shop_id) {
                $q->where('shop_id', $shop_id);
            }
        })->get();
    }
    private function getDate($date)
    {
        $dataTime =  $date ? Carbon::parse($date) : Carbon::now();
        return $dataTime;
    }
}
