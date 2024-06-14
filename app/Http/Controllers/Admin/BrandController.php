<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Brand;
class BrandController extends Controller
{
    protected $layout = 'admin::pages.brand.';
    public function __construct()
    {
        $this->middleware('permission:brand-view', ['only' => ['index']]);
        $this->middleware('permission:brand-create', ['only' => ['onSave']]);
        $this->middleware('permission:brand-update', ['only' => ['onUpdate', 'onUpdateStatus']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-brand-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Brand::where('status', $req->status);
        } else {
            $query = Brand::onlyTrashed();
        }
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            }
        })
            ->orderBy('id', 'asc')->paginate(50);

        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        return view($this->layout . 'create');
    }
    public function onSave(Request $request, $id = null)
    {
        $items = [
            'name' => $request->name,
            'status' => 1,
        ];
        try {
            $status = "Create success.";
            if ($id) {
                $data = Brand::find($id);
                $data->update($items);
                $status = "Update success.";
            } else {
                Brand::create($items);
            }
            Session::flash('success', $status);
            return redirect()->route('admin-brand-list', 1);
        } catch (\Exception $e) {
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function onEdit($id)
    {
        $data["data"] = Brand::find($id);
        if ($data['data']) {
            return view($this->layout . 'edit', $data);
        }
        return redirect()->route('admin-brand-list');
    }

    public function onUpdateStatus(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = Brand::find($req->id);
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
