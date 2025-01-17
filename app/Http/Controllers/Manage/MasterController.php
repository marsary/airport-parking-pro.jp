<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Manage\Controller;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    //
    public function index()
    {
        return view('manage.master.index');
    }
}
