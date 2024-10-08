<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
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
}
