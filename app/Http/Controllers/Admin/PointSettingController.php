<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\PointSetting;
use App\Models\Brand;
class PointSettingController extends Controller
{
    protected $layout = 'admin::pages.pointSetting.';
    public function __construct()
    {
        $this->middleware('permission:pointSetting-view', ['only' => ['index']]);
        $this->middleware('permission:pointSetting-create', ['only' => ['onSave']]);
        $this->middleware('permission:pointSetting-update', ['only' => ['onUpdate', 'onUpdateStatus']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-pointSetting-list', 1);
        }else{
            $query = PointSetting::with('brand')->where('status', $req->status);
        }
        
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            }
        }) ->orderBy('id', 'asc')->paginate(50);
        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        $data['brands'] = Brand::where('status',1)->get();
        return view($this->layout . 'create')->with($data);
    }
    public function onSave(Request $request, $id = null)
    {
        $items = [
            'brand_id' => $request->brand_id,
            'discount' => $request->discount,
            'discount_type' => $request->discount_type,
            'used_count' => $request->used_count,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
        ];
        try {
            $status = "Create success.";
            if ($id) {
                $data = PointSetting::find($id);
                $data->update($items);
                $status = "Update success.";
            } else {
                PointSetting::create($items);
            }
            Session::flash('success', $status);
            return redirect()->route('admin-pointSetting-list', 1);
        } catch (\Exception $e) {
            return $e;
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function onEdit($id)
    {
        $data["data"] = PointSetting::find($id);
        $data['brands'] = Brand::where('status',1)->get();
        if ($data['data']) {
            return view($this->layout . 'edit', $data);
        }
        return redirect()->route('admin-pointSetting-list');
    }

    public function onUpdateStatus(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = PointSetting::find($req->id);
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
