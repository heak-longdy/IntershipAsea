<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\StockHistory;
use App\Models\Shop;
use App\Models\StockType;
use App\Models\Supplier;
use App\Models\UOM;

class StockMovementController extends Controller
{
    protected $layout = 'admin::pages.inventoryManagement.stockMovement.';
    function __construct()
    {
        $this->middleware('permission:stock-movement-view', ['only' => ['index']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $data['shop'] = $req->shop_id ? Shop::find($req->shop_id) : null;
        $query = new StockHistory();
        $data['data'] = $query->where(function ($q) {
            $q->whereHas("product", function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            });
            if (request('shop_id')) {
                $q->where('shop_id', request('shop_id'));
            }
            if (request('from_date') && !request('to_date')) {

                $q->whereDate('created_at', request('from_date'));
            }
            if (request('from_date') && request('to_date')) {
                $q->whereBetween('created_at', [request('from_date'), request('to_date')]);
            }
            if (request('status_type')) {
                $q->where('status', request('status_type'));
            }
        })->orderBy('id', 'desc')->paginate(50);
        foreach ($data['data'] as $index => $item) {
            if ($item->type == "customer") {
                $item->data_to = Customer::find($item->to_id);
            } elseif ($item->type == "shop" && $item->status != "stock_in") {
                $item->data_to = Shop::find($item->to_id);
            } elseif ($item->type == "shop" && $item->status == "stock_in") {
                $item->data_to = Supplier::find($item->to_id);
            } elseif ($item->type == "stock_type") {
                $item->data_to = StockType::where("key", $item->to_id)->first();
            }
        }
        return view($this->layout . 'index', $data);
    }
    public function report(Request $req)
    {
        $query = StockHistory::with(["shop", "product", "user"]);
        $data = $query->where(function ($q) {
            $q->whereHas("product", function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            });
            if (request('shop_id')) {
                $q->where('shop_id', request('shop_id'));
            }
            if (request('from_date') && !request('to_date')) {

                $q->whereDate('created_at', request('from_date'));
            }
            if (request('from_date') && request('to_date')) {
                
                $q->whereBetween('created_at', [request('from_date'), request('to_date')]);
            }
            if (request('status_type')) {
                $q->where('status', request('status_type'));
            }
        })->orderBy('id', 'desc')->get();
        foreach ($data as $index => $item) {
            if ($item->type == "customer") {
                $item->data_to = Customer::find($item->to_id);
            } elseif ($item->type == "shop" && $item->status != "stock_in") {
                $item->data_to = Shop::find($item->to_id);
            } elseif ($item->type == "shop" && $item->status == "stock_in") {
                $item->data_to = Supplier::find($item->to_id);
            } elseif ($item->type == "stock_type") {
                $item->data_to = StockType::where("key", $item->to_id)->first();
            }
            $item->category = isset($item->product->category_id) && $item->product->category_id ? Category::find($item->product->category_id) : null;
            $item->uom = isset($item->product->uom_id) && $item->product->uom_id ? UOM::find($item->product->uom_id) : null;
        }
        return response()->json($data);
    }
}
