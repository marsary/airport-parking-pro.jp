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


    public function store(CouponRequest $request)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_0";
        $start_date = date('Y-M-d H:i', strtotime($request->{$recordKey}['start_date'] . ' ' . $request->{$recordKey}['start_time']));
        $end_date = date('Y-M-d H:i', strtotime($request->{$recordKey}['end_date'] . ' ' . $request->{$recordKey}['end_time']));

        $coupon = Coupon::create([
            'office_id' => config('const.commons.office_id'),
            'name' => $request->{$recordKey}['name'],
            'code' => $request->{$recordKey}['code'],
            'discount_amount' => $request->{$recordKey}['discount_amount'],
            'discount_type' => $request->{$recordKey}['discount_type'],
            'good_category_id' => $request->{$recordKey}['good_category_id'],
            'limit_flg' => $request->{$recordKey}['limit_flg'],
            'combination_flg' => $request->{$recordKey}['combination_flg'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'memo' => $request->{$recordKey}['memo'],
        ]);

        return redirect()->back();
    }


    public function update(CouponRequest $request, $id)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_" . $request->route()->parameter('coupon', 0);
        $start_date = date('Y-M-d H:i', strtotime($request->{$recordKey}['start_date'] . ' ' . $request->{$recordKey}['start_time']));
        $end_date = date('Y-M-d H:i', strtotime($request->{$recordKey}['end_date'] . ' ' . $request->{$recordKey}['end_time']));

        $coupon = Coupon::findOrFail($id);
        $coupon->fill([
            'name' => $request->{$recordKey}['name'],
            'code' => $request->{$recordKey}['code'],
            'discount_amount' => $request->{$recordKey}['discount_amount'],
            'discount_type' => $request->{$recordKey}['discount_type'],
            'good_category_id' => $request->{$recordKey}['good_category_id'],
            'limit_flg' => $request->{$recordKey}['limit_flg'],
            'combination_flg' => $request->{$recordKey}['combination_flg'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'memo' => $request->{$recordKey}['memo'],
        ])->save();

        return redirect()->back();
    }


    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->back();
    }
}
