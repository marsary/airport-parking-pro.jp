<?php

namespace App\Http\Controllers\Manage\Settings;

use App\Http\Controllers\Manage\Controller;
use Illuminate\Http\Request;

class DailyUpdateController extends Controller
{
    //
    public function index()
    {
        return view('manage.settings.daily_update');
    }
}
