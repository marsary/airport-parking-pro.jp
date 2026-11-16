<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\LegacyCompareRequest;
use App\Services\ArrivalFlights\Legacy\LegacyCompareService;
use App\Services\ArrivalFlights\Legacy\LegacyCsvReader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * 到着便マスタ：現行システムのデータ（flt_corp_tbl / flt_dpt_tbl / flt_corp_term_tbl / flt_tbl）と比較し、
 * 修正・新規作成の SQL を作る。DBは変更しない。
 */
class LegacyCompareController extends Controller
{
    public function index(Request $request)
    {
        return view('manage.master.arrival_flights_legacy', [
            'result' => $request->session()->get('legacyCompareResult'),
            'files' => LegacyCsvReader::FILES,
        ]);
    }

    public function compare(LegacyCompareRequest $request, LegacyCompareService $service)
    {
        $paths = [];
        $names = [];
        foreach (array_keys(LegacyCsvReader::FILES) as $kind) {
            $file = $request->file($kind);
            $paths[$kind] = $file->getRealPath();
            $names[$kind] = $file->getClientOriginalName();
        }

        try {
            $result = $service->run($paths, $names, myOffice()->airport_id);
        } catch (RuntimeException $e) {
            return redirect()->route('manage.master.arrival_flights.legacy')->with('failure', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('現行データとの比較に失敗しました。', ['files' => $names, 'exception' => $e]);
            return redirect()->route('manage.master.arrival_flights.legacy')->with('failure', '現行データとの比較中にエラーが発生しました。');
        }

        $request->session()->put('legacyCompareResult', $result);

        return redirect()->route('manage.master.arrival_flights.legacy')->with('success', $result['has_changes']
            ? '比較が終わりました。下の内容を確認し、SQLをダウンロードして実行してください（データはまだ変更していません）。'
            : '比較が終わりました。現行データとの差異はありませんでした。');
    }

    public function downloadSql(Request $request)
    {
        $result = $request->session()->get('legacyCompareResult');
        $path = $result['sql_path'] ?? null;
        abort_if(!$path || !Storage::disk('local')->exists($path), 404, 'ダウンロードできるSQLがありません。もう一度比較してください。');

        return Storage::disk('local')->download($path, basename($path), ['Content-Type' => 'application/sql; charset=UTF-8']);
    }
}
