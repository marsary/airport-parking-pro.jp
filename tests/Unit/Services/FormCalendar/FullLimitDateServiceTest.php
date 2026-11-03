<?php

namespace Tests\Unit\Services\FormCalendar;

use App\Enums\LimitOverStatus;
use App\Models\FullLimitSetting;
use App\Models\Office;
use App\Services\FormCalendar\FullLimitDateService;
use Carbon\Carbon;
use Database\Seeders\AirportSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FullLimitDateServiceTest extends TestCase
{
    use RefreshDatabase;

    private int $officeId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AirportSeeder::class);
        $office = Office::factory()->create([
            'airport_id' => 1,
        ]);
        $this->officeId = $office->id;
        config(['const.commons.office_id' => $this->officeId]);
    }

    /**
     * can_reserve.test.js と同じ19パターンで予約可否を確認する。
     * PHP側は設定レコードがない日を「制限なし」として扱うため、
     * JS側の「カレンダーデータ欠損」3ケースは、設定なしの日の許可動作を確認する。
     *
     * @dataProvider canReserveCases
     */
    public function test_can_reserve_cases(
        string $startDate,
        string $endDate,
        array $overrides,
        bool $expectedResult,
        string $expectedMessage = '',
        array $missingDates = []
    ): void {
        $this->createLimitSettings($startDate, $endDate, $overrides, $missingDates);

        $service = new FullLimitDateService(
            Carbon::parse($startDate),
            Carbon::parse($endDate)
        );

        $result = $service->canReserve();

        $this->assertSame($expectedResult, $result->result);
        $this->assertSame($expectedMessage, $result->message);
    }

    /**
     * @return array<string, array<mixed>>
     */
    public static function canReserveCases(): array
    {
        return [
            // 同日予約、設定日の前後、またぎ不可日の前後関係。
            '1日予約' => ['2025-11-10', '2025-11-10', [], true, ''],
            '設定日の前日から当日まで' => ['2025-11-09', '2025-11-10', [], true, ''],
            '設定日をまたぐ' => ['2025-11-09', '2025-11-11', ['2025-11-10' => ['canCrossTime' => false]], false, '満車のため、2025/11/10をまたぐ予約はできません。'],
            '設定日の当日から翌日まで' => ['2025-11-10', '2025-11-11', ['2025-11-10' => ['canCrossTime' => false]], false, '満車のため、2025/11/10をまたぐ予約はできません。'],
            '設定日の翌日以降' => ['2025-11-11', '2025-11-12', [], true, ''],
            '連続設定日の前に出庫' => ['2025-10-20', '2025-10-22', [], true, ''],
            '連続設定日をまたぐ' => ['2025-10-22', '2025-10-25', ['2025-10-23' => ['canCrossTime' => false], '2025-10-24' => ['canCrossTime' => false]], false, '満車のため、2025/10/23, 2025/10/24をまたぐ予約はできません。'],
            '連続設定の初日から出庫' => ['2025-10-22', '2025-10-23', ['2025-10-23' => ['canCrossTime' => false], '2025-10-24' => ['canCrossTime' => false]], true, ''],
            '連続設定の初日から最終日まで' => ['2025-10-23', '2025-10-24', ['2025-10-23' => ['canCrossTime' => false], '2025-10-24' => ['canCrossTime' => false]], false, '満車のため、2025/10/23をまたぐ予約はできません。'],
            '連続設定期間の終了後' => ['2025-10-25', '2025-10-27', [], true, ''],
            '離れた設定日の間をまたぐ' => ['2025-10-23', '2025-10-26', ['2025-10-25' => ['canCrossTime' => false]], false, '満車のため、2025/10/25をまたぐ予約はできません。'],
            '離れた設定日の前日まで' => ['2025-10-20', '2025-10-22', [], true, ''],
            '離れた設定日の翌日以降' => ['2025-10-26', '2025-10-27', [], true, ''],

            // 入庫、出庫、滞在中それぞれの制限。
            '入庫不可' => ['2025-11-10', '2025-11-10', ['2025-11-10' => ['canCheckIn' => false]], false, '2025/11/10は入庫できません。'],
            '出庫不可' => ['2025-11-10', '2025-11-10', ['2025-11-10' => ['canCheckOut' => false]], false, '2025/11/10は出庫できません。'],
            '滞在中の日付がまたぎ不可' => ['2025-11-09', '2025-11-11', ['2025-11-10' => ['canCrossTime' => false]], false, '満車のため、2025/11/10をまたぐ予約はできません。'],

            // 設定データがない日（PHPでは制限なしとして判定される）。
            '入庫日の設定なし' => ['2025-11-10', '2025-11-11', [], true, '', ['2025-11-10']],
            '滞在中の日の設定なし' => ['2025-11-09', '2025-11-11', [], true, '', ['2025-11-10']],
            '出庫日の設定なし' => ['2025-11-09', '2025-11-10', [], true, '', ['2025-11-10']],
        ];
    }

    /**
     * 指定期間の各日について、既定ではすべて許可となる設定を作成し、
     * overridesで指定された日だけ該当の制限を「満車」にする。
     * 範囲外の日付（逆順ケース）は作成しない。
     *
     * @param array<string, array<string, bool>> $overrides
     */
    private function createLimitSettings(
        string $startDate,
        string $endDate,
        array $overrides,
        array $missingDates = []
    ): void
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        if ($start->gt($end)) {
            return;
        }

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $key = $date->toDateString();
            if (in_array($key, $missingDates, true)) {
                continue;
            }
            $settings = $overrides[$key] ?? [];

            FullLimitSetting::create([
                'office_id' => $this->officeId,
                'target_date' => $key,
                'load_limit_symbol' => ($settings['canCheckIn'] ?? true)
                    ? LimitOverStatus::VACANT->value : LimitOverStatus::FULL->value,
                'unload_limit_symbol' => ($settings['canCheckOut'] ?? true)
                    ? LimitOverStatus::VACANT->value : LimitOverStatus::FULL->value,
                'cross_time_symbol' => ($settings['canCrossTime'] ?? true)
                    ? LimitOverStatus::VACANT->value : LimitOverStatus::FULL->value,
            ]);
        }
    }
}
