<?php
namespace App\Services\Printers;

use \Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Spiritix\Html2Pdf\Converter;
use Spiritix\Html2Pdf\Input\StringInput;
use Spiritix\Html2Pdf\Output\FileOutput;

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

    private function savePdfWithPupeteer(AbstractPrintable $printable,string $html,string $filePath)
    {
        $input = new StringInput();
        $input->setHtml($html);

        $converter = new Converter($input, new FileOutput());

        $converter->setOptions($printable->getConfig());
        /** @var FileOutput $output */
        $output = $converter->convert();
        $output->store($filePath);
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
        // SumatraPDF
        $command = sprintf(
            'cmd /c ""%s" -print-to "%s" "%s""',
            $this->pdfReaderPath,
            $this->printerName,
            $pdfPath
        );

        // 印刷設定を指定する場合
        // -print-settings "paper=Custom.48x80mm,orientation=portrait,fit"

        // | 設定                      | 説明      |
        // | ----------------------- | ------- |
        // | `fit`                   | 用紙にフィット |
        // | `noscale`               | 等倍      |
        // | `orientation=portrait`  | 縦       |
        // | `orientation=landscape` | 横       |
        // | `paper=A4`              | A4      |
        // | `paper=Custom.48x80mm`  | 感熱ラベル   |

        shell_exec($command);
    }
}
