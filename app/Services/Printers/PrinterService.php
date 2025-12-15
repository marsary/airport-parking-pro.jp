<?php
namespace App\Services\Printers;

use \Illuminate\Support\Facades\File;
use Mpdf\Mpdf;

class PrinterService
{
    // プリンタ接続情報
    protected string $printerName;
    protected string $driverName;
    protected string $portName;
    protected string $pdfReaderPath;

    public function __construct(array $connectionConfig)
    {
        // プリンタ接続情報を外部から設定
        $this->printerName = $connectionConfig['printerName'];
        $this->driverName = $connectionConfig['driverName'];
        $this->portName = $connectionConfig['portName'];
        $this->pdfReaderPath = $connectionConfig['pdfReaderPath'];
    }

    /**
     * 印刷物をPDF化し、自動印刷コマンドを実行
     */
    public function print(AbstractPrintable $printable)
    {
        // ファイルパスの準備と保存
        $storagePath = storage_path('app/print');
        if (!File::isDirectory($storagePath)) {
             File::makeDirectory($storagePath, 0755, true);
        }

        $filename = 'print_' . time() . '_' . uniqid() . '.pdf';
        $filePath = $storagePath . '/' . $filename;

        // // HTML → PDF
        $html = $printable->render();

        $this->savePdfWithMPdf($printable, $html, $filePath);

        // 自動印刷コマンドの実行
        $this->executePrintCommand($filePath);

        return $filePath;
    }

    private function savePdfWithMPdf(AbstractPrintable $printable,string $html,string $filePath)
    {
        $printable->setConfig([
            'format' => [80, 48], // [幅mm, 高さmm] 例：48mm x 80mmラベル
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);
        // 1. Mpdfインスタンス作成
        $mpdf = new Mpdf($printable->getConfig());

        // 2. HTML → PDF
        $html = $printable->render();

        $mpdf->WriteHTML($html);

        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);
    }

    private function executePrintCommand(string $pdfPath)
    {
        // 印刷コマンドの組み立てと実行
        $command = sprintf(
            '"%s" /h /t "%s" "%s" "%s" "%s"',
            $this->pdfReaderPath,
            $pdfPath,
            $this->printerName,
            $this->driverName,
            $this->portName
        );

        shell_exec($command);
    }
}
