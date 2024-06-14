<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Requests\Admin\ResetPasswordRequest;
use App\Http\Requests\Admin\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\ModelHasPermission;
use App\Models\ModulePermission;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:admin-view', ['only' => ['index']]);
        $this->middleware('permission:admin-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:admin-update', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:admin-change-password', ['only' => ['onChangePassword']]);
        $this->middleware('permission:admin-permission', ['only' => ['savePermission']]);
    }


    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('admin-dashboard');
        }
        return view('admin::auth.sign-in');
    }

    public function index(Request $request)
    {
        if (!$request->id) {
            return redirect()->route('admin-user-list', 1);
        }
        $data['status'] = $request->id;
        if ($request->id != 'trash') {
            $query = User::where('status', $request->id);
        } else {
            $query = User::onlyTrashed();
        }
        $data['data'] = $query->when(filled(request('keyword')), function ($q) {
            $q->where(function ($q) {
                $q->where('username', 'like', '%' . request('keyword') . '%');
                $q->orWhere('phone', 'like', '%' . request('keyword') . '%');
                $q->orWhere('email', 'like', '%' . request('keyword') . '%');
            });
        })
            ->where('role', 'admin')
            ->orderByDesc("created_at")
            ->paginate(10);
        return view("admin::pages.user.index", $data);
    }

    // public function onCreate(Request $request)
    // {
    //     $data['data'] = User::where('id', $request->id)->first();
    //     return view('admin::pages.user.create', $data);
    // }

    public function onSave(UserRequest $req)
    {
        $item = $req->all();
        $status = "Create successful.";
        try {
            if (!$req->id) {
                $item["role"] = "admin";
                $item["password"] = bcrypt($req->password);
                User::create($item);
            } else {
                User::find($req->id)->update($item);
                $status = "Update successful.";
            }
            Session::flash("success", $status);
            return response()->json([
                "message" => true,
                "error" => false
            ]);
        } catch (Exception $error) {
            Session::flash('warning', 'Create unsuccess!');
            return response()->json([
                "message" => false,
                "error" => true
            ]);
        }
    }

    public function onUpdateStatus(Request $request)
    {
        $item = [
            "status" => $request->status,
        ];
        try {
            $status = $request->status == 2 ? "Disable successful!" : "Enable successful!";
            User::where("id", $request->id)->update($item);
            Session::flash("success", $status);
        } catch (Exception $error) {
            Session::flash('warning', 'Create unsuccess!');
        }
        return redirect()->back();
    }

    // public function onChangePassword(Request $request)
    // {
    //     $user = User::where('id', $request->id)->first();
    //     if ($user->role == 'super_admin') {
    //         return redirect()->route('admin-user-list', 1);
    //     }
    //     return view("admin::pages.user.change-password", ['data' => $user]);
    // }

    public function onSavePassword(ResetPasswordRequest $request)
    {
        $item = [
            "password" => bcrypt($request->password),
        ];
        try {
            $user = User::find($request->id);
            $user->update($item);
            $status = "change password success";
            Session::flash("success", $status);
            return response()->json([
                "message" => true,
                "error" => false
            ]);
        } catch (Exception $error) {
            Session::flash('warning', 'Create unsuccess!');
            return response()->json([
                "message" => false,
                "error" => true
            ]);
        }
    }

    // public function setPermission()
    // {
    //     $user = User::find(request("id"));
    //     if ($user->role == "super_admin" || $user->id == Auth::user()->id) {
    //         return redirect()->back();
    //     }
    //     $data["user"] = User::find(request('id'));
    //     // $data['ModulPermission'] = ModulePermission::select('parent_id')->groupBy('parent_id')->orderBy('sort_no')->get();
    //     $data['ModulPermission'] = ModulePermission::select('parent_id')->orderBy('sort_no')->get();
    //     $data['permission'] = $data["user"]->ModelHasPermission;
    //     return view("admin::pages.user.permission", $data);
    // }

    // public function savePermission(Request $req)
    // {
    //     $req->validate([
    //         "permission" => "required",
    //     ], [
    //         "permission.required" => "Permission required",
    //     ]);
    //     if (!$req->permission) {
    //         return redirect()->back();
    //     }
    //     DB::beginTransaction();
    //     try {
    //         $data = User::find($req->id);
    //         $permissions = Permission::pluck('name')->toArray();
    //         $revoke = array_diff($permissions, $req->permission);
    //         $data->givePermissionTo($req->permission);
    //         $data->revokePermissionTo($revoke);
    //         DB::commit();
    //         Session::flash("success", 'Set permission successful!');
    //         return redirect()->route("admin-user-list", 1);
    //     } catch (Exception $error) {
    //         DB::rollback();
    //         $status = "Permission unsuccess!";
    //         Session::flash("warning", $status);
    //         return redirect()->back();
    //     }
    // }
    public function restore($id)
    {
        Log::info("Start: Admin/UserController > restore: " . $id);
        try {
            DB::beginTransaction();
            $data = User::withTrashed()->where('id', $id)->first();
            $data->restore();
            DB::commit();
            Session::flash('success', 'Restore success!');
            return redirect()->back();
        } catch (Exception $error) {
            DB::rollback();
            Session::flash('warning', 'Move to restore unsuccess!');
            Log::error("Error: Admin/UserController > restore | message: " . $error->getMessage());
            return redirect()->back();
        }
    }
    public function destroy(Request $request)
    {
        Log::info("Start: Admin/UserController > destroy: " . $request);
        try {
            $item = User::withTrashed()->where('id', $request->id)->first();
            DB::beginTransaction();
            if ($item) {
                $item->forceDelete();
            }
            DB::commit();
            Session::flash('success', 'Destroy success!');
            return redirect()->back();
        } catch (Exception $error) {
            DB::rollback();
            Session::flash('warning', 'Move to destroy unsuccess!');
            Log::error("Error: Admin/UserController > destroy | message: " . $error->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        Log::info("Start: Admin/UserController > delete: " . $request);
        try {
            $item = User::where('id', $request->id)->first();
            DB::beginTransaction();
            if ($item) {
                $item->delete();
            }
            DB::commit();
            Session::flash('success', 'Move to delete success!');
            return redirect()->back();
        } catch (Exception $error) {
            DB::rollback();
            Session::flash('warning', 'Move to delete unsuccess!');
            Log::error("Error: Admin/UserController > delete | message: " . $error->getMessage());
            return redirect()->back();
        }
    }

    //Permission
    public function userPermission(Request $request)
    {
        $data['user'] = User::find($request->id);
        if ($data['user']->role == "super_admin" || $data['user']->id == Auth::user()->id) {
            return redirect()->back();
        }
        $data['ModulePermission'] = ModulePermission::with('permission')->orderBy('sort_no')->get();
        if (isset($data['ModulePermission']) && count($data['ModulePermission']) > 0)
            foreach ($data['ModulePermission'] as $module) {
                $module->check = false;
                if (isset($module->permission) && count($module->permission) > 0) {
                    foreach ($module->permission as $perItem)
                        if (in_array($perItem->id, $data['user']->ModelHasPermission->pluck('permission_id')->toArray())) {
                            $perItem->check = true;
                            $module->check = true;
                        } else {
                            $perItem->check = false;
                        }
                }
            }
        return response()->json($data);
    }
    public function userPermissionSave(PermissionRequest $req)
    {
        try {
            $data = User::find($req->id);
            $this->savePermission($req->permission, $data);
            Session::flash("success", "Set permission success");
            return response()->json([
                'status' => 'success',
                'message' => __('user.message.update.success'),
                'error' => false,
            ]);
        } catch (Exception $e) {
            Session::flash('warning', 'Set permission unsuccess!');
            return response()->json([
                'status' => 'error',
                'message' => __('user.message.error'),
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function savePermission($permission, $user)
    {
        $permissions = Permission::pluck('name')->toArray();
        $revoke = array_diff($permissions, $permission);
        $user->givePermissionTo($permission);
        $user->revokePermissionTo($revoke);
    }
}
