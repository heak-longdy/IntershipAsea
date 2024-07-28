<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PartnerController extends Controller
{
    protected $layout = 'admin::pages.partner.';
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        $search = $req->search ? $req->search : '';
        if (!$req->status) {
            return redirect()->route('admin-partner-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Partner::where('status', $req->status);
        } else {
            $query = Partner::onlyTrashed();
        }
        $data['data'] = $query->where(function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', '%' . $search . '%');
                $q->orWhere('phone', 'like', '%' . $search . '%');
            }
        }) ->orderBy('order', 'desc')->paginate(50);

        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        $data['id'] = "";
        return view($this->layout . 'store', $data);
    }
    public function onEdit(Request $req)
    {
        $data['id'] = $req->id;
        $data['data'] = Partner::find($req->id);
        return view($this->layout . 'store', $data);
    }
    public function onSave(PartnerRequest $req, $id = "")
    {
        $status = $id ? "Update success." : "Create success.";
        DB::beginTransaction();
        try {
            $item = $req->all();
            Partner::updateOrCreate(['id' => $id], $item);
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-partner-list', 1);
        } catch (\Exception $error) {
            DB::rollback();
            Session::flash('warning', $status);
            return redirect()->back();
        }
    }
}
