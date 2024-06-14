<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\StockHistory;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class StockInController extends Controller
{
    protected $layout = 'admin::pages.inventoryManagement.stockIn.';
    function __construct()
    {
        $this->middleware('permission:stock-in-view', ['only' => ['index']]);
        $this->middleware('permission:stock-in-create', ['only' => ['onCreate', 'onSave']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        $data['shop'] = $req->shop_id ? Shop::find($req->shop_id) : null;
        $query = StockIn::where('status', 1);
        $data['data'] = $query->where(function ($q) {
            $q->whereHas("product", function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            });
            if(request('shop_id')){
                $q->where('shop_id', request('shop_id'));
            }
            if(request('date')){
                $q->whereDate('created_at', request('date'));
            }
            // if ($search) {
            //     $q->where('name', 'like', '%' . $search . '%');
            //     $q->orWhere('phone', 'like', '%' . $search . '%');
            // }
        })->orderBy('id', 'desc')->paginate(50);
        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        return view($this->layout . 'create');
    }
    public function onEdit($id)
    {
        if (!$id) {
            return redirect()->route('admin-stock-in-list');
        } else {
            $data["data"] = StockIn::with(["product", "shop"])->find($id);
            if ($data["data"]) {
                $data["data"]->stockHistory = StockHistory::where('status', 'stock_in')->where('stock_id', $data["data"]->id)->orderBy("created_at", "desc")->first();
                return view($this->layout . 'edit', $data);
            }
        }
        return redirect()->route('admin-stock-in-list');
    }
    public function onSave(Request $req)
    {
        $dataStock = null;
        $fromData = json_decode($req->fromData);
        DB::beginTransaction();
        try {
            $status = "Create success.";
            foreach ($fromData as $index => $item) {
                $itemStock = [
                    "product_id" => $item->product_id->value,
                    "shop_id" => $item->shop_id->value,
                    "qty" => $item->qty->value,
                    "remark" => $item->remark->value,
                    "status" => 1,
                    "request_by" => Auth::user()->id
                ];
                $dataStock = StockIn::where('product_id', $itemStock['product_id'])->where('shop_id', $itemStock['shop_id'])->first();
                $currentStock = $dataStock ? $dataStock->qty : 0;
                if ($dataStock) {
                    $dataStock->update([
                        "qty" => $currentStock + (int)$itemStock['qty'],
                        "remark" => $item->remark->value,
                        "request_by" => Auth::user()->id
                    ]);
                } else {
                    $dataStock = StockIn::create($itemStock);
                }
                $itemStockHistory = [
                    "stock_id" => $dataStock->id,
                    "product_id" => $itemStock['product_id'],
                    "current_stock" => $currentStock,
                    "stock_in" => $itemStock['qty'],
                    "shop_id" => $itemStock['shop_id'],
                    "qty" => $itemStock["qty"],
                    "remark" => $itemStock['remark'],
                    "status" => 'stock_in',
                    "request_by" => Auth::user()->id
                ];
                StockHistory::create($itemStockHistory);
            }
            DB::commit();
            Session::flash('success', $status);
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Create unsuccess!');
            return response()->json(['message' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function onUpdate(Request $req, $id = null)
    {
        $fromData = json_decode($req->fromData);
        DB::beginTransaction();
        try {
            $status = "Update success.";
            foreach ($fromData as $index => $item) {
                $currentStock = $item->product_id->currentQty ? $item->product_id->currentQty : 0;
                $itemStock = [
                    "qty" => $currentStock + (int)$item->qty->value,
                    "remark" => $item->remark->value,
                    "request_by" => Auth::user()->id
                ];
                $itemStockHistory = [
                    "current_stock" => $currentStock,
                    "stock_in" => $item->qty->value,
                    "qty" => $item->qty->value,
                    "remark" => $itemStock['remark'],
                    "request_by" => Auth::user()->id
                ];
                $dataStock = StockIn::find($id);
                $dataStock->update($itemStock);
                $dataStockHistory = StockHistory::where('stock_id', $id)->where('status', 'stock_in')->orderBy("created_at", "desc")->first();
                $dataStockHistory->update($itemStockHistory);
            }
            DB::commit();
            Session::flash('success', $status);
            return response()->json(['message' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Update unsuccess!');
            return response()->json(['message' => 'error', 'error' => $e->getMessage()]);
        }
    }
    public function onFind($product_id = null, $shop_id = null)
    {
        try {
            $data = StockIn::where('product_id', $product_id)->where('shop_id', $shop_id)->first();
            return response()->json($data);
        } catch (\Exception $error) {
            return response()->json(null);
        }
    }
}
