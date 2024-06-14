<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
use App\Models\BrandSetting;
use Exception;
use DB;

class BrandSettingController extends Controller
{
    protected $layout = 'admin::pages.brandSetting.';
    function __construct()
    {
        $this->middleware('permission:brandSetting-view', ['only' => ['index']]);
        $this->middleware('permission:brandSetting-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:brandSetting-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:brandSetting-delete', ['only' => ['delete', 'restore', 'destroy']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-brandSetting-list', 1);
        }else{
            $query = BrandSetting::with('brand')->where('status','!=',0)->where('status',$req->status)->orderBy('id', 'desc');
        }
        
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('point', 'like', '%' . $search . '%');
            }
        })->paginate(50);
        //return $data['data'];
        foreach($data['data'] as $index => $item){
            $item->brands = $item->brand_point_use?Brand::whereIn('id',$item->brand_point_use)->get():[];
        }
        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        $data['brands'] = Brand::where('status',1)->get();
        return view($this->layout . 'create')->with($data);
    }
    public function onEdit($id)
    {
        $data["data"] = BrandSetting::find($id);
        $data['brands'] = Brand::where('status',1)->get();
        return view($this->layout . 'edit', $data);
    }
    public function onSave(Request $req, $id = null)
    {
        $item = [
            "brand_id" => $req->brand_id,
            "brand_point_use" => $req->brand_point_use,
          	"status" => 1,
        ];

        DB::beginTransaction();
        try {
            $status = "Create success.";
            if ($id) {
                $data = BrandSetting::find($id);
                $data->update($item);
                $status = "Update success.";
            } else {
                $item["password"] = bcrypt($req->password);
                $data = BrandSetting::create($item);
            }
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-brandSetting-list', 1);
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
            $data = BrandSetting::find($req->id);
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
