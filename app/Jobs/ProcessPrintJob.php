<?php

namespace App\Jobs;

use App\Services\Printers\AbstractPrintable;
use App\Services\Printers\PrinterService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPrintJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $printerConfig;
    protected AbstractPrintable $printable;
    protected string $library;

    /**
     * @param array $printerConfig プリンタ接続情報
     * @param AbstractPrintable $printable 印刷物クラス
     */
    public function __construct(array $printerConfig, AbstractPrintable $printable, string $library = 'mpdf')
    {
        $this->printerConfig = $printerConfig;
        $this->printable = $printable;
        $this->library = $library;
    }

    /**
     * ジョブを実行
     */
    public function handle(): void
    {
        try {
            $printerService = new PrinterService($this->printerConfig, $this->library);
            $printerService->print($this->printable);
        } catch (\Throwable $e) {
            Log::error("印刷ジョブ実行エラー: " . $e->getMessage(), [
                'config' => $this->printerConfig,
                'printable_class' => get_class($this->printable)
            ]);
            throw $e;
        }
    }
}
