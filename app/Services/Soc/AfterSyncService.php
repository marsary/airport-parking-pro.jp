<?php
namespace App\Services\Soc;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AfterSyncService
{
    public $syncCount;
    public $results = [];

    function __construct()
    {
        $this->syncCount = 0;
    }

    public function updateAfterSync(array $dealData)
    {
        foreach($dealData as $row) {
            try {
                $service = $this;
                DB::transaction(function () use ($row, $service) {


                });

            } catch (\Exception $e) {
                Log::error('エラー内容：' . $e->getMessage());
            }

        }
    }
}
