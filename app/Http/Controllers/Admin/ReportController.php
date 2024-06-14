<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Booking;
use App\Report\Order;
use App\Models\Barber;
use App\Models\Service;
use DB;
class ReportController extends Controller
{
    protected $layout = 'admin::pages.report.';
    function __construct()
    {
        $this->middleware('permission:booking-view', ['only' => ['index']]);
        $this->middleware('permission:booking-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:booking-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:booking-delete', ['only' => ['delete', 'restore', 'destroy']]);
       // $this->SlideService = $itemSer;
    }
    public function barberReport()
    {
        $data['barberData'] = Barber::whereStatus(1)->orderByDesc('id')->get();
        $data['barbers'] = Barber::with('booking')->whereHas('booking')->paginate(50);
        return view($this->layout .'barber.index', $data);
    }
    public function shopReport()
    {
        $data['shopData'] = Shop::whereStatus(1)->orderByDesc('id')->get();
        $data['shops'] = Shop::with('booking')->whereHas('booking')->paginate(50);
        return view($this->layout .'shop.index', $data);
    }
    public function serviceReport()
    {
       $data['services'] = Service::with('booking')->whereHas('booking')->paginate(50);
       return $data['services'];
    }
    
}
