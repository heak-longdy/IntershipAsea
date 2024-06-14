<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Exception;
class DiscountController extends Controller
{
    protected $layout = 'admin::pages.discount.';
    function __construct()
    {
        $this->middleware('permission:discount-view', ['only' => ['index']]);
        $this->middleware('permission:discount-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:discount-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-discount-list', 1);
        }else{
            $query = Discount::where('status', $req->status);
        }
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            }
        }) ->orderBy('id', 'desc')->paginate(50);

        return view($this->layout . 'index', $data);
    }
    public function onCreate(Request $req)
    {
        $data["data"] = Discount::where('id', $req->id)->first();
        return view($this->layout . 'create',$data);
    }
    public function onEdit($id)
    {
        $data["data"] = Discount::find($id);
        if ($data['data']) {
            return view($this->layout . 'edit', $data);
        }
       
        return redirect()->route('admin-discount-list',$data);
    }
}
