<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\QueryService;
use App\Models\RevenueDetail;
use App\Models\ExpenseDetail;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:dashboard-view', ['only' => ['index']]);
    }
    public function index()
    {
        $data['totalRevenueUsd'] = 23;
        $data['totalRevenueKhr'] =  23;
        $data['totalRevenueThb'] = 23;

        $data['totalExpenseUsd'] =  23;
        $data['totalExpenseKhr'] =  23;
        $data['totalExpenseThb'] =  23;
        
        return view('admin::pages.dashboard')->with($data);
    }
}
