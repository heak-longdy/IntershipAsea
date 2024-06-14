<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Currency;
use Illuminate\Support\Facades\Log;
class CurrencyController extends Controller
{
    protected $layout = 'admin::pages.currency.';
    public function __construct()
    {
        $this->middleware('permission:currency-view', ['only' => ['index']]);
        $this->middleware('permission:currency-create', ['only' => ['onSave', 'onCreate']]);
        $this->middleware('permission:currency-update', ['only' => ['onUpdate', 'onUpdateStatus']]);
    }
    public function index(Request $req)
    {
        Log::info("Start: Admin/CurrencyController > index | admin: ".$req);
        try {
            if ($req->id != 'trash') {
                $query = Currency::where('status', $req->id);
            } else {
                $query = Currency::onlyTrashed();
            }

            $data['status'] = $req->id;
            $data['data'] = $query->when(filled(request('keyword')), function ($q) {
                $q->where(function ($q) {
                    $q->where('name', 'like', '%' . request('keyword') . '%');
                });
            })
                ->orderBy("ordering",'asc')
                ->paginate(50);
            return view($this->layout . 'index', $data);
        } catch (Exception $error) {
            Log::error("Error: Admin/CurrencyController > index | message: ". $error->getMessage());
        }

    }
    public function onCreate(Request $request)
    {
        Log::info("Start: Admin/CurrencyController > index | admin: ");
        try {
            $data['data'] = Currency::where('id', $request->id)->first();
            return view($this->layout . 'create',$data);
        } catch (Exception $error) {
            Log::error("Error: Admin/CurrencyController > index | message: ". $error->getMessage());
            return redirect()->back();
        }

    }
    public function onSave(Request $request, $id = null)
    {
        Log::info("Start: Admin/CurrencyController > onSave | admin: ".$request);
        $items = [
            'name' => $request->name,
            'status' => $request->status,
            'ordering' => $request->ordering,
        ];
        try {
            DB::beginTransaction();
            $status = "Create success.";
            if ($id) {
                $data = Currency::find($id);
                $data->update($items);
                $status = "Update success.";
            } else {
                Currency::create($items);
            }
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-currency-list', 1);
        } catch (Exception $error) {
            DB::rollback();
            Session::flash('warning', 'Create unsuccess!');
            Log::error("Error: Admin/CurrencyController > onSave | message: ". $error->getMessage());
            return redirect()->back();
        }
    }
    public function onUpdateStatus(Request $req)
    {
        Log::info("Start: Admin/CurrencyController > onUpdateStatus | admin: ".$req);
        $statusGet = 'Enable';
        DB::beginTransaction();
        try {
            $data = Currency::find($req->id);
            $data->update(['status' => $req->status]);
            if ($data->status !== '1') {
                $statusGet = 'Disable';
            }
            DB::commit();
            Session::flash('success', $statusGet);
            return redirect()->back();
        } catch (Exception $error) {
            DB::rollback();
            $status = false;
            Log::error("Error: Admin/CurrencyController > onUpdateStatus | message: ". $error->getMessage());
            return redirect()->back();
        }
    }
    public function restore($id)
    {
        Log::info("Start: Admin/CurrencyController > restore: ".$id);
        try{
            DB::beginTransaction();
            $data = Currency::withTrashed()->where('id', $id)->first();
            $data->restore();
            DB::commit();
            Session::flash('success', 'Restore success!');
            return redirect()->back();
        }catch (Exception $error) {
            DB::rollback();
            Session::flash('warning', 'Move to restore unsuccess!');
            Log::error("Error: Admin/CurrencyController > restore | message: ". $error->getMessage());
            return redirect()->back();
        }
    }
    public function destroy(Request $request)
    {
        Log::info("Start: Admin/CurrencyController > destroy: ".$request);
        try{
            $item = Currency::withTrashed()->where('id', $request->id)->first();
            DB::beginTransaction();
            if ($item) {
                $item->forceDelete();
            }
            DB::commit();
            Session::flash('success', 'Destroy success!');
            return redirect()->back();
        }catch (Exception $error) {
            DB::rollback();
            Session::flash('warning', 'Move to destroy unsuccess!');
            Log::error("Error: Admin/CurrencyController > destroy | message: ". $error->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        Log::info("Start: Admin/CurrencyController > delete: ".$request);
        try{
            $item = Currency::where('id', $request->id)->first();
            DB::beginTransaction();
            if ($item) {
                $item->delete();
            }
            DB::commit();
            Session::flash('success', 'Move to delete success!');
            return redirect()->back();
        }catch (Exception $error) {
            DB::rollback();
            Session::flash('warning', 'Move to delete unsuccess!');
            Log::error("Error: Admin/CurrencyController > delete | message: ". $error->getMessage());
            return redirect()->back();
        }
    }
}
