<?php

namespace App\Http\Controllers\Manage\Master;

use App\Enums\RegiDisplayFlag;
use App\Enums\TaxType;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\GoodRequest;
use App\Models\Good;
use App\Models\GoodCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
    public function index(Request $request)
    {
        $goods = Good::where('office_id', config('const.commons.office_id'))
            ->when($request->input('name'), function($query, $search){
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->when($request->input('good_category_id'), function($query, $search){
                $query->where('good_category_id', $search);
            })->orderBy('name')->get();


        return view('manage.master.goods', [
            'goodCategories' => GoodCategory::all(),
            'goods' => $goods,
        ]);
    }


    public function store(GoodRequest $request)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_0";
        $good = Good::create([
            'office_id' => config('const.commons.office_id'),
            'good_category_id' => $request->{$recordKey}['good_category_id'],
            'name' => $request->{$recordKey}['name'],
            'abbreviation' => $request->{$recordKey}['abbreviation'],
            'price' => $request->{$recordKey}['price'],
            'tax_type' => $request->{$recordKey}['tax_type'],
            'start_date' => Carbon::today(),
            'end_date' => maxDateTime(),
            'regi_display_flag' => RegiDisplayFlag::RESERVE_ONLY->value,
            'memo' => $request->{$recordKey}['memo'],
        ]);

        return redirect()->back();
    }


    public function update(GoodRequest $request, $id)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_" . $request->route()->parameter('good', 0);
        $good = Good::findOrFail($id);
        $good->fill([
            'good_category_id' => $request->{$recordKey}['good_category_id'],
            'name' => $request->{$recordKey}['name'],
            'abbreviation' => $request->{$recordKey}['abbreviation'],
            'price' => $request->{$recordKey}['price'],
            'tax_type' => $request->{$recordKey}['tax_type'],
            'memo' => $request->{$recordKey}['memo'],
        ])->save();

        return redirect()->back();
    }


    public function destroy(Request $request, $id)
    {
        $good = Good::findOrFail($id);
        $good->delete();

        return redirect()->back();
    }
}
