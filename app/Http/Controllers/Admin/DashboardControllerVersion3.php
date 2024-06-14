<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Setting;
use App\Models\Shop;
use App\Models\WalletHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:dashboard-view', ['only' => ['index']]);
    }
    public function index(Request $req)
    {
        $data['setting'] = Setting::first();
        $data['shop'] = Shop::find(request('shop_id'));
        $data['firstMonthDay'] = $req->from_date ? $req->from_date : Carbon::now()->firstOfMonth()->toDate()->format('Y-m-d');
        $data['lastMonthDay'] =  $req->to_date ? $req->to_date : Carbon::now()->lastOfMonth()->toDate()->format('Y-m-d');

        //dashboard1 filter(shop, form_date and to_date)
        $data['total_top_up'] = $this->filterSumTotal(WalletHistory::class, $data['firstMonthDay'], $data['lastMonthDay'], 'status', '', 'amount', request('shop_id'));
        $data['booking'] = $this->filterBooking(Booking::class, $data['firstMonthDay'], $data['lastMonthDay'], 'nonTrash', 'status', '', request('shop_id'));
        $data['totalPayLiabilities'] = $this->PayLiabilities($data['firstMonthDay'], $data['lastMonthDay'], request('shop_id'));

        //dashboard2 filter(none)
        $data['pendingPayment'] = $this->PendingPayment();
        $data['highest_performance_rate'] = $this->HighestPerformanceRate();
        $data['employee_balance'] = $this->EmployeeBalance();
        $data['brand'] = $this->filterCount(Brand::class, null, null, 'trash', 'status', 1, null);
        $data['barber'] = $this->filterCount(Barber::class, null, null, 'trash', 'status', 1, null);
        $data['shopData'] = $this->filterCountID(Shop::class, null, null, 'trash', 'status', 1, null);

        //chartData
        $data['chartFromDate'] = $req->chart_from_date ? $req->chart_from_date : Carbon::now()->format('Y-m-d');
        $data['chartShop'] = Shop::find(request('chart_shop_id'));

        //CustomerDayByDay
        $data['chartCustomerDayByDay'] = $this->chartCustomerDayByDay($data['chartFromDate'], $data['chartShop']);
        //Shop
        $data['chartShopBooking'] = $this->chartShopBooking($data['chartFromDate'], $data['chartShop']);
        //bookingTime
        $data['chartBookingByTime'] = $this->chartBookingByTime($data['chartFromDate'], $data['chartShop']);

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

        // $titleDay = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        // $days = [];
        // foreach ($titleDay as $index => $day) {
        //     $item = [
        //         "name" => $day,
        //         "total" => 0
        //     ];
        //     $days[$day] = $item;
        // }

        //generateTime
        //$times = [];
        //$times = $this->chartBookingTime($first, $shop_id);

        $invoiceData = [];
        $totalPrice = [];
        $totalCommission = [];
        $totalDiscount = [];
        $totalIncome = [];
        $totalBookingAll = 0;
        $totalCountBookingAll = 0;
        $totalProductBooking = 0;
        $totalCountProductBooking = 0;
        $totalServiceBooking = 0;
        $totalCountServiceBooking = 0;
        $totalPayLiabilities = 0;
        $totalCommissionExpenses = 0;
        $totalCommissionExpensesDiscount = 0;

        $totalCustomerCount = 0;
        $totalCustomerEmptyNumberCount = 0;
        $totalCustomerPhoneNumber = 0;

        $totalCompanyIncome = 0;
        $totalCompanyIncomeDiscount = 0;

        $totalPendingPayment = 0;
        $totalPromotionExpenses = 0;
        $totalPromotionExpensesDiscount = 0;

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

                $totalCommissionExpenses += $item->total_commission;
                $totalCompanyIncome += $companyIncomePrice;
                $totalPromotionExpenses += $item->total_discount;
                $totalCountBookingAll += count($item->bookingDetail);
                $totalCustomerCount += 1;
                $findCustomerData = $this->findCustomer($item->customer_id);
                $totalCustomerEmptyNumberCount += $findCustomerData?->phone == "999" ? 1 : 0;
                $totalCustomerPhoneNumber += $findCustomerData?->phone != "999" ? 1 : 0;

                if (count($item->bookingDetail) > 0) {
                    foreach ($item->bookingDetail as $itemDetail) {
                        $qty = isset($itemDetail->qty) && $itemDetail->qty ? $itemDetail->qty : 1;
                        if ($itemDetail->type == "product") {
                            $totalProductBooking += $itemDetail->price * $qty;
                            $totalCountProductBooking += 1;
                        } else {
                            $totalServiceBooking += $itemDetail->price * $qty;
                        }
                        if ($itemDetail->type == "service") {
                            $price =  $itemDetail->price;
                            $totalCountServiceBooking += 1;
                        }
                        //chartBookingTime
                        // foreach ($times as $index => $time) {
                        //     if ($item->timeNumber >= (int)$time->form_time && $item->timeNumber <= (int)$time->to_date && $itemDetail->type == "service") {
                        //         //if ($item->timeNumber >= (int)$time->form_time && $item->timeNumber <= (int)$time->to_date) {
                        //         $time->serviceCount += 1;
                        //         $time->total += $price * $qty;
                        //         $time->totalLastWeek += $price * $qty;
                        //     }
                        // }
                    }
                }

                //barChart
                // if ($days[$item->date]) {
                //     $days[$item->date]['total'] += $item->total_price;
                // }
            }
        }
        $totalCommissionExpensesDiscount = isset($totalBookingAll) && $totalBookingAll ? (float)(($totalCommissionExpenses / $totalBookingAll) * 100) : 0;
        $totalCompanyIncomeDiscount = isset($totalBookingAll) && $totalBookingAll ? (float)(($totalCompanyIncome / $totalBookingAll) * 100) : 0;
        $totalPromotionExpensesDiscount = isset($totalBookingAll) && $totalBookingAll ? (float)(($totalPromotionExpenses / $totalBookingAll) * 100) : 0;

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
        //count
        $data['totalCountBookingAll'] = $totalCountBookingAll;
        $data['totalCountProductBooking'] = $totalCountProductBooking;
        $data['totalCountServiceBooking'] = $totalCountServiceBooking;

        //discount
        $data['totalCommissionExpensesDiscount'] = $totalCommissionExpensesDiscount;
        $data['totalCompanyIncomeDiscount'] = $totalCompanyIncomeDiscount;
        $data['totalPromotionExpensesDiscount'] = $totalPromotionExpensesDiscount;

        $data['totalCustomerCount'] = $totalCustomerCount;
        $data['totalCustomerEmptyNumberCount'] = $totalCustomerEmptyNumberCount;
        $data['totalCustomerPhoneNumber'] = $totalCustomerPhoneNumber;
        return (object) $data;
    }

    public function PayLiabilities($first, $last, $shop_id)
    {
        return Booking::where(function ($q) use ($first, $last, $shop_id) {
            $q->where('payment_status', 'Paid');
            if ($first && $last) {
                $q->whereDate('payment_date', '>=', $first);
                $q->whereDate('payment_date', '<=', $last);
            }
            if ($shop_id) {
                $q->where('shop_id', $shop_id);
            }
        })->sum('total_price');
    }
    public function chartBookingTime($firstDate, $shop_id)
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

    //chartData
    public function chartCustomerDayByDay($date, $shop)
    {
        $titleDay = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];
        $days = [];
        foreach ($titleDay as $index => $day) {
            $item = [
                "name" => $day,
                "totalCustomer" => 0
            ];
            $days[$day] = (object)$item;
        }
        $dataBooking = Booking::where(function ($query) use ($date, $shop) {
            $query->whereDate('booking_date', $date);
            if ($shop) {
                $query->where('shop_id', $shop->id);
            }
        })->get();
        foreach ($dataBooking as $item) {
            $item->date = Carbon::parse($item->booking_date)->dayName;
            $item->timeNumber = (int)Carbon::parse($item->booking_date)->format('Hi');
            //barChart
            if ($days[$item->date]) {
                $days[$item->date]->totalCustomer += 1;
            }
        }
        //$this->chartBookingByTime($date, $shop, $dataBooking);
        return $days;
    }

    public function chartShopBooking($date, $shop)
    {
        $data = Booking::with(['shop' => function ($qShop) {
            $qShop->select('id', 'name', 'nick_name');
        }])->select(
            DB::raw('sum(total_price) as totalPrice'),
            DB::raw('sum(total_discount) as totalDiscount'),
            DB::raw('sum(total_commission) as totalCommission'),
            DB::raw('shop_id')
        )
            ->where(function ($query) use ($date, $shop) {
                $query->whereDate('booking_date', $date);
                if ($shop) {
                    $query->where('shop_id', $shop->id);
                }
            })
            ->groupBy('shop_id')
            ->get();
        return $data;
    }

    private function chartBookingByTime($date, $shop)
    {
        $formDate = $this->getDate($date);
        $dateLast2Week = $this->getDate($formDate)->subWeek()->format('Y-m-d');
        $dateLast3Week = $this->getDate($dateLast2Week)->subWeek()->format('Y-m-d');
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

        $dataLastWeek = $this->bookingTimeFilter($formDate->format('Y-m-d'), $formDate->format('Y-m-d'), $shop?->id);
        if (count($dataLastWeek) > 0) {
            foreach ($dataLastWeek as $item) {
                $timeNumber = (int)Carbon::parse($item->booking_date)->format('Hi');
                if (count($item->bookingDetail) > 0) {
                    foreach ($item->bookingDetail as $itemDetail) {
                        //chartBookingTime
                        foreach ($times as $index => $time) {
                            if ($timeNumber >= (int)$time->form_time && $timeNumber <= (int)$time->to_date && $itemDetail->type == "service") {
                                $time->serviceCount += 1;
                                $time->total += 1;
                                $time->totalLastWeek += 1;
                            }
                        }
                    }
                }
            }
        }



        $dataLast2Week = $this->bookingTimeFilter($dateLast2Week, $formDate->format('Y-m-d'), $shop?->id);
        if (count($dataLast2Week) > 0) {
            foreach ($dataLast2Week as $l2item) {
                $timeNumber2 = (int)Carbon::parse($l2item->booking_date)->format('Hi');
                if (count($l2item->bookingDetail) > 0) {
                    foreach ($l2item->bookingDetail as $itemDetail) {
                        //chartBookingTime
                        foreach ($times as $index => $time) {
                            if ($timeNumber2 >= (int)$time->form_time && $timeNumber2 <= (int)$time->to_date && $itemDetail->type == "service") {
                                $time->serviceCount += 1;
                                $time->total += 1;
                                $time->totalLastWeek2 += 1;
                            }
                        }
                    }
                }
            }
        }

        //dataLast3Week

        $dataLast3Week = $this->bookingTimeFilter($dateLast3Week, $dateLast2Week, $shop?->id);
        if (count($dataLast3Week) > 0) {
            foreach ($dataLast3Week as $l3item) {
                $timeNumber3 = (int)Carbon::parse($l3item->booking_date)->format('Hi');
                if (count($l3item->bookingDetail) > 0) {
                    foreach ($l3item->bookingDetail as $itemDetail) {
                        //chartBookingTime
                        foreach ($times as $index => $time) {
                            if ($timeNumber3 >= (int)$time->form_time && $timeNumber3 <= (int)$time->to_date && $itemDetail->type == "service") {
                                $time->serviceCount += 1;
                                $time->totalLastWeek3 += 1;
                            }
                        }
                    }
                }
            }
        }

        foreach ($times as $index => $item) {
            $indexData = $index == 0 ? $index : $index - 1;
            if (!$times[$index]->totalLastWeek) {
                $times[$index]->totalLastWeek =  $times[$indexData]->totalLastWeek;
            } else {
                $times[$index]->totalLastWeek += $times[$indexData]->totalLastWeek;
            }
            if (!$times[$index]->totalLastWeek2) {
                $times[$index]->totalLastWeek2 =  $times[$indexData]->totalLastWeek2;
            }
            else {
                $times[$index]->totalLastWeek2 += $times[$indexData]->totalLastWeek2;
            }
            if (!$times[$index]->totalLastWeek3) {
                $times[$index]->totalLastWeek3 =  $times[$indexData]->totalLastWeek3;
            }
            else {
                $times[$index]->totalLastWeek3 += $times[$indexData]->totalLastWeek3;
            }
        }

       // dd($times);

        return $times;
    }

    //filterNone
    /**
     * Write code on Method
     *
     * @return response()
     */
    private function PendingPayment()
    {
        $item = (object) [
            'totalPendingPayment' => $this->PendingPaymentFindDaysOfAgo($this->getDateSubDay()),
            'totalPendingPayment2DaysAgo' => $this->PendingPaymentFindDaysOfAgo($this->getDateSubDay(2)),
            'totalPendingPayment3DaysAgo' => $this->PendingPaymentFindDaysOfAgo($this->getDateSubDay(3)),
        ];
        $item->totalPendingPayment->percent = ($item->totalPendingPayment->total / $item->totalPendingPayment->total) * 100;
        $item->totalPendingPayment2DaysAgo->percent = ($item->totalPendingPayment2DaysAgo->total / $item->totalPendingPayment->total) * 100;
        $item->totalPendingPayment3DaysAgo->percent = ($item->totalPendingPayment3DaysAgo->total / $item->totalPendingPayment->total) * 100;

        return $item;
    }

    private function PendingPayment00()
    {
        // $currentDate = $this->getDateSubDay();
        // dd($currentDate, 'CurrentDate');
        // $TwoDaysAgo = $this->getDateSubDay(20);
        // // dd($TwoDaysAgo);
        // // $ThreeDaysAgo = $this->getDateSubDay(3);
        // DB::enableQueryLog();
        // $data['1'] = Booking::select(
        //     DB::raw('sum(total_price) as totalPrice'),
        //     DB::raw('sum(total_discount) as totalDiscount'),
        //     DB::raw('sum(total_commission) as totalCommission'),
        //     DB::raw('payment_status as paymentStatus')
        // )
        //     ->where('payment_status', 'Pending')
        //     ->groupBy('paymentStatus')
        //     ->orderBy('totalPrice', 'desc')
        //     ->first();
        // // $totalPayingPayment = $data->totalPrice - ($data->totalCommission + $data->totalDiscount);
        // $data['2'] = Booking::select(
        //     DB::raw('sum(total_price) as totalPrice'),
        //     DB::raw('sum(total_discount) as totalDiscount'),
        //     DB::raw('sum(total_commission) as totalCommission'),
        //     DB::raw('payment_status as paymentStatus')
        // )
        //     ->where('payment_status', 'Pending')
        //     ->groupBy('paymentStatus')
        //     ->orderBy('totalPrice', 'desc')
        //     ->first();
        // $data['3'] = Booking::select(
        //     DB::raw('sum(total_price) as totalPrice'),
        //     DB::raw('sum(total_discount) as totalDiscount'),
        //     DB::raw('sum(total_commission) as totalCommission'),
        //     DB::raw('payment_status as paymentStatus')
        // )
        //     ->whereDate('booking_date', '<=', $TwoDaysAgo)
        //     ->where('payment_status', 'Pending')
        //     ->groupBy('paymentStatus')
        //     ->orderBy('totalPrice', 'desc')
        //     ->first();

        // $totalPayingPayment = 0;
        // $data =  Booking::where(function ($q) {
        //     $q->where('payment_status', 'Pending');
        // })->get();
        // if (count($data) > 0) {
        //     foreach ($data as $val) {
        //         $price = $val->total_price - ($val->total_discount + $val->total_commission);
        //         $totalPayingPayment += $price;
        //     }
        // }
        // $query = DB::getQueryLog();

        // dd($data);
        return 0;
    }

    private function PendingPaymentFindDaysOfAgo($date)
    {
        $data = Booking::select(
            DB::raw('sum(total_price) as totalPrice'),
            DB::raw('sum(total_discount) as totalDiscount'),
            DB::raw('sum(total_commission) as totalCommission'),
            DB::raw('payment_status as paymentStatus')
        )
            ->whereDate('booking_date', '<=', $date)
            ->where('payment_status', 'Pending')
            ->groupBy('paymentStatus')
            ->orderBy('totalPrice', 'desc')
            ->first();

        $totalPrice = isset($data->totalPrice) && $data->totalPrice ? $data->totalPrice : 0;

        $data = (object) [
            'total' => $totalPrice ? ($totalPrice - ($data->totalDiscount + $data->totalCommission)) : 0,
            'percent' => 0
        ];
        return $data;
    }

    private function HighestPerformanceRate()
    {
        $data = Booking::select(
            DB::raw('sum(total_price) as totalPrice'),
            DB::raw('sum(total_discount) as totalDiscount'),
            DB::raw('sum(total_commission) as totalCommission'),
            DB::raw("DATE_FORMAT(booking_date,'%Y-%m-%d') as bookingDate")
        )
            ->groupBy('bookingDate')
            ->orderBy('totalPrice', 'desc')
            ->first();

        $data->total_income = isset($data->totalPrice) && $data->totalPrice ? $data->totalPrice - ($data->totalDiscount + $data->totalCommission) : 0;
        return $data;
    }
    private function EmployeeBalance()
    {
        return Barber::sum('wallet');
    }
    private function findCustomer($id)
    {
        return Customer::select('id', 'phone')->find($id);
    }
    private function getDate($date)
    {
        $dataTime =  $date ? Carbon::parse($date) : Carbon::now();
        return $dataTime;
    }
    private function getDateSubDay($number = 0)
    {
        return Carbon::now()->subDay($number)->format('Y-m-d');
    }
}
