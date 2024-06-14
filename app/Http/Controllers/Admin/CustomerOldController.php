<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\PointHistory;
use App\Models\CustomerReward;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Customer_Export;
class CustomerController extends Controller
{
    protected $layout = 'admin::pages.customer.';
    function __construct()
    {
        $this->middleware('permission:customer-view', ['only' => ['index']]);
        $this->middleware('permission:customer-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:customer-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:customer-delete', ['only' => ['delete', 'restore', 'destroy']]);
       // $this->SlideService = $itemSer;
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-customer-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Customer::where('status', $req->status);
        } else {
            $query = Customer::onlyTrashed();
        }
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                $q->orWhere('phone', 'like', '%' . $search . '%');
            }
        }) ->orderBy('id', 'desc')->paginate(50);

        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        return view($this->layout . 'create');
    }
    public function onEdit($id)
    {
        $data["data"] = Customer::find($id);
        if ($data['data']) {
            return view($this->layout . 'edit', $data);
        }
        return redirect()->route('admin-customer-list');
    }
    public function onSave(Request $req, $id = null)
    {

        $item = [
            "name" => $req->name,
            "phone" => $req->phone,
            "status" => 1,
            "ordering" => 2,
            "address" => $req->address,
            "profile" =>  $req->image ?? $req->tmp_file ?? null,
        ];

        $req->validate([
            "phone" => "required|unique:customers,phone" . ($id ? ",$id" : ''),
        ], [
            "phone.unique" => "Phone number already exist",
        ]);

        DB::beginTransaction();
        try {
            $status = "Create success.";
            if ($id) {
                $data = Customer::find($id);
                $data->update($item);
                $status = "Update success.";
            } else {
                $data = Customer::create($item);
            }
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-customer-list', 1);
        } catch (\Exception $e) {
            DB::rollback();
            //dd($e->getMessage());
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function onUpdateStatus(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = Customer::find($req->id);
            $data->update(['status' => $req->status]);
            if ($data->status !== '1') {
                $statusGet = 'Disable';
            }
            Session::flash('success', $statusGet);
            return redirect()->back();
        } catch (\Exception $error) {
            $status = false;
            return redirect()->back();
        }
    }
    public function bookingHistory($id)
    {
        $data['data'] = Booking::where('customer_id',$id)->paginate(50);
        $data['name'] = Customer::find($id);
        foreach($data['data'] as $item){
           $item->service =  BookingDetail::with(['service'])->where('booking_id', $item->id)->get();
        }
        return view($this->layout . 'bookings', $data);
    }
    public function bookingDetail(Request $req)
    {
        $data['data'] = BookingDetail::with(['service'])->where('booking_id', $req->id)->get();
        //return $data['data'];
        return view($this->layout . 'booking-detail', $data);
    }
    public function pointHistory($id)
    {
        $data['data'] = PointHistory::where('customer_id',$id)->paginate(50);
        $data['name'] = Customer::find($id);
        return view($this->layout . 'point-history', $data);
    }
    public function redeemHistory($id)
    {
        $data['data'] = CustomerReward::where('customer_id',$id)->paginate(50);
        $data['name'] = Customer::find($id);
        return view($this->layout . 'redeem-history', $data);
    }

    public function export_customer(Request $request){
        return Excel::download(new Customer_Export, 'customer.xlsx');
      
    }
}
