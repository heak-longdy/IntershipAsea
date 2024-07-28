<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobRequest;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    protected $layout = 'admin::pages.job.';
    public function index(Request $req)
    {
        $data['status'] = $req->status;
        if (!$req->status) {
            return redirect()->route('admin-job-list', 1);
        }
        if ($req->status != 'trash') {
            $query = Job::where('status', $req->status);
        } else {
            $query = Job::onlyTrashed();
        }
        $data['data'] = $query->orderBy('id', 'desc')->paginate(50);
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
        $data['data'] = Job::find($req->id);
        return view($this->layout . 'store', $data);
    }
    public function onSave(JobRequest $req, $id = "")
    {
        $status = $id ? "Update success." : "Create success.";
        DB::beginTransaction();
        try {
            $item = $req->all();
            Job::updateOrCreate(['id' => $id], $item);
            DB::commit();
            Session::flash('success', $status);
            return redirect()->route('admin-job-list', 1);
        } catch (\Exception $error) {
            DB::rollback();
            Session::flash('warning', $status);
            return redirect()->back();
        }
    }
}
