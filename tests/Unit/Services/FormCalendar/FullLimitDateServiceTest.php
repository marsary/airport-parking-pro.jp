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
    }


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

    }
}
