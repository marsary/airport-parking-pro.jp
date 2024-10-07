<?php

namespace App\Http\Controllers\Manage\Settings;

use App\Http\Controllers\Manage\Controller;
use Illuminate\Http\Request;

class MonthlySalesTargetsController extends Controller
{
    //
    public function index()
    {
        return view('manage.settings.monthly_sales_targets');
    }
}
