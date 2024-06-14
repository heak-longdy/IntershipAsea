<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Barber;
use App\Models\CommissionHistory;
use App\Models\WalletHistory;
use App\Models\Setting;
use Carbon\Carbon;

class BarberController extends Controller
{
    protected $layout = 'admin::pages.barber.';
    function __construct()
    {
        $this->middleware('permission:barber-view', ['only' => ['index']]);
        $this->middleware('permission:barber-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:barber-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:barber-delete', ['only' => ['delete', 'restore', 'destroy']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-barber-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Barber::with('shop')->where('status', $req->status);
        } else {
            $query = Barber::with('shop')->onlyTrashed();
        }
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                $q->orWhere('phone', 'like', '%' . $search . '%');
            }
        })->orderBy('id', 'desc')->paginate(50);

        return view($this->layout . 'index', $data);
    }
    public function onCreate(Request $req)
    {
        $data['number_id'] = $this->numberID();
        $data["data"] = Barber::where('id', $req->id)->first();
        $data["shops"] = Shop::where('status', 1)->get();
        return view($this->layout . 'create', $data);
    }
    public function onEdit($id)
    {
        $data["data"] = Barber::find($id);
        if ($data['data']) {
            return view($this->layout . 'edit', $data);
        }
        $data["shops"] = Shop::where('status', 1)->get();
        return redirect()->route('admin-barber-list', $data);
    }
    public function onSave(Request $req, $id = null)
    {
        $item = [
            "number_id" => $req->number_id,
            "name" => $req->name,
            "gender" => $req->gender,
            "phone" => $req->phone,
            "dob" => $req->dob,
            "address" => $req->address,
            "status" => 1,
            "shop_id" => $req->shop_id,
            "commission" => $req->commission,
            //"wallet" => $req->wallet,
            "image" =>  $req->image ?? $req->tmp_file ?? null,
        ];

        $req->validate([
            "number_id" => "required|unique:barbers,number_id" . ($id ? ",$id" : ''),
            "phone" => "required|unique:barbers,phone" . ($id ? ",$id" : ''),
        ], [
            "number_id.required" => "Barber id required",
            "number_id.unique" => "Barber id already exist",
            "phone.unique" => "Phone number already exist",
        ]);

        DB::beginTransaction();
        try {
            $status = "Create success.";
            if ($id) {
                $data = Barber::find($id);
                $item["password"] = bcrypt($req->password);
                $data->update($item);
                $status = "Update success.";
            } else {
                $item["password"] = bcrypt($req->password);
                $data = Barber::create($item);
            }
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-barber-list', 1);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function numberID()
    {
        $no_num = 'B0001';
        $barber = Barber::whereNotNull('number_id')->orderBy('number_id', 'desc')->first();
        if (isset($barber->number_id) && $barber->number_id) {
            $number = str_replace("B", "", $barber->number_id);
            $numbers = str_pad($number + 1, 4, "0", STR_PAD_LEFT);  //0002
            $no_num = "B" . $numbers;
        }
        return $no_num;
    }
    public function onUpdateStatus(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = Barber::find($req->id);
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

    public function onChangePassword(Request $req)
    {
        $barber = Barber::where('id', $req->id)->first();
        return view("admin::pages.barber.change-password", ['data' => $barber]);
    }

    public function onSavePassword(Request $req)
    {
        $item = [
            "password" => bcrypt($req->password),
        ];
        try {
            $barber = Barber::find($req->id);
            $barber->update($item);
            $status = "change password success";
            Session::flash("success", $status);
        } catch (\Exception $error) {
            Session::flash("warning", "change password unsuccess");
        }
        return redirect()->route("admin-barber-list", 1);
    }
    public function commissionHistory(Request $req, $id)
    {
        $from = $req->from_date ? $req->from_date : '';
        $to = $req->to_date ? $req->to_date : '';
        $data['data'] = CommissionHistory::where('barber_id', $id)
            ->when(function ($q) use ($from, $to) {
                $q->whereBetween('commission_date', [$from, $to]);
            })->paginate(50);
        $data['name'] = Barber::find($id);
        return view($this->layout . 'commission-history', $data);
    }
    public function walletHistory($id)
    {
        if (!$id) {
            return redirect()->route('admin-barber-list', 1);
        }
        $data['data'] = WalletHistory::where('barber_id', $id)->paginate(50);
        $data['name'] = Barber::find($id);
        return view($this->layout . 'walletHistory', $data);
        //$data['data'] = WalletHistory::where('barber_id',$id)->paginate(50);
        //$data['name'] = Barber::find($id);
        //return view($this->layout . 'wallet-history', $data);
    }
    public function onTopUp(Request $req)
    {
        $barber = Barber::where('id', $req->id)->first();
        return view("admin::pages.barber.top-up", ['data' => $barber]);
    }

    public function saveWallet(Request $req)
    {
        $req->validate([
            "amount" => 'required|numeric|max:50',
            "image"   => 'required',
            "remark"    => 'required'
        ], [
            "image.required" => "Image is required",
            "remark.required" => "Remark is required",
            "amount.required" => "Amount is required",
            "amount.max" => "Amount largest number 50 dollar",
        ]);
        $r = Setting::first();
        if ($r) {
            $top_rate = $r->rate;
        } else {
            $top_rate = 4100;
        }
        $kh = $req->amount * $top_rate;
        $item = [
            "amount" => $kh,
            'amount_dollar' => $req->amount,
            //"shop_id" => $req->id,
            'barber_id' => $req->id,
            'status' => 2,
            'remark' => $req->remark,
            "image" =>  $req->image ?? $req->tmp_file ?? null,
            'status_date' => Carbon::now(),
            "tran_type" => 'Admin Top Up',
        ];

        $req->validate([
            "amount" => "required",
        ], [
            "amount.required" => "Pls enter amount",
        ]);

        DB::beginTransaction();
        try {
            $status = "Create success.";
            $data = WalletHistory::create($item);
            $bar = Barber::where('id', $req->id)->first();
            $bar->wallet = $bar->wallet + $req->amount;
            $bar->save();
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-barber-list', 1);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function reportExcel(Request $req)
    {
        $search = $req->search ? $req->search : '';
        if ($req->status != 'trash') {
            $query = Barber::with('shop')->where('status', $req->status);
        } else {
            $query = Barber::with('shop')->onlyTrashed();
        }
        $data = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                $q->orWhere('phone', 'like', '%' . $search . '%');
            }
        })->orderBy('id', 'desc')->get();
        if (count($data) > 0) {
            $setting = Setting::first();
            $rate = isset($setting->rate) && $setting->rate ? $setting->rate : 4000;
            foreach ($data as $item) {
                $wallet = $item->wallet ?? 0;
                $item->wallet_dollar = $wallet ? ($wallet / $rate) : 0;
            }
        }
        return response()->json($data);
    }
    public function restore($id)
    {
        $data = Barber::withTrashed()->where('id', $id)->first();
        $data->restore();
        Session::flash('success', 'Restore move to trash!');
        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $item = Barber::where('id', $request->id)->first();
        if ($item) {
            $item->delete();
        }
        Session::flash('success', 'Move to trash success!');
        return redirect()->back();
    }

}
