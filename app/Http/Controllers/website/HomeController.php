<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $layout = 'website::pages.home.';
    public function index(Request $req)
    {
        $data['data'] = Booking::limit(100)->get();
        return view($this->layout . 'index', $data);
    }
}
