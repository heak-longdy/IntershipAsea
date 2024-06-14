<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Type;
use App\Models\WalletHistory;
use App\Models\ShopService;
use App\Models\Service;
use App\Models\ShopProduct;
use App\Models\Barber;
use App\Models\Product;
use App\Models\Brand;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    protected $layout = 'admin::pages.shop.';
    function __construct()
    {
        $this->middleware('permission:shop-view', ['only' => ['index']]);
        $this->middleware('permission:shop-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:shop-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:shop-delete', ['only' => ['delete', 'restore', 'destroy']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-shop-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Shop::with('brand')->where('status', $req->status);
        } else {
            $query = Shop::onlyTrashed();
        }
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                $q->orWhere('phone', 'like', '%' . $search . '%');
            }
        })->orderBy('id', 'desc')->paginate(50);

        return view($this->layout . 'index', $data);
    }
    public function onCreate(Request $req)
    {
        $data['types'] = Type::get();
        $data['brands'] = Brand::where('status', 1)->get();
        $data["data"] = Shop::where('id', $req->id)->first();
        return view($this->layout . 'create', $data);
    }
    public function onEdit($id)
    {
        $data["data"] = Shop::find($id);
        if ($data['data']) {
            return view($this->layout . 'edit', $data);
        }
        return redirect()->route('admin-shop-list');
    }
    public function onSave(Request $req, $id = null)
    {

        $item = [
            "name" => $req->name,
            "nick_name" => $req->nick_name,
            "phone" => $req->phone,
            'total_point' => $req->total_point,
            "address" => $req->address,
            'type_id' => $req->type_id,
            'brand_id' => $req->brand_id,
            "image" =>  $req->image ?? $req->tmp_file ?? null,
            "status" => 1,
        ];

        $req->validate([
            "phone" => "required|unique:shops,phone" . ($id ? ",$id" : ''),
        ], [
            "phone.unique" => "Phone number already exist",
        ]);

        DB::beginTransaction();
        try {
            $status = "Create success.";
            if ($id) {
                $data = Shop::find($id);
                $data->update($item);
                $status = "Update success.";
            } else {
                $item["password"] = bcrypt($req->password);
                $data = Shop::create($item);

                Barber::create([
                    "name" => $req->name,
                    "phone" => $req->phone,
                    "address" => $req->address,
                    "image" =>  $req->image ?? $req->tmp_file ?? null,
                    'password' => bcrypt($req->password),
                    'shop_id' => $data->id,
                    'type' => 'shop',
                    'status' => 1,
                ]);
            }
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-shop-list', 1);
        } catch (\Exception $e) {
            DB::rollback();
            //dd($e->getMessage());
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function onUpdateStatus(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = Shop::find($req->id);
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

    public function onChangePassword(Request $req)
    {
        $shop = Shop::where('id', $req->id)->first();
        return view("admin::pages.shop.change-password", ['data' => $shop]);
    }

    public function onSavePassword(Request $req)
    {
        $item = [
            "password" => bcrypt($req->password),
        ];
        try {
            $shop = Shop::find($req->id);
            $shop->update($item);
            $status = "change password success";
            Session::flash("success", $status);
        } catch (\Exception $error) {
            Session::flash("warning", "change password unsuccess");
        }
        return redirect()->route("admin-shop-list", 1);
    }
    public function shopService($id)
    {
        $data['data'] = ShopService::with(['service'])->where('shop_id', $id)->paginate(50);
        // return $data['data'];
        $data['shop'] = Shop::find($id);
        return view($this->layout . 'service', $data);
    }
    public function shopServiceCreate()
    {
        $data['services'] = Service::where('status', 1)->get();
        return view($this->layout . 'create-service', $data);
    }
    public function onEditService($id)
    {
        $data["data"] = ShopService::find($id);
        // return $data["data"];
        $data['services'] = Service::where('status', 1)->get();
        if ($data['data']) {
            return view($this->layout . 'edit-service', $data);
        }
        return redirect()->route('admin-shop-shop-service');
    }
    public function saveService(Request $req)
    {
        //return $req->id;
        $item = [
            "price" => $req->price,
            "point" => $req->point,
            "commission" => $req->commission,
            "commission_type" => $req->commission ? $req->commission_type : null,
            "shop_id" => $req->shop_id,
            'status' => 1,
            'service_id' => $req->service_id,
        ];
        $acceptedId = $req->id ?? '';
        $req->validate([
            'service_id'  => [
                'required',
                Rule::unique('shop_services')->where(function ($query) {
                    return $query->where('shop_id', request('shop_id'))->where('service_id', request('service_id'));
                })->ignore($acceptedId),
            ],
        ], [
            "service_id.required" => "Pls select product",
            'service_id.unique' => "The service has already been taken.",
        ]);
        DB::beginTransaction();
        try {
            if ($req->id) {
                $data = ShopService::find($req->id);
                $data->update($item);
                $status = "Update success.";
            } else {
                $status = "Create success.";
                $data = ShopService::create($item);
            }
            //$status = "Create success.";
            // $data = ShopService::create($item);
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-shop-service-list', $req->shop_id);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function onUpdateStatusService(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = ShopService::find($req->id);
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
    public function shopProduct($id)
    {
        $data['data'] = ShopProduct::with(['product'])->where('shop_id', $id)->paginate(50);
        // return $data['data'];
        $data['shop'] = Shop::find($id);

        return view($this->layout . 'product', $data);
    }
    public function shopProductCreate(Request $req)
    {
        $data['products'] = Product::where('status', 1)->get();
        $data['product_id'] =  \Request::segment(4);
        // return $data['product_id'];
        $data['data'] =  ShopProduct::find($data['product_id']);

        return view($this->layout . 'create-product', $data);
    }
    public function onEditProduct($id)
    {
        $data["data"] = ShopProduct::find($id);
        // return $data["data"];
        $data['products'] = Product::where('status', 1)->get();
        if ($data['data']) {
            return view($this->layout . 'edit-product', $data);
        }
        return redirect()->route('admin-shop-shop-product');
    }
    public function saveProduct(Request $req)
    {
        // return $req->id;
        $item = [
            "price" => $req->price,
            "point" => $req->point,
            "commission" => $req->commission,
            "commission_type" => $req->commission ? $req->commission_type : null,
            "shop_id" => $req->shop_id,
            'status' => 1,
            'product_id' => $req->product_id,
        ];
        $acceptedId = $req->id ?? '';
        $req->validate([
            'product_id'  => [
                'required',
                Rule::unique('shop_products')->where(function ($query) {
                    return $query->where('shop_id', request('shop_id'))->where('product_id', request('product_id'));
                })->ignore($acceptedId),
            ],
        ], [
            "product_id.required" => "Pls select product",
            'product_id.unique' => "The product has already been taken.",
        ]);

        DB::beginTransaction();
        try {
            if ($req->id) {
                $data = ShopProduct::find($req->id);
                $data->update($item);
                $status = "Update success.";
            } else {
                $status = "Create success.";
                $data = ShopProduct::create($item);
            }
            //$status = "Create success.";
            //$data = ShopProduct::create($item);
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-shop-shop-product', $req->shop_id);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function onUpdateStatusProduct(Request $req)
    {
        $statusGet = 'Enable';
        try {
            $data = ShopProduct::find($req->id);
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
