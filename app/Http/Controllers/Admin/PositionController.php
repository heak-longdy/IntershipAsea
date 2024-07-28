<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\VarDumper;

class PositionController extends Controller
{
    protected $layout = 'admin::pages.position.';
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        if (!$req->status) {
            return redirect()->route('admin-position-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Position::where('status', $req->status);
        } else {
            $query = Position::onlyTrashed();
        }
        $data['data'] = $query->orderBy('id', 'desc')->paginate(50);
        return view($this->layout . 'index', $data);
    }
    public function onCreate()
    {
        $data['id'] = "";
        return view($this->layout . 'create', $data);
    }
    public function onEdit(Request $req)
    {
        $data['id'] = $req->id;
        $data['data'] = Position::find($req->id);
        return view($this->layout . 'create', $data);
    }
    public function onSave(PositionRequest $req, $id = "")
    {
        $status = $id ? "Update success." : "Create success.";
        DB::beginTransaction();
        try {
            $item = $req->all();
            Position::updateOrCreate(['id' => $id], $item);
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-position-list', 1);
        } catch (\Exception $error) {
            DB::rollback();
            Session::flash('warning', $status);
            return redirect()->back();
        }
    }
}
