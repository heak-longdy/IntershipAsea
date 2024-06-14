<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryHistory;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\ShopProduct;
use App\Models\Shop;
class InventoryController extends Controller
{
    private $inventoryService;
    private $inventoryPath = 'admin::pages.inventory.';
    private $stockListPath = 'admin::pages.inventory.include.stock-list';
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
        $this->middleware('permission:inventory', ['stockCount', 'stockReceive', 'stockAdjust']);
    }

    //Stock Count
    public function stockCount(Request $request)
    {
        $shop_id =  \Request::segment(4);
        $data['shop'] = Shop::where('id',$shop_id)->first();
        $data['data'] = $this->inventoryService->getInventoryStock($shop_id);
        if ($request->ajax()) {
            return view($this->stockListPath, $data)->render();
        }
        return view($this->inventoryPath . 'stock-count.index', $data);
    }

    public function saveStockCount(Request $request)
    {
        $productIds = $request->arr_productId;
        $productQtys = $request->arr_productQty;
        $shop_id =  \Request::segment(4);
        DB::beginTransaction();
        try {
            if (isset($productIds) && isset($productQtys)) {
                foreach ($productQtys as $key => $productQty) {
                    $product = ShopProduct::where('id', $productIds[$key])->first();
                    $product->update(['qty' => $productQty]);

                    //history
                    $items['type'] = 'stock_count';
                    $items['shop_product_id'] = $productIds[$key];
                    $items['start_qty'] = $productQty;
                    $items['shop_id'] = $shop_id;
                    $items['end_qty'] = $productQty;
                    $items['stock_qty'] = $productQty;
                    $items['remark'] = sprintf(Auth::user()->username . ' has been counted stock for product %s with quantity %s', $product->name, $productQty);
                    $this->inventoryService->saveStockHistory($items);
                }
            }
            DB::commit();
            Session::flash('success', 'Stock counted success!');
            return 'success';
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return 'error';
        }
    }
    //End Stock Count

    //Stock Receive
    public function stockReceive(Request $request)
    {
        $shop_id =  \Request::segment(4);
        $data['shop'] = Shop::where('id',$shop_id)->first();
        $data['data'] = $this->inventoryService->getInventoryStock($shop_id);
        if ($request->ajax()) {
            return view($this->stockListPath, $data)->render();
        }
        return view($this->inventoryPath . 'stock-receive.index', $data);
    }

    public function saveStockReceive(Request $request)
    {
        $productIds = $request->arr_productId;
        $sizeIds = $request->arr_sizeId;
        $colorIds = $request->arr_colorId;
        $productQtys = $request->arr_productQty;
        $sizeQtys = $request->arr_sizeQty;
        $colorQtys = $request->arr_colorQty;
        $shop_id =  \Request::segment(4);
        DB::beginTransaction();
        try {
            if (isset($productIds) && isset($productQtys)) {
                foreach ($productQtys as $key => $productQty) {
                    $product = ShopProduct::where('id', $productIds[$key])->first();
                    if ($product) {
                        $oldQty = $product->qty ?? 0;
                        $product->update(['qty' => ($productQty + $oldQty)]);

                        //history
                        $items['type'] = 'stock_receive';
                        $items['shop_product_id'] = $productIds[$key];
                        $items['start_qty'] = $oldQty;
                        $items['shop_id'] = $shop_id;
                        $items['end_qty'] = $productQty;
                        $items['stock_qty'] = $oldQty + $productQty;
                        $items['remark'] = sprintf(Auth::user()->username . ' has been updated stock receive for product %s with quantity %s', $product->name, $productQty);
                        $this->inventoryService->saveStockHistory($items);
                    }
                }
            }
            DB::commit();
            Session::flash('success', 'Update stock receive success!');
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
            return 'error';
        }
    }
    //End Stock Receive

    //Stock Adjust
    public function stockAdjust(Request $request)
    {
        $shop_id =  \Request::segment(4);
        $data['shop'] = Shop::where('id',$shop_id)->first();
        $data['data'] = $this->inventoryService->getInventoryStock($shop_id);
        if ($request->ajax()) {
            return view($this->stockListPath, $data);
        }
        return view($this->inventoryPath . 'stock-adjust.index', $data);
    }

    public function saveStockAdjust(Request $request)
    {
        $productIds = $request->arr_productId;
        $sizeIds = $request->arr_sizeId;
        $colorIds = $request->arr_colorId;
        $productQtys = $request->arr_productQty;
        $sizeQtys = $request->arr_sizeQty;
        $colorQtys = $request->arr_colorQty;
        $shop_id =  \Request::segment(4);
        DB::beginTransaction();
        try {
            if (isset($productIds) && isset($productQtys)) {
                foreach ($productQtys as $key => $productQty) {
                    $product = ShopProduct::where('id', $productIds[$key])->first();
                    if ($product) {
                        $oldQty = $product->qty ?? 0;
                        $product->update(['qty' => ($oldQty - $productQty)]);

                        //history
                        $items['type'] = 'stock_adjust';
                        $items['shop_product_id'] = $productIds[$key];
                        $items['start_qty'] = $oldQty;
                        $items['shop_id'] = $shop_id;
                        $items['end_qty'] = $productQty;
                        $items['stock_qty'] = $oldQty - $productQty;
                        $items['remark'] = sprintf(Auth::user()->username . ' has been adjusted stock for product %s with quantity %s', $product->name, $productQty);
                        $this->inventoryService->saveStockHistory($items);
                    }
                }
            }
            DB::commit();
            Session::flash('success', 'Adjusted stock success!');
            return 'success';
        } catch (\Exception $e) {
            DB::rollBack();
            return 'error';
        }
    }
    //End Stock Adjust

    //Inventory History
    public function history(Request $request)
    {
        $shop_id =  \Request::segment(4);
        $data['shop'] = Shop::where('id',$shop_id)->first();
        $data['data'] = InventoryHistory::where('shop_id',$shop_id)->where(function ($q) {
            if (request('search')) {
                $q->where('type', 'like', '%' . request('search') . '%');
            }
        })->orderByDesc('id')->paginate(50);

        return view('admin::pages.inventory.history', $data);
    }
}
