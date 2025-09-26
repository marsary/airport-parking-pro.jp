<?php
namespace App\Exports;

use App\Models\AgencyRecord;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromGenerator;

class AgencyRecordsGenExport implements FromGenerator
{
    use Exportable;

    /** @var array<int,Collection> */
    protected $agencyRecords;
    /** @var Office[] */
    protected $offices;
    /** @var Carbon */
    protected $targetDate;

    private static $idx;

    public function __construct(array $agencyRecords, Collection $offices, Carbon $targetDate)
    {
        $this->agencyRecords = $agencyRecords;
        $this->offices = $offices;
        $this->targetDate = $targetDate;
    }

    public function generator(): \Generator
    {
        //=============================================================== 1行目
        // ---- ヘッダー行の作成（1行目） ----
        $line = ',,,';
        foreach ($this->offices as $office) {
            $line .= $office->name . ',,,';
        }
        yield explode(',', rtrim($line, ','));

        //=============================================================== 2行目
        // ---- カラム見出し行 ----
        $line = '対象年月,代理店ID,代理店名,';
        foreach ($this->offices as $dummy) {
            $line .= '件数,利用料金,マージン,';
        }
        yield explode(',', rtrim($line, ','));

        foreach ($this->agencyRecords as $agencyId => $agencyRecords) {


            // ---- データ行 ----
            $firstRecord = $agencyRecords->first();
            if (!$firstRecord) {
                continue;
            }

            $row = [
                // 対象年月
                $this->targetDate->format('Ym'),
                // 代理店ID
                $firstRecord->agency_id,
                // 代理店名
                $firstRecord->agency_name,
            ];

            $recordsByOffice = $agencyRecords->keyBy('office_id');

            foreach ($this->offices as $office) {
                $record = $recordsByOffice->get($office->id);
                /** @var AgencyRecord $record */
                if ($record) {
                    $row[] = $record->record_count;
                    $row[] = $record->price_parking;
                    $row[] = $record->margin;
                } else {
                    $row[] = '';
                    $row[] = '';
                    $row[] = '';
                }
            }
            yield $row;
        }
    }
}
