<?php

namespace App\Http\Controllers\Manage\Master;

use App\Enums\RegiDisplayFlag;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\GoodCategoryRequest;
use App\Models\GoodCategory;
use Illuminate\Http\Request;

class GoodCategoriesController extends Controller
{
    //
    public function index(Request $request)
    {
        $goodCategories = GoodCategory::where('office_id', config('const.commons.office_id'))
            ->when($request->input('name'), function($query, $search){
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->when($request->input('type'), function($query, $search){
                $query->where('type', $search);
            })->orderBy('name')->get();


        return view('manage.master.good_categories', [
            'goodCategories' => $goodCategories,
        ]);
    }


    public function store(GoodCategoryRequest $request)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_0";
        $goodCategory = GoodCategory::create([
            'office_id' => config('const.commons.office_id'),
            'name' => $request->{$recordKey}['name'],
            'type' => $request->{$recordKey}['type'],
            'regi_display_flag' => RegiDisplayFlag::RESERVE_ONLY->value,
            'memo' => $request->{$recordKey}['memo'],
        ]);

        return redirect()->back();
    }


    public function update(GoodCategoryRequest $request, $id)
    {
        // 動的なレコードIDに基づいた入力データの取得
        $recordKey = "record_" . $request->route()->parameter('good_category', 0);
        $goodCategory = GoodCategory::findOrFail($id);
        $goodCategory->fill([
            'name' => $request->{$recordKey}['name'],
            'type' => $request->{$recordKey}['type'],
            'memo' => $request->{$recordKey}['memo'],
        ])->save();

        return redirect()->back();
    }


    public function destroy(Request $request, $id)
    {
        $goodCategory = GoodCategory::findOrFail($id);
        $goodCategory->delete();

        return redirect()->back();
    }
}
