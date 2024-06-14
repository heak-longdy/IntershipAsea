<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockIn;
use App\Models\UOM;

class ProductController extends Controller
{
    protected $layout = 'admin::pages.product.';
    function __construct()
    {
        $this->middleware('permission:product-view', ['only' => ['index']]);
        $this->middleware('permission:product-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:product-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:product-delete', ['only' => ['delete', 'restore', 'destroy']]);
        // $this->SlideService = $itemSer;
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-product-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Product::where('status', $req->status);
        } else {
            $query = Product::onlyTrashed();
        }
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                // $q->orWhere('phone', 'like', '%' . $search . '%');
            }
        })
            ->with(['category'])
            ->with(['uom'])
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        $data['category'] = Category::where('status', 1)->get();
        $data['uom'] = UOM::where('status', 1)->get();
        return view($this->layout . 'create', $data);
    }
    public function onEdit($id)
    {
        $data["data"] = Product::find($id);
        $data['category'] = Category::where('status', 1)->get();
        $data['uom'] = UOM::where('status', 1)->get();
        if ($data['data']) {
            return view($this->layout . 'edit', $data);
        }
        return redirect()->route('admin-product-list');
    }
    public function onSave(Request $req, $id = null)
    {
        $item = [
            "name" => $req->name,
            "price" => $req->price,
            "category_id" => $req->category_id,
            "uom_id" => $req->uom_id,
            'status' => 1,
            'commission' => $req->commission,
            "image" =>  $req->image ?? $req->tmp_file ?? null,
        ];

        $req->validate([
            "name" => "required|unique:products,name" . ($id ? ",$id" : ''),
        ], [
            "name.unique" => "Product already exist",
        ]);

        DB::beginTransaction();
        try {
            $status = "Create success.";
            if ($id) {
                $data = Product::find($id);
                $data->update($item);
                $status = "Update success.";
            } else {
                $data = Product::create($item);
            }
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-product-list', 1);
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
            $data = Product::find($req->id);
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
