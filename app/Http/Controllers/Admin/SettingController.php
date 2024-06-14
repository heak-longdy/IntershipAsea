<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use DB;
use Illuminate\Support\Facades\Session;
class SettingController extends Controller
{
    protected $layout = 'admin::pages.setting.';
    function __construct()
    {
        $this->middleware('permission:setting-view', ['only' => ['index']]);
        $this->middleware('permission:setting-create', ['only' => ['onSave']]);
    }
    public function index()
    {
        $data['data'] = Setting::first();
        return view($this->layout . 'index', $data);
    }
    public function store(Request $req)
    {
        $data = Setting::first();
        $items = [
            'rate' => $req->rate,
            'service' => $req->service,
            'product' => $req->product,
        ];
        DB::beginTransaction();
        try {
            $data->update($items);
            $status = "Update success.";
            DB::commit();
            Session::flash('success', $status);
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
}
