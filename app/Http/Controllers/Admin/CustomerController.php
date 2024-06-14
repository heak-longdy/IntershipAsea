<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Revenue;
use App\Models\RevenueDetail;
use App\Models\ExpenseDetail;
use App\Models\Expense;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\IncomeExport;
use Exception;
use Illuminate\Support\Facades\DB;
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-view', ['only' => ['index']]);
        $this->middleware('permission:user-view-detail', ['only' => ['income','expense']]);
        $this->middleware('permission:user-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:user-update', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:user-change-password', ['only' => ['onChangePassword']]);
    }

    public function index(Request $request)
    {
        Log::info("app > Http > Controllers > Admin > CustomerController.php > index");

        // Log::info('User accessing for customer list: '.$request->id);
        $data['status'] = $request->id;
        if ($request->id != 'trash') {
            $query = User::where('status', $request->id);
        } else {
            $query = User::onlyTrashed();
        }
        $data['data'] = $query->when(filled(request('keyword')), function ($q) {
            $q->where(function ($q) {
                $q->where('name', 'like', '%' . request('keyword') . '%');
                $q->orWhere('phone', 'like', '%' . request('keyword') . '%');
                $q->orWhere('email', 'like', '%' . request('keyword') . '%');
                $q->orWhere('identity', 'like', '%' . request('keyword') . '%');
            });
        })
        ->where('role', 'member')
        ->orderByDesc("created_at")
        ->paginate(50);
        return view("admin::pages.customer.index", $data);
    }

    // Block
    public function block(Request $request)
    {
        Log::warning('User is have to blocked customer.', ['User' => Auth::user()->id, 'Customer ID = ' => $request->id]);

        $item = [
            "status" => $request->status,
        ];
        try {
            $status = $request->status == 2 ? "Blocked successful!" : "Unblock successful!";
            User::where("id", $request->id)->update($item);
            Session::flash("success", $status);
        } catch (\Exception $error) {
            Session::flash('warning', 'Create unsuccess!');
        }
        return redirect()->back();
    }

    // View Details

    public function income(Request $req, $id)
    {
        $data['Status'] = $req->check;
        $data['id'] = $req->id;
        $fromDate = $req->from_date ? $req->from_date : '';
        $toDate = $req->to_date ? $req->to_date : '';
        // $expense = Expense::where('user_id', $req->id)->pluck('id');
        $revenue = Revenue::where('user_id', $req->id)->pluck('id');
        $data['name'] = User::where('id', $req->id)->first()->name ?? '--';

        if ($req->check == "excel_export") {
            $data = $this->queryIncome($revenue, $fromDate, $toDate)->get();
            return Excel::download(new IncomeExport($data), 'income.xlsx');
        } else if ($req->check == "pdf_export") {
            $data['data'] = $this->queryIncome($revenue, $fromDate, $toDate)->get();
        } else if ($req->check !== "pdf_export" || !$req->check) {
            $data['data'] = $this->queryIncome($revenue, $fromDate, $toDate)->paginate(50);
        }
        return view('admin::pages.customer.view_detail.income', $data);
    }

    public function queryIncome($revenue, $fromDate, $toDate)
    {
        return RevenueDetail::whereIn('revenue_id', $revenue)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('expense_date', [$fromDate, $toDate]);
            })
            ->orderByDesc("id");
    }

    public function expense(Request $req, $id)
    {
        $data['Status'] = $req->check;
        $data['id'] = $req->id;

        $fromDate = $req->from_date ? $req->from_date : '';
        $toDate = $req->to_date ? $req->to_date : '';

        $expense = Expense::where('user_id', $req->id)->pluck('id');
        $data['name'] = User::where('id', $req->id)->first()->name ?? '--';

        if ($req->check == "excel_export") {
            $data = $this->queryExpense($expense, $fromDate, $toDate)->get();
            return Excel::download(new IncomeExport($data), 'expense.xlsx');
        } else if ($req->check == "pdf_export") {
            $data['data'] = $this->queryExpense($expense, $fromDate, $toDate)->get();
        } else if ($req->check !== "pdf_export" || !$req->check) {
            $data['data'] = $this->queryExpense($expense, $fromDate, $toDate)->paginate(50);
        }
        return view('admin::pages.customer.view_detail.expense', $data);
    }
    public function queryExpense($expense, $fromDate, $toDate)
    {
        return ExpenseDetail::whereIn('expense_id', $expense)
            ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('expense_date', [$fromDate, $toDate]);
            })
            ->orderByDesc("id");
    }
    public function excel(Request $req)
    {
        $data = RevenueDetail::where('id', $req->id)->first();
        return Excel::download(new IncomeExport($data), 'income.xlsx');
    }

    public function expense_excel(Request $req)
    {
        $data = ExpenseDetail::where('id', $req->id)->first();

        return Excel::download(new IncomeExport($data), 'expense.xlsx');
    }

    public function pdf(Request $req)
    {
        $data = RevenueDetail::where('id', $req->id)->first();
        return response()->json($data);
    }

    public function expense_pdf(Request $req)
    {
        $data = ExpenseDetail::where('id', $req->id)->first();
        return response()->json($data);
    }

    public function onCreate(Request $request)
    {
        $data['data'] = User::where('id', $request->id)->first();
        return view('admin::pages.customer.create', $data);
    }
    public function onChangePassword(Request $request)
    {
        $customer = User::where('id', $request->id)->first();
        if ($customer->role == 'super_admin') {
            return redirect()->route('admin-customer-list', 1);
        }
        return view("admin::pages.customer.change-password", ['data' => $customer]);
    }

    public function onSavePassword(Request $request)
    {
        $item = [
            "password" => bcrypt($request->password),
        ];
        try {
            $user = User::find($request->id);
            $user->update($item);
            $status = "change password success";
            Session::flash("success", $status);
        } catch (Exception $error) {
            Session::flash("warning", "change password unsuccess");
        }
        return redirect()->route("admin-customer-list", 1);
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
    public function onSave(Request $req)
    {
        $id = $req->id;
        $item = [
            "name" => $req->name,
            "email" => $req->email,
            "phone" => $req->phone,
            "dob" => $req->dob,
            "status" => $req->status,
            "role" => "member",
            'image' => $req->image,
            "identity" => $req->identity,
        ];
        // required|numeric
        $req->validate(
            [
                "email" => "required|unique:users,email" . ($id ? ",$id" : ''),
                "phone" => "required|numeric|unique:users,phone" . ($id ? ",$id" : ''),
                "identity" => "required|numeric|unique:users,identity" . ($id ? ",$id" : ''),
            ],
            [
                "email.required" => "email is required",
                "email.unique" => "Email already exists",

                "phone.unique" => "Phone number already exists",
                "phone.required" => "phone is required",
                "phone.numeric" => "phone is invalid format",

                "identity.unique" => "Identity already exists",
                "identity.required" => "identity is required",
                "identity.numeric" => "identity is invalid format",

                "password" => "Your password is too short, Must be 8 or more characters",
            ]

        );
        $status = "Create successful.";
        try {
            if (!$id) {
                $item["role"] = "admin";
                $item["password"] = bcrypt($req->password);
                User::create($item);
            } else {
                User::find($id)->update($item);
                $status = "Update successful.";
            }
            Session::flash("success", $status);
            return redirect()->route("admin-customer-list", 1);
        } catch (Exception $error) {
            Session::flash('warning', 'Create unsuccess!');
            return redirect()->back();
        }
    }
    public function restore($id)
    {
        Log::info("Start: Admin/CustomerController > restore: ".$id);
        try{
            DB::beginTransaction();
            $data = User::withTrashed()->where('id', $id)->first();
            $data->restore();
            DB::commit();
            Session::flash('success', 'Restore success!');
            return redirect()->back();
        }catch (Exception $error) {
            DB::rollback();
            Session::flash('warning', 'Move to restore unsuccess!');
            Log::error("Error: Admin/CustomerController > restore | message: ". $error->getMessage());
            return redirect()->back();
        }
    }
    public function destroy(Request $request)
    {
        Log::info("Start: Admin/CustomerController > destroy: ".$request);
        try{
            $item = User::withTrashed()->where('id', $request->id)->first();
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
            Log::error("Error: Admin/CustomerController > destroy | message: ". $error->getMessage());
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        Log::info("Start: Admin/CustomerController > delete: ".$request);
        try{
            $item = User::where('id', $request->id)->first();
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
            Log::error("Error: Admin/CustomerController > delete | message: ". $error->getMessage());
            return redirect()->back();
        }
    }
}
