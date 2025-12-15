<?php

namespace App\Services\Printers;

use App\Models\Deal;

// ラベル用
class LabelPrintable extends AbstractPrintable
{
    public $deal;
    protected array $defaultConfig = [
        // 'format' => [80, 48], // [幅mm, 高さmm] 例：48mm x 80mmラベル
        'width' => '80mm',
        'height' => '40mm',
        'margin' => [
            'top' => '2mm',
            'right' => '0',
            'bottom' => '0',
            'left' => '6mm',
        ],
        'scale' => 0.8,
    ];


    public function __construct(Deal $deal, array $config = [])
    {
        $this->deal = $deal;
        // 外部から設定されたフォーマットとマージンを組み合わせる
        $config = array_merge($this->defaultConfig, $config);

        parent::__construct($config, 'manage.test.label');
    }

    public function setConfig($config)
    {
        // 外部から設定されたフォーマットとマージンを組み合わせる
        $config = array_merge($this->defaultConfig, $config);

        parent::setConfig($config);
    }

    // ラベル印刷に必要なデータ取得ロジック
    public function getData(): array
    {
        return [
            // 受付番号
            'receipt_code' => $this->deal->receipt_code,
            // 受付者名
            'member_name' => $this->deal->kana,
            // 到着日（到着便マスタから
            'arrive_date' => $this->deal->arrivalFlight?->arrive_date?->format('Y/m/d'),
            // 到着時間（到着便マスタから
            'arrive_time' => $this->deal->arrivalFlight?->arrive_time? \Carbon\Carbon::parse($this->deal->arrivalFlight->arrive_time)->format('H:i') : '',
            // 到着便名（到着便マスタから）
            'arrival_flight_name' => $this->deal->arrivalFlight?->name,
            // 出発空港コード
            'dep_airport_code' => $this->deal->arrivalFlight?->depAirport?->code,
            // 車両情報
            'car_number' => $this->deal->memberCar?->number,
            'car_name' => $this->deal->memberCar?->car?->name,
            'car_color_name' => $this->deal->memberCar?->carColor?->name,
            // 現在時刻
            'print_date' => now()->format('Y/m/d H:i'),
        ];
    }
}

