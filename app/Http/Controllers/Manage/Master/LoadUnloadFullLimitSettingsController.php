<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class LoadUnloadFullLimitSettingsController extends Controller
{
    public function index()
    {
        // サンプルデータを生成
        return view('manage.master.load_unload_full_limit_settings');
    }
}
