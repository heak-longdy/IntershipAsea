<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\WalletHistory;
use App\Models\Shop;

class WalletHistoryController extends Controller
{
    protected $layout = 'admin::pages.wallet.';
    function __construct()
    {
        $this->middleware('permission:wallet-view', ['only' => ['index']]);
        $this->middleware('permission:wallet-create', ['only' => ['onCreate', 'onSave']]);
        $this->middleware('permission:wallet-update', ['only' => ['onEdit', 'onSave', 'onUpdateStatus', 'restore']]);
        $this->middleware('permission:wallet-delete', ['only' => ['delete', 'restore', 'destroy']]);
    }
    public function index(Request $req)
    {
        $data['status'] = $req->status;

        if (!$req->status) {
            return redirect()->route('admin-wallet-list', 1);
        }
        $query = WalletHistory::orderBy('id', 'desc');
        $data['data'] = $query->paginate(50);

        return view($this->layout . 'index', $data);
    }
}
