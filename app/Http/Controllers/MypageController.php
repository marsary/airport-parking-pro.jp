<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MypageController extends Controller
{
    //
    public function reserveInfo()
    {
        return view('mypage.reserve_info');
    }
    public function reserveInfoDetail()
    {
        return view('mypage.reserve_info_detail');
    }
    public function memberInfoDetail()
    {
        return view('mypage.member_info_detail');
    }
    public function memberInfoChange()
    {
        return view('mypage.member_info_change');
    }
    public function memberCarsChange()
    {
        return view('mypage.member_cars_change');
    }
    public function change()
    {
        return view('mypage.change');
    }
    public function reserveCancel()
    {
        return view('mypage.reserve_cancel');
    }
    public function resetPassword()
    {
        return view('mypage.reset-password');
    }
    public function resetPasswordComplete()
    {
        return view('mypage.reset-password_complete');
    }
}
