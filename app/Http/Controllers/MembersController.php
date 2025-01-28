<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\TagMember;
use App\Services\LabelTagManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembersController extends Controller
{
    public function loadMember(Request $request)
    {
        $kana = $request->input('kana');
        $tel = $request->input('tel');

        $member= Member::with(['memberCars' => function ($query) {
            $query->where('default_flg', true)->with('car');
        }])->where('kana', $kana)->where('tel', $tel)->first();

        if($member) {
            LabelTagManager::attachTagDataToMember($member);
            $member->car_caution_ids = [];
            if(isset($member->memberCars[0])) {
                $member->car_caution_ids = DB::table('car_cautions')
                    ->leftJoin('car_caution_member_cars', 'car_cautions.id', '=', 'car_caution_member_cars.car_caution_id')
                    ->where('car_caution_member_cars.member_car_id', $member->memberCars[0]->id)
                    ->pluck('car_cautions.id')->toArray();
            }

        }
        return response()->json([
            'success' => true,
            'data' => ['member' => $member],
         ]);
    }

    public function complete()
    {
        return view('member.members.complete');
    }
}
