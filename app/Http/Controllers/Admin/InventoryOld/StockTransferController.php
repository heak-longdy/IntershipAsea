<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\StockHistory;
use App\Models\Shop;
use App\Models\ShopProduct;
use App\Models\StockIn;
use App\Models\StockTransfer;

class StockTransferController extends Controller
{
    protected $layout = 'admin::pages.inventoryManagement.stockTransfer.';
    function __construct()
    {
        $this->middleware('permission:stock-transfer-view', ['only' => ['index']]);
        $this->middleware('permission:stock-transfer-create', ['only' => ['onCreate', 'onSave']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $data['shop'] = $req->shop_id ? Shop::find($req->shop_id) : null;
        $query = StockTransfer::where('status', 1);
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
        return 'Blade Edit';
    }
    public function onSave(Request $req)
    {
        $dataStock = null;
        $fromData = json_decode($req->fromData);
        DB::beginTransaction();
        try {
            $status = "Create success.";
            foreach ($fromData as $index => $item) {
                $currentQty = $item->product_id?->currentQty ? $item->product_id?->currentQty : 0;
                $itemStock = [
                    "product_id" => $item->product_id->value,
                    "from_shop_id" => $item->shop_id->value,
                    "to_shop_id" => $item->shop_id_to->value,
                    "qty" => $item->qty->value,
                    "remark" => $item->remark->value,
                    "status" => 1,
                    "request_by" => Auth::user()->id
                ];
                $itemStockIn = [
                    "product_id" => $item->product_id->value,
                    "shop_id" => $item->shop_id_to->value,
                    "qty" => $item->qty->value,
                    "remark" => $item->remark->value,
                    "status" => 1,
                    "request_by" => Auth::user()->id
                ];

                $dataStock = StockTransfer::create($itemStock);

                $sumStockInShopProduct = StockIn::where('product_id', $itemStock['product_id'])->where('shop_id', $itemStock['to_shop_id'])->first();
                $stockOutShopProduct = StockIn::where('product_id', $itemStock['product_id'])->where('shop_id', $itemStock['from_shop_id'])->first();


                $stockOutShopProduct->update([
                    "qty" => $stockOutShopProduct->qty - (int)$itemStock['qty'],
                    "remark" => $item->remark->value,
                    "request_by" => Auth::user()->id
                ]);

                if ($sumStockInShopProduct) {
                    $sumStockInShopProduct->update([
                        "qty" => $sumStockInShopProduct->qty + (int)$itemStock['qty']
                    ]);
                } else {
                    $dataStockIn = StockIn::create($itemStockIn);
                    $product = Product::find($itemStock['product_id']);
                    $itemStockHistoryStockIn = [
                        "stock_id" => $dataStockIn->id,
                        "product_id" => $itemStock['product_id'],
                        "current_stock" => $currentQty,
                        "stock_in" => $itemStock['qty'],
                        "shop_id" => $itemStock['to_shop_id'],
                        "qty" => $itemStock["qty"],
                        "remark" => $itemStock['remark'],
                        "status" => 'stock_in',
                        "request_by" => Auth::user()->id
                    ];
                    $itemShopProduct = [
                        "shop_id" => $itemStock['to_shop_id'],
                        "product_id" => $itemStock['product_id'],
                        "promotion_id" => null,
                        "price" => $product->price,
                        "max_qty"   => 0,
                        "status" => 1,
                        "commission_type" => "khr",
                        "commission" => 0
                    ];
                    ShopProduct::create($itemShopProduct);
                    StockHistory::create($itemStockHistoryStockIn);
                }
                $itemStockHistory = [
                    "stock_id" => $dataStock->id,
                    "product_id" => $itemStock['product_id'],
                    "current_stock" => $currentQty,
                    "stock_out" => $itemStock['qty'],
                    "shop_id" => $itemStock['from_shop_id'],
                    "to_id" => $itemStock['to_shop_id'],
                    "qty" => $itemStock["qty"],
                    "remark" => $itemStock['remark'],
                    "status" => 'stock_transfer',
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
        return 'Update Submit';
    }
}
