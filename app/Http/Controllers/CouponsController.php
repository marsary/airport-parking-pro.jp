<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Deal;
use App\Models\DealGood;
use App\Models\Good;
use App\Models\GoodCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function couponsForRegister(Request $request)
    {
        $today = Carbon::today();
        $query = Coupon::whereDate('start_date','<=', $today->toDateString())
            ->whereDate('end_date','>', $today->toDateString())
            ->where('office_id', config('const.commons.office_id'))
            ;

        if($request->has('deal_id')) {
            $dealId = $request->deal_id;
            $goodCategoryIds = Good::whereHas('dealGoods', function ($q) use($dealId){
                $q->where('deal_id', $dealId);
            })->pluck('good_category_id')->toArray();

            $query = $query->where(function($q) use($goodCategoryIds) {
                $q->whereIn('good_category_id', $goodCategoryIds)
                    ->orWhereNull('good_category_id');
            });
        }

        return response()->json([
            'success' => true,
            'data' => [
                'coupons' => $query->get()
            ],
         ]);
    }
}
