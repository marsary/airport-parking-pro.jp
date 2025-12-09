<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Manage\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\File;

class TopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manage.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function marketing()
    {
        return view('manage.marketing.index');
    }

    public function label()
    {
        $data = $this->getPrintData();

        return view('manage.test.label', $data);
    }

    public function print()
    {
        $data = $this->getPrintData();

        // Blade を HTML に変換
        $html = view('manage.test.label', $data)->render();

        // mPDF インスタンス作成
        $mpdf = new Mpdf([
            'format' => [80, 48], // [幅mm, 高さmm] 例：48mm x 80mmラベル
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        // HTML → PDF
        $mpdf->WriteHTML($html);

        // 保存先
        $storagePath = storage_path('app/print');
        if (!File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        $filename = 'print_' . time() . '.pdf';
        $filePath = $storagePath . '/' . $filename;
        // 保存
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

        // 自動印刷コマンドの実行
        $this->executePrintCommand($filePath);

        return redirect()->route("manage.label");
    }

    private function getPrintData()
    {
        $deal = Deal::first();

        $data = [
            // 受付番号
            'receipt_code' => $deal->receipt_code,
            // 受付者名
            'member_name' => $deal->kana,
            // 到着日（到着便マスタから
            'arrive_date' => $deal->arrivalFlight?->arrive_date?->format('Y/m/d'),
            // 到着時間（到着便マスタから
            'arrive_time' => $deal->arrivalFlight?->arrive_time? \Carbon\Carbon::parse($deal->arrivalFlight->arrive_time)->format('H:i') : '',
            // 到着便名（到着便マスタから）
            'arrival_flight_name' => $deal->arrivalFlight?->name,
            // 出発空港コード
            'dep_airport_code' => $deal->arrivalFlight?->depAirport?->code,
            // 車両情報
            'car_number' => $deal->memberCar?->number,
            'car_name' => $deal->memberCar?->car?->name,
            'car_color_name' => $deal->memberCar?->carColor?->name,
            // 現在時刻
            'print_date' => now()->format('Y/m/d H:i'),
        ];
        return $data;
    }

    private function executePrintCommand(string $pdfPath)
    {
        // --- 環境に合わせて以下の値を設定 ---
        $printerName = config("services.printers.label_printer.printerName"); // Windowsの「デバイスとプリンター」で設定したプリンター名
        $driverName = config("services.printers.label_printer.driverName"); // プリンタードライバ名 (通常はプリンター名と同じ)
        $portName = config("services.printers.label_printer.portName");             // プリンターのポート名（環境によって異なる）
        $acroReadPath = config("services.printers.label_printer.acroReadPath"); // Adobe Readerのフルパス

        // ------------------------------------------------
        // 印刷コマンドの組み立て
        $command = sprintf(
            '"%s" /h /t "%s" "%s" "%s" "%s"',
            $acroReadPath,
            $pdfPath,
            $printerName,
            $driverName,
            $portName
        );

        // コマンド実行
        shell_exec($command);

        // 印刷コマンド実行後、すぐにPDFファイルを削除すると、印刷処理が間に合わないため
        // 削除は別の仕組み（CleanupOldPdfs を console.php で定期実行）で行う
    }
}
