<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    //
    public function index()
    {
        return view('manage.master.goods');
    }
}