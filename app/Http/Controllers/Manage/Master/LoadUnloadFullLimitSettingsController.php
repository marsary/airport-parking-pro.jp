<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use Illuminate\Http\Request;

class LoadUnloadFullLimitSettingsController extends Controller
{
    //
    public function index()
    {
        return view('manage.master.load_unload_full_limit_settings', [
        ]);
    }


    public function store()
    {
        return redirect()->back();
    }


    public function update()
    {
        // 動的なレコードIDに基づいた入力データの取得

        return redirect()->back();
    }


    public function destroy($id)
    {

        return redirect()->back();
    }

}
