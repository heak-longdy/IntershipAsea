<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\StockHistory;
use App\Models\StockIn;
use App\Models\StockOnHand;
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
            if (request('shop_id')) {
                $q->where('shop_id', request('shop_id'));
            }
            if (request('date')) {
                $q->whereDate('created_at', request('date'));
            }
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
                    "supplier_id" => $item->supplier->value,
                    "supplier_type" => "supplier",
                    "qty" => $item->qty->value,
                    "remark" => $item->remark->value,
                    "status" => 1,
                    "request_by" => Auth::user()->id,
                    "request_by_type" => "admin"
                ];
                $itemStockOnHand = [
                    "product_id" => $item->product_id->value,
                    "shop_id" => $item->shop_id->value,
                    "current_stock" => $item->qty->value,
                    "remark" => $item->remark->value,
                    "status" => 1,
                    "request_by" => Auth::user()->id,
                    "request_by_type" => "admin"
                ];
                $dataStock = StockIn::create($itemStock);

                $dataStockOnHand = StockOnHand::where('product_id', $item->product_id->value)->where('shop_id', $item->shop_id->value)->first();
                $currentStock = $dataStockOnHand ? $dataStockOnHand->current_stock : 0;

                if ($dataStockOnHand) {
                    $dataStockOnHand->update([
                        "current_stock" => $currentStock + (int)$item->qty->value,
                        "remark" => $item->remark->value,
                        "request_by" => Auth::user()->id,
                        "request_by_type" => "admin"
                    ]);
                } else {
                    StockOnHand::create($itemStockOnHand);
                }
                $itemStockHistory = [
                    "stock_id" => $dataStock->id,
                    "product_id" => $item->product_id->value,
                    "current_stock" => $currentStock + (int)$item->qty->value,
                    "stock_in" => $item->qty->value,
                    "shop_id" => $item->shop_id->value,
                    "to_id" => $item->supplier->value,
                    "qty" => $item->qty->value,
                    "remark" => $item->remark->value,
                    "status" => 'stock_in',
                    "type"  => 'shop',
                    "request_by" => Auth::user()->id,
                    "request_by_type" => "admin"
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
}
