<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromotionRequest;
use App\Models\Product;
use App\Models\Service;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\Promotion;
use App\Models\Customer;
use App\Models\ShopProduct;
use App\Models\ShopService;
use Carbon\Carbon;

class PromotionController extends Controller
{
    protected $layout = 'admin::pages.promotion.';
    function __construct()
    {
        $this->middleware('permission:promotion-view', ['only' => ['index']]);
        $this->middleware('permission:promotion-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:promotion-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:promotion-delete', ['only' => ['delete', 'restore', 'destroy']]);
    }

    public function index(Request $req)
    {
        // $data['data'] = Promotion::paginate(50);
        $search = $req->keyword ? $req->keyword : '';
        $data['status'] = $req->status;
        if (!$req->status) {
            return redirect()->route('admin-promotion-list', 1);
        } else {
            $query = Promotion::where('status', $req->status);
        }

        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('title', 'like', '%' . $search . '%');
            }
        })->orderBy('id', 'desc')->paginate(50);
        foreach ($data['data'] as $index => $item) {
            $item->shops =         $item->shop_id ? Shop::whereIn('id', $item->shop_id)->get() : [];
            $item->products = $item->product_id ? Product::whereIn('id', $item->product_id)->get() : [];
            $item->services = $item->service_id ? Service::whereIn('id', $item->service_id)->get() : [];
            $item->customers = $item->customer_id ? Customer::whereIn('id', $item->customer_id)->get() : [];
        }
        return view($this->layout . 'index', $data);
    }

    public function onCreate(Request $req)
    {
        $data['firstMonthDay'] = $req->from_date ? $req->from_date : Carbon::now()->format('Y-m-d');
        return view($this->layout . 'create', $data);
    }
    public function onEdit($id)
    {
        $promotion = Promotion::find($id);
        $promotion->customers = $this->getTypeData(Customer::class, $promotion?->customer_id, '');
        $promotion->shops = $this->getTypeData(Shop::class, $promotion?->shop_id, '');
        $promotion->products = $this->getTypeData(Product::class, $promotion?->product_id, 'product');
        $promotion->services = $this->getTypeData(Service::class, $promotion?->service_id, 'service');
        $data['data'] = $promotion;
        return view($this->layout . 'edit', $data);
    }

    public function getTypeData($model, $id, $type)
    {
        $select = ($type == "product" || $type == "service") ?  ['id', 'name'] : ['id', 'name', 'phone'];
        return $id ? $model::whereIn('id', $id)->select($select)->get() : [];
    }

    public function onSave(PromotionRequest $req, $id = null)
    {
        $id = $req->id;
        $item = $req->all();
        $item['status'] = 1;
        DB::beginTransaction();
        try {
            $status = "Create success.";
            if ($id) {
                $data = Promotion::find($id);
                $data->update($item);
                $status = "Update success.";
            } else {
                Promotion::create($item);
            }

            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-promotion-list', 1);
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
            $data = Promotion::find($req->id);
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
    public function promotion()
    {
        $shop = auth('barber-api')->user();
        $data = Promotion::whereJsonContains('shop_id', ["$shop->shop_id"])->get();
        $product = ShopProduct::with(['product'])->where('shop_id', $shop->id)->get();
        foreach ($product as $value) {  
            $value->promotion = Promotion::whereJsonContains('shop_id', ["$shop->shop_id"])->whereJsonContains('product_id', ["$value->product_id"])->get();
        }
        return $data;
    }
}
