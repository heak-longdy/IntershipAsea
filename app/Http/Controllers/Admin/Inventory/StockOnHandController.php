<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StockHistory;
use App\Models\Shop;
use App\Models\StockIn;
use App\Models\StockOnHand;
use App\Models\StockTransfer;
use App\Models\UOM;

class StockOnHandController extends Controller
{
    protected $layout = 'admin::pages.inventoryManagement.stockOnHand.';
    function __construct()
    {
        $this->middleware('permission:stock-on-hand-view', ['only' => ['index']]);
        
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $data['shop'] = $req->shop_id ? Shop::find($req->shop_id) : null;
        $query =new StockOnHand();
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
    public function onFind($product_id = null, $shop_id = null)
    {
        try {
            $data = StockOnHand::where('product_id', $product_id)->where('shop_id', $shop_id)->first();
            return response()->json($data);
        } catch (\Exception $error) {
            return response()->json(null);
        }
    }
    public function report()
    {
        $query = StockOnHand::with(["shop", "product", "user"]);
        $data = $query->where(function ($q) {
            $q->whereHas("product", function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            });
            if (request('shop_id')) {
                $q->where('shop_id', request('shop_id'));
            }
            if (request('date')) {
                $q->whereDate('created_at', request('date'));
            }
        })->orderBy('id', 'desc')->get();
        foreach ($data as $index => $item) {
            $item->category = isset($item->product->category_id) && $item->product->category_id ? Category::find($item->product->category_id):null;
            $item->uom = isset($item->product->uom_id) && $item->product->uom_id ? UOM::find($item->product->uom_id):null;
        }
        return response()->json($data);
    }
}
