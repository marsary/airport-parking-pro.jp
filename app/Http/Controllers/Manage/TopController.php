<?php

namespace App\Http\Controllers\Manage;

use App\Exceptions\PrinterPrintException;
use App\Http\Controllers\Manage\Controller;
use App\Jobs\ProcessPrintJob;
use App\Models\Deal;
use App\Services\Printers\LabelPrintable;
use Illuminate\Http\Request;

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
        // 印刷物（Printable）のインスタンスを作成
        $labelPrintable = new LabelPrintable($deal);

        $data = $labelPrintable->getData();

        return view('manage.test.label', $data);
    }

    public function print()
    {
        try {
            // プリンタ接続情報を設定
            // 領収書とラベル、2つの設定を用意
            // $labelPrinterConfig = config("services.printers.label_printer"); // ラベルプリンタ
            $labelPrinterConfig = config("services.printers.receipt_printer"); // debug用

            $deal = Deal::first();

            // 印刷物（Printable）のインスタンスを作成
            $labelPrintable = new LabelPrintable($deal);
            // 印刷処理をキューに投入
            ProcessPrintJob::dispatch($labelPrinterConfig, $labelPrintable);

        } catch (\Throwable $th) {
            throw new PrinterPrintException();
        }

        return redirect()->route("manage.label");
    }
}
