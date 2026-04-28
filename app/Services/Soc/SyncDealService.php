<?php
namespace App\Services\Soc;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncDealService
{
    protected array $syncDealRecords = [];
    protected const AG_ID1 = 6033;
    protected const AG_ID2 = 71;

    public function setDeals(Collection $deals): self
    {
        $this->syncDealRecords = []; // 複数回呼ばれる可能性を考慮してリセット
        foreach ($deals as $deal) {
            $this->syncDealRecords[] = $deal;
        }
        return $this;
    }

    public function getSortedDataForSync()
    {
        $syncData = [];
        foreach ($this->syncDealRecords as $syncDealRecord) {
            try {
                DB::transaction(function () use($syncDealRecord, &$syncData) {

                });

            } catch (\Throwable $th) {
                Log::error('エラー内容：' . $th->getMessage());
            }
        }

        return $syncData;
    }


}

