<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CustomerPointImport;
use App\Models\Customer;
use App\Models\CustomerPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class CustomerPointController extends Controller
{
    protected $layout = 'admin::pages.customerPoint.';
    function __construct()
    {
        $this->middleware('permission:customer-point-view', ['only' => ['index']]);
    }
    public function index(Request $req)
    {
        $data['data'] = CustomerPoint::with(["customer", "shop", "brand"])->where(function ($query) use ($req) {
            if ($req->from_date && !$req->to_date) {
                $query->whereDate('created_at', '>=', $req->from_date);
            } elseif ($req->from_date && $req->to_date) {
                $query->whereDate('created_at', '>=', $req->from_date);
                $query->whereDate('created_at', '<=', $req->to_date);
            }
        })->orderBy('id', 'asc')->paginate(50);
        return view($this->layout . 'index', $data);
    }

    public function report(Request $req)
    {
        $data = CustomerPoint::with(["customer", "shop", "brand"])->where(function ($query) use ($req) {
            if ($req->from_date && !$req->to_date) {
                $query->whereDate('created_at', '>=', $req->from_date);
            } elseif ($req->from_date && $req->to_date) {
                $query->whereDate('created_at', '>=', $req->from_date);
                $query->whereDate('created_at', '<=', $req->to_date);
            }
        })->orderBy('id', 'asc')->get();
        return response()->json($data);
    }

    public function importCreate()
    {
        $data['customers'] = Customer::get();
        return view($this->layout . 'importExcelFile',$data);
    }
    public function importSave(Request $req)
    {
        return false;
        // $item = (object) json_decode($req->val);
        // DB::beginTransaction();
        // try {
        //     $dataCustomer = Customer::create([
        //         "phone" => $item->customer_phone_number,
        //         "status" => 1
        //     ]);
        //     CustomerPoint::create([
        //         'customer_id' => $dataCustomer->id,
        //         'shop_id' => $item->shop_id,
        //         'brand_id' => $item->brand_id,
        //         'total_point' => $item->remaing_point,
        //         'total_receving_point' => $item->total_receving_points,
        //         'used_point' => $item->used_point,
        //         'count_of_using_service' => $item->count_of_using_service,
        //     ]);
        //     DB::commit();
        //     return $dataCustomer;
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return $e->getMessage();
        //     return 'error';
        // }
    }
}
