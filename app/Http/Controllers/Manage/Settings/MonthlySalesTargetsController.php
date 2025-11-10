<?php

namespace App\Http\Controllers\Manage\Settings;

use App\Exports\MonthlySalesTargetsExport;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\MonthlySalesTargetUploadRequest;
use App\Imports\MonthlySalesTargetImport;
use Illuminate\Http\Request;

class MonthlySalesTargetsController extends Controller
{
    //
    public function index()
    {
        $today = \Carbon\Carbon::today();
        // 当年及び過去5年、未来2年
        $yearList = range($today->year - 5, $today->year + 2);
        return view('manage.settings.monthly_sales_targets', [
            'yearList' => $yearList,
        ]);
    }

    public function upload(MonthlySalesTargetUploadRequest $request)
    {
        (new MonthlySalesTargetImport)->import($request->file('csvFileInput'), null, \Maatwebsite\Excel\Excel::CSV);

        return redirect()->back()->with('success', '月次売上目標のインポートが完了しました。');
    }


    public function loadTables(Request $request)
    {
        $year = (int) $request->input('year', \Carbon\Carbon::today()->year);
        $service = new \App\Services\Settings\MonthlySalesTargetResultsService($year);
        $tableData = $service->generateData();
        // dd($tableData);
        return response()->json($tableData);
    }


    public function download(Request $request)
    {
        $year = (int) $request->input('year', \Carbon\Carbon::today()->year);
        $service = new \App\Services\Settings\MonthlySalesTargetResultsService($year);
        $csvData = $service->generateCsvData();


        /** @var MonthlySalesTargetsExport $export */
        $export = new MonthlySalesTargetsExport($csvData);

        $fileName = "月次売上目標_{$year}.csv";
        return $export->download($fileName, \Maatwebsite\Excel\Excel::CSV);
    }

}
