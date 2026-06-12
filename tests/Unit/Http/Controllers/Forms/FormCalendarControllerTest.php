<?php

namespace Tests\Unit\Http\Controllers\Forms;

use App\Http\Controllers\Forms\FormCalendarController;
use Carbon\Carbon;
use Tests\TestCase;

class FormCalendarControllerTest extends TestCase
{

    protected function tearDown(): void
    {
        Carbon::setTestNow(); // テスト終了後は現在時刻設定をリセット
        parent::tearDown();
    }

    /**
     * getMinDate メソッドのテスト
     *
     * @dataProvider minDateDataProvider
     */
    public function testGetMinDate($now, $expected)
    {
        // 現在時刻を固定
        Carbon::setTestNow(Carbon::parse($now));

        $controller = new FormCalendarController();
        $result = $controller->getMinDate();

        $this->assertEquals($expected, $result->format('Y-m-d'), "現在時刻が {$now} の時、期待値は {$expected} です。");
    }

    /**
     * パターン網羅データプロバイダ
     */
    public static function minDateDataProvider()
    {
        return [
            // 1. システム開始日（2026-07-01）よりずっと前のケース
            'Before start date (morning)' => [
                'now' => '2026-01-01 10:00:00',
                'expected' => '2026-07-01'
            ],
            'Before start date (night)' => [
                'now' => '2026-01-01 23:30:00',
                'expected' => '2026-07-01'
            ],
            // 2. システム開始前日の境界値
            'Day before start date (morning)' => [
                'now' => '2026-06-30 10:00:00',
                'expected' => '2026-07-01'
            ],
            'Day before start date (at 23:00)' => [
                'now' => '2026-06-30 23:00:00',
                'expected' => '2026-07-02' // 明後日制限が優先される
            ],
            // 3. 通常運用期間（2026-07-01以降）
            'Normal period (morning)' => [
                'now' => '2026-08-01 10:00:00',
                'expected' => '2026-08-02' // 翌日
            ],
            'Normal period (exactly 23:00)' => [
                'now' => '2026-08-01 23:00:00',
                'expected' => '2026-08-03' // 明後日
            ],
            'Normal period (night)' => [
                'now' => '2026-08-01 23:30:00',
                'expected' => '2026-08-03' // 明後日
            ],
        ];
    }

}
