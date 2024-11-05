<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\CouponRequest;
use App\Models\Coupon;
use App\Models\GoodCategory;
use Illuminate\Http\Request;

class CouponsController extends Controller
{
    //
    public function index(Request $request)
    {
        // dd($request->all());
        $query = Coupon::where('office_id', config('const.commons.office_id'))
            ->when($request->input('name'), function($query, $search){
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->when($request->input('code'), function($query, $search){
                $query->where('code', 'LIKE', '%' . $search . '%');
            })
            ->when($request->input('good_category_id'), function($query, $search){
                $query->where('good_category_id', $search);
            })
            ->when($request->input('start_date'), function($query, $search){
                $query->whereDate('start_date', '>=', $search);
            })
            ->when($request->input('end_date'), function($query, $search){
                $query->whereDate('end_date', '<=', $search);
            })
            ->whereHas('goodCategory', function ($query) {
                $query->whereNull('deleted_at'); // GoodCategory が削除されていないものに限定
            })
            ;

            if($request->input('limit_flg') !== null) {
                $query->where('limit_flg', $request->input('limit_flg'));
            }
            if($request->input('combination_flg') !== null) {
                $query->where('combination_flg', $request->input('combination_flg'));
            }


            $coupons = $query->orderBy('start_date', 'desc')->get();


        return view('manage.master.coupons', [
            'goodCategories' => GoodCategory::all(),
            'coupons' => $coupons,
        ]);
    }

}
