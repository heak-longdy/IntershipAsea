<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\Reward;
use App\Models\Product;
use App\Models\Service;
class RewardController extends Controller
{
    protected $layout = 'admin::pages.reward.';
    function __construct()
    {
        $this->middleware('permission:reward-view', ['only' => ['index']]);
        $this->middleware('permission:reward-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:reward-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:reward-delete', ['only' => ['delete', 'restore', 'destroy']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-reward-list', 1);
        }else{
            $query = Reward::where('status', $req->status);
        }
        
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('point', 'like', '%' . $search . '%');
            }
        }) ->orderBy('id', 'desc')->paginate(50);

        foreach($data['data'] as $index => $item){
            $item->products = $item->product_id?Product::whereIn('id',$item->product_id)->get():[];
            $item->services = $item->service_id?Service::whereIn('id',$item->service_id)->get():[];
        }
        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        //$data['customers'] = Reward::where('status',1)->limit(20)->get();
        return view($this->layout . 'create');
    }
    public function onEdit($id)
    {
        $data["data"] = Reward::find($id);
        return view($this->layout . 'edit', $data);
    }
    public function onSave(Request $req, $id = null)
    {
        $item = [
            "shop_id" => $req->shop_id,
            "product_id" => $req->product_id,
            "service_id" => $req->service_id,
            "start_date" => $req->start_date,
            "end_date" => $req->end_date,
            'name' => $req->name,
            'price' => $req->price,
            'des' => $req->des,
            'status' => 1,
        ];

        DB::beginTransaction();
        try {
            $status = "Create success.";
            if ($id) {
                $data = Reward::find($id);
                $data->update($item);
                $status = "Update success.";
            } else {
                $item["password"] = bcrypt($req->password);
                $data = Reward::create($item);
            }
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-reward-list', 1);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function onUpdateStatus(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = Reward::find($req->id);
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
}
