<?php

namespace App\Http\Controllers\Manage\Master;

use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\ArrivalFlightSearchRequest;
use App\Http\Requests\Manage\Master\ArrivalFlightUploadRequest;
use App\Models\Airport;
use App\Models\AirportTerminal;
use App\Models\ArrivalFlight;
use App\Services\ArrivalFlights\ArrivalFlightImportService;
use App\Services\ArrivalFlights\ArrivalFlightMasterSqlBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ArrivalFlightsController extends Controller
{
    /** アップロードしたCSVの保存先（storage/app/arrival_flights。公開フォルダには置かない） */
    private const STORE_DIR = 'arrival_flights';

    /** 検索で選べる到着空港（この順に表示） */
    private const SEARCH_AIRPORT_CODES = ['NRT', 'HND'];

    /** 並べ替えの列 => ORDER BY に使う式 */
    private const SORT_COLUMNS = [
        'airport' => ['arr.code'],
        'terminal' => ['at.terminal_id'],
        'name' => ['arrival_flights.name'],
        'arrive_date' => ['arrival_flights.arrive_date', 'arrival_flights.arrive_time'],
        'arrive_time' => ['arrival_flights.arrive_time'],
        'dep' => ['dep.code'],
        'airline' => ['al.name'],
        'codeshare' => ['arrival_flights.codeshare'],
    ];

    /**
     * 到着便の一覧・検索
     */
    public function index(ArrivalFlightSearchRequest $request)
    {
        $params = $request->validated();
        // 条件を何も指定していないときは、今日以降の便を表示する
        if (!$request->query()) {
            $params['date_from'] = today()->toDateString();
        }
        // 空港は成田・羽田から選ぶ。指定がない（または対象外の）ときは店舗の空港、それも対象外なら成田
        $airports = Airport::whereIn('code', self::SEARCH_AIRPORT_CODES)->get(['id', 'code', 'name'])
            ->sortBy(fn ($a) => array_search($a->code, self::SEARCH_AIRPORT_CODES))->values();
        $airportIds = $airports->pluck('id')->all();
        if (!in_array((int) ($params['airport_id'] ?? 0), $airportIds, true)) {
            $params['airport_id'] = in_array(myOffice()->airport_id, $airportIds, true)
                ? myOffice()->airport_id
                : ($airportIds[0] ?? myOffice()->airport_id);
        }
        $params['airport_id'] = (int) $params['airport_id'];
        $sort = $params['sort'] ?? 'arrive_date';
        $dir = $params['dir'] ?? 'asc';

        $query = ArrivalFlight::query()
            ->leftJoin('airports as arr', 'arr.id', '=', 'arrival_flights.arr_airport_id')
            ->leftJoin('airports as dep', 'dep.id', '=', 'arrival_flights.dep_airport_id')
            ->leftJoin('airlines as al', 'al.id', '=', 'arrival_flights.airline_id')
            ->leftJoin('airport_terminals as at', 'at.id', '=', 'arrival_flights.terminal_id')
            ->select([
                'arrival_flights.*',
                'arr.code as arr_code', 'arr.name as arr_name',
                'dep.code as dep_code', 'dep.name as dep_name',
                'al.code as airline_code', 'al.name as airline_name',
                'at.terminal_id as terminal_no',
            ])
            ->where('arrival_flights.arr_airport_id', $params['airport_id'])
            ->when($params['terminal'] ?? null, fn ($q, $v) => $q->where('at.terminal_id', $v))
            ->when($params['date_from'] ?? null, fn ($q, $v) => $q->where('arrival_flights.arrive_date', '>=', $v))
            ->when($params['date_to'] ?? null, fn ($q, $v) => $q->where('arrival_flights.arrive_date', '<=', $v));

        // 到着便：航空会社コード・便名（便名の先頭の0は外す。CSV取り込みと同じ）
        $carrier = strtoupper(trim($params['carrier'] ?? ''));
        $flightNo = preg_replace('/^0+(?=\d)/', '', trim($params['flight_no'] ?? ''));
        if ($carrier !== '' && $flightNo !== '') {
            $query->where('arrival_flights.name', $carrier . $flightNo);
        } elseif ($carrier !== '') {
            $query->where('arrival_flights.name', 'like', $carrier . '%');
        } elseif ($flightNo !== '') {
            $query->where('arrival_flights.flight_no', $flightNo);
        }

        // 時刻：指定した時刻以降
        if (isset($params['hours']) || isset($params['minutes'])) {
            $query->where('arrival_flights.arrive_time', '>=', sprintf('%02d:%02d:00', $params['hours'] ?? 0, $params['minutes'] ?? 0));
        }

        // マスタ未設定（航空会社・出発空港・ターミナルのどれかが空）の便だけ
        if (!empty($params['unset_only'])) {
            $query->where(fn ($q) => $q->whereNull('arrival_flights.airline_id')
                ->orWhereNull('arrival_flights.dep_airport_id')
                ->orWhereNull('arrival_flights.terminal_id'));
        }

        foreach (self::SORT_COLUMNS[$sort] as $col) {
            $query->orderBy($col, $dir);
        }
        $query->orderBy('arrival_flights.arrive_date')->orderBy('arrival_flights.arrive_time')
            ->orderBy('arrival_flights.name')->orderBy('arrival_flights.id');

        $flights = $query->paginate($params['limit'] ?? 25)->withQueryString();

        return view('manage.master.arrival_flights', [
            'importResult' => session('arrivalFlightImportResult'),
            'flights' => $flights,
            'params' => $params,
            'sort' => $sort,
            'dir' => $dir,
            'airports' => $airports,
            'terminalNos' => AirportTerminal::where('airport_id', $params['airport_id'])
                ->distinct()->orderBy('terminal_id')->pluck('terminal_id'),
        ]);
    }

    /**
     * Sun_Parking CSV のアップロード。
     * 変換できないもの（マスタ未登録など）があれば取り込まずに一覧を表示する。
     * なければ「あれば更新・なければ挿入」する（削除はしない）。
     */
    public function upload(ArrivalFlightUploadRequest $request, ArrivalFlightImportService $service)
    {
        $file = $request->file('csvFileInput');
        $storedPath = $file->storeAs(self::STORE_DIR, now()->format('Ymd_His') . '_' . $file->getClientOriginalName(), 'local');

        return $this->runImport($request, $service, $storedPath, $file->getClientOriginalName(), false);
    }

    /**
     * 直前にアップロードしたCSVを、マスタにないものは NULL のまま取り込む
     */
    public function forceImport(Request $request, ArrivalFlightImportService $service)
    {
        $pending = $request->session()->get('arrivalFlightPending');
        if (!$pending || !Storage::disk('local')->exists($pending['path'])) {
            return redirect()->route('manage.arrival_flights')
                ->with('failure', '取り込むファイルが見つかりません。もう一度CSVをアップロードしてください。');
        }

        return $this->runImport($request, $service, $pending['path'], $pending['file_name'], true);
    }

    /**
     * 直前の取り込みの確認リストを CSV でダウンロード
     */
    public function downloadIssues(Request $request)
    {
        $result = $request->session()->get('arrivalFlightImportIssues');
        abort_if(!$result, 404, 'ダウンロードできるリストがありません。もう一度取り込んでください。');

        $base = pathinfo($result['file_name'] ?? 'arrival_flights', PATHINFO_FILENAME);

        return response(ArrivalFlightImportService::issuesToCsv($result), 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename*=UTF-8''" . rawurlencode("到着便_要確認リスト_{$base}.csv"),
        ]);
    }

    /**
     * 直前の取り込みで「マスタにない」と判定されたものを登録するSQLをダウンロード
     */
    public function downloadMasterSql(Request $request, ArrivalFlightMasterSqlBuilder $builder)
    {
        $result = $request->session()->get('arrivalFlightImportIssues');
        abort_if(!$result, 404, 'ダウンロードできるSQLがありません。もう一度取り込んでください。');

        $arrCode = Airport::withTrashed()->find(myOffice()->airport_id)?->code ?? 'NRT';
        $sql = $builder->build($result['issues'], $arrCode, $result['file_name'] ?? '', now()->format('Y-m-d H:i'));
        abort_if($sql === null, 404, 'マスタに登録が必要なものはありません。');

        $base = pathinfo($result['file_name'] ?? 'arrival_flights', PATHINFO_FILENAME);

        return response($sql, 200, [
            'Content-Type' => 'application/sql; charset=UTF-8',
            'Content-Disposition' => "attachment; filename*=UTF-8''" . rawurlencode("到着便_マスタ登録_{$base}.sql"),
        ]);
    }

    private function runImport(Request $request, ArrivalFlightImportService $service, string $storedPath, string $fileName, bool $force)
    {
        $back = redirect()->route('manage.arrival_flights');

        try {
            $result = $service->import(Storage::disk('local')->path($storedPath), myOffice()->airport_id, false, $force);
        } catch (RuntimeException $e) {
            return $back->with('failure', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('到着便CSVの取り込みに失敗しました。', ['file' => $fileName, 'exception' => $e]);
            return $back->with('failure', '到着便の取り込み中にエラーが発生しました。データは変更していません。');
        }

        $result['file_name'] = $fileName;
        $result['forced'] = $force;

        // リストのダウンロード用に、次の取り込みまでセッションに残す
        $request->session()->put('arrivalFlightImportIssues', $result);

        if ($result['status'] === 'blocked') {
            // マスタ登録後に同じファイルで取り込めるよう、ファイルの場所を残す
            $request->session()->put('arrivalFlightPending', ['path' => $storedPath, 'file_name' => $fileName]);

            return $back
                ->with('failure', "マスタにない（変換できない）ものが {$result['blocking_count']} 件あるため、取り込みを中止しました。データは変更していません。下の一覧のマスタを登録してから、もう一度取り込んでください。")
                ->with('arrivalFlightImportResult', $result);
        }

        $request->session()->forget('arrivalFlightPending');

        $message = "到着便の取り込みが完了しました（新規 {$result['inserted']} 件・更新 {$result['updated']} 件）。";
        if ($force && $result['blocking_count']) {
            $message .= 'マスタにないものは NULL で登録しています。';
        }

        return $back->with('success', $message)->with('arrivalFlightImportResult', $result);
    }
}
