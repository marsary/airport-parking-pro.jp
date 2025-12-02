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

        // return view('manage.test.label', $data);

        $html = view('manage.test.label', $data)->render();

        $mpdf = new Mpdf();

        $mpdf->WriteHTML($html);

        $storagePath = storage_path('app/print');

        $filename = 'print.pdf';
        $filePath = $storagePath . '/' . $filename;

        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

        $this->executePrintCommand($filePath);

        return view('manage.test.label', $data);
    }


    private function executePrintCommand(string $pdfPath)
    {
        $printerName = 'NEC MultiCoder 500L3';
        $driverName = 'NEC MultiCoder 500L3';
        $portName = 'USB001';
        $acroReadPath = 'C:\Program Files\Adobe\Acrobat DC\Acrobat\Acrobat.exe';

        $command = sprintf(
            '"%s" /h /t "%s" "%s" "%s" "%s"',
            $acroReadPath,
            $pdfPath,
            $printerName,
            $driverName,
            $portName
        );

        shell_exec($command);
    }
}
