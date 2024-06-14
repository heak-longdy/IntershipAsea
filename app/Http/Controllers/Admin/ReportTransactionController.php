<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportTransactionController extends Controller
{
    protected $layout = 'admin::pages.reportTransaction.';
    function __construct()
    {
        $this->middleware('permission:report-transaction-view', ['only' => ['index']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        if (!$req->status) {
            return redirect()->route('admin-report-transaction-list', 'wallet');
        }
        $query = $this->query($req);
        $data['data'] = $query->paginate(50);
        $data['tranType'] = $req->tran_type ? explode(',', $req->tran_type) : [];
        foreach($data['tranType'] as $key=>$val){
            $data['type'][$val] = $val;
        }
        return view($this->layout . 'index', $data);
    }

    public function report(Request $req)
    {
        $query = $this->query($req);
        $data = $query->get();
        return response()->json($data);
    }
    public function query($req)
    {
        if ($req->status == "wallet") {
            $query = WalletHistory::with(["shop", "barber"])->where(function ($query) use ($req) {
                if ($req->tran_type) {
                    $query->whereIn('tran_type', explode(',', $req->tran_type));
                }
                if ($req->from_date && !$req->to_date) {
                    $query->whereDate('created_at', '>=', $req->from_date);
                }
                if ($req->from_date && $req->to_date) {
                    $query->whereDate('created_at', '>=', $req->from_date);
                    $query->whereDate('created_at', '<=', $req->to_date);
                }
            })->orderBy('id', 'desc');
        } else if ($req->status == "pay_liability") {
            $query = Booking::with(["shop", "barber"])->where(function ($query) {
                $query->where('payment_status', 'Paid');
                if (request('from_date') && request('to_date')) {
                    $query->whereBetween(DB::raw('DATE(payment_date)'), [request('from_date'), request('to_date')]);
                } else if (request('from_date') && !request('to_date')) {
                    $query->whereDate('payment_date', request('from_date'));
                }
            })->orderBy('payment_date', 'desc');
        }
        return $query;
    }
}
