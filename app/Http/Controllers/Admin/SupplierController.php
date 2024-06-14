<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    protected $layout = 'admin::pages.supplier.';
    function __construct()
    {
        $this->middleware('permission:supplier-view', ['only' => ['index']]);
        $this->middleware('permission:supplier-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:supplier-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:supplier-delete', ['only' => ['delete', 'restore', 'destroy']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-supplier-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Supplier::where('status', $req->status);
        } else {
            $query = Supplier::onlyTrashed();
        }
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            }
        }) ->orderBy('ordering', 'asc')->paginate(50);

        return view($this->layout . 'index', $data);
    }
    public function onCreate(Request $req)
    {
        $data["data"] = Supplier::where('id', $req->id)->first();
        return view($this->layout . 'create',$data);
    }
    public function onSave(Request $req, $id = null)
    {
        $item = [
            "name" => $req->name,
            "status" => 1,
            "ordering" => $req->ordering,
            "user_id" => Auth::user()->id
        ];

        $req->validate([
            "name" => "required|unique:suppliers,name" . ($id ? ",$id" : ''),
        ], [
            "name.unique" => "Name already exist",
        ]);

        DB::beginTransaction();
        try {
            $status = "Create success.";
            if ($id) {
                $data = Supplier::find($id);
                $data->update($item);
                $status = "Update success.";
            } else {
                $data = Supplier::create($item);
            }
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-supplier-list', 1);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function onUpdateStatus(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = Supplier::find($req->id);
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
