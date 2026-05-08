<?php

namespace Tests\Feature\Api;

use App\Enums\CarSize;
use App\Enums\Cms\SocOfficeId;
use App\Enums\DealStatus;
use App\Models\Car;
use App\Models\CarColor;
use App\Models\Deal;
use App\Models\Member;
use App\Models\MemberCar;
use App\Models\Office;
use App\Services\Soc\AfterSyncService;
use App\Services\Soc\SyncDealService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Mockery;
use Tests\TestCase;

class DealControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $apiToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiToken = config('services.internal_api.token');
        // 共通マスタデータの作成（必要に応じて）
        $this->seed(\Database\Seeders\AirportSeeder::class);
        Office::factory()->create(['id' => SocOfficeId::MY_OFFICE_ID_NARITA->value]);
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->seed(\Database\Seeders\CarColorSeeder::class);

        // テスト用のトークンを設定
        Config::set('services.internal_api.token', $this->apiToken);
        Config::set(['services.internal_api.allowed_ips' => ['localhost','127.0.0.1']]);
    }

    private function createDeal(array $memberData = [],array $dealData = [])
    {
        $officeId = SocOfficeId::MY_OFFICE_ID_NARITA->value;
        $member = Member::factory()->create($memberData);
        $car = Car::factory()->create(['size_type' => CarSize::MEDIUM->value]);
        $memberCar = MemberCar::factory()->create([
            'car_id' => $car->id,
            'car_color_id' => CarColor::first()->id,
            'member_id' => $member->id,
        ]);

        $deal = Deal::factory()->create([
            'office_id' => $officeId,
            'member_id' => $member->id,
            'member_car_id' => $memberCar->id,
            'status' => DealStatus::NOT_LOADED->value,
        ] + $dealData);

        return [
            'officeId' => $officeId,
            'member' => $member,
            'deal' => $deal,
        ];
    }

    /**
     * getDealsForSync: 成功パターン
     */
    public function test_get_deals_for_sync_success()
    {
        // 1. 同期対象のダミーデータ作成（SQL発行確認用）
        $this->createDeal(
            [],
            [
                'sync_flg' => false,
            ]
        );

        // 2. サービスのモック作成と登録
        $mock = Mockery::mock(SyncDealService::class)->makePartial(); // setDeals メソッドもモックするため partial mock を使用
        // setDeals メソッドが呼ばれることを期待し、自身を返す
        $mock->shouldReceive('setDeals')
            ->once()
            ->andReturnSelf(); // メソッドチェーンを可能にするため、モック自身を返す
        $mock->shouldReceive('getSortedDataForSync')
            ->once()
            ->andReturn([
                1 => ['rsv_id' => 1, 'name' => 'テスト太郎'],
                2 => ['rsv_id' => 2, 'name' => 'テスト次郎']
            ]);
        $this->app->instance(SyncDealService::class, $mock);

        // 3. リクエスト実行 (BearerトークンとlocalhostのIPを指定)
        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])
        ->getJson(route('deals_for_sync', [], false))
        ;

        // 4. 検証
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'count' => 2,
                'data' => [
                    '1' => ['rsv_id' => 1],
                    '2' => ['rsv_id' => 2]
                ]
            ]);
    }

    /**
     * updateAfterSync: 成功パターン
     */
    public function test_update_after_sync_success()
    {
        $payload = [
            'rsvs' => [
                ['rsv_id1' => 100, 'sync_flg' => 1, 'u_id' => 500, 'member_flg' => true],
            ]
        ];

        // サービスのモック作成
        $mock = Mockery::mock(AfterSyncService::class);
        // プロパティへのアクセスを許容
        $mock->syncCount = 1;
        $mock->results = [
            ['id' => 100, 'dealUpdated' => true, 'memberUpdated' => true]
        ];

        $mock->shouldReceive('updateAfterSync')
            ->once()
            ->with($payload['rsvs'] ?? Mockery::any());

        $this->app->instance(AfterSyncService::class, $mock);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])->postJson(route('update_after_sync'), $payload, [
            'REMOTE_ADDR' => '127.0.0.1'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'results' => [
                    ['id' => 100, 'dealUpdated' => true]
                ]
            ]);
    }

    /**
     * 認証失敗パターン (トークン不正)
     */
    public function test_api_access_unauthorized_with_wrong_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer wrong-token',
        ])->getJson(route('deals_for_sync'), [
            'REMOTE_ADDR' => '127.0.0.1'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthorized']);
    }

    /**
     * 認証失敗パターン (許可されていないIP)
     */
    public function test_api_access_forbidden_with_external_ip()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])->getJson(route('deals_for_sync'), [
            'REMOTE_ADDR' => '255.255.255.1' // localhost以外
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Forbidden']);
    }

    /**
     * updateAfterSync: バリデーション失敗パターン
     */
    public function test_update_after_sync_validation_error()
    {
        // 必須項目 'rsvs' が欠落しているリクエスト
        $payload = [];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])->postJson(route('update_after_sync'), $payload, [
            'REMOTE_ADDR' => '127.0.0.1'
        ]);

        $response->assertStatus(422) // Unprocessable Entity
            ->assertJsonValidationErrors(['rsvs']);
    }

    /**
     * サーバーエラー（例外発生）時のパターン
     */
    public function test_update_after_sync_server_error()
    {
        $payload = ['rsvs' => [['rsv_id1' => 1, 'sync_flg' => 1]]];

        $mock = Mockery::mock(AfterSyncService::class);
        $mock->shouldReceive('updateAfterSync')
            ->andThrow(new \Exception('想定外のDBエラー'));
        $this->app->instance(AfterSyncService::class, $mock);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])->postJson(route('update_after_sync'), $payload, [
            'REMOTE_ADDR' => '127.0.0.1'
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'status' => 'error',
                'message' => '更新中にエラーが発生しました。'
            ]);
    }

    /**
     * getDealsForSync: 成功パターン
     * 実際のサービスクラスを使う
     */
    public function test_get_deals_for_sync_success_from_db()
    {
        // 1. 同期対象のデータ作成
        ['deal' => $deal] =
        $this->createDeal(
            [],
            [
                'sync_flg' => false,
            ]
        );


        // 3. リクエスト実行 (BearerトークンとlocalhostのIPを指定)
        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])
        ->getJson(route('deals_for_sync', [], false))
        ;

        // 4. 検証
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'count' => 1,
                'data' => [
                    ($deal->id) => ['rsv_id' => $deal->id],
                ]
            ]);
    }

    /**
     * updateAfterSync: 成功パターン
     * 実際のサービスクラスを使う
     */
    public function test_update_after_sync_success_to_db()
    {
        // 1. 同期対象のデータ作成
        ['deal' => $deal] =
        $this->createDeal(
            [],
            [
                'sync_flg' => false,
            ]
        );

        $payload = [
            'rsvs' => [
                ['rsv_id1' => $deal->id, 'sync_flg' => 1, 'u_id' => 500, 'member_flg' => true],
            ]
        ];


        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiToken,
        ])->postJson(route('update_after_sync'), $payload, [
            'REMOTE_ADDR' => '127.0.0.1'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'results' => [
                    ['id' => $deal->id, 'dealUpdated' => true, 'memberUpdated' => true]
                ]
            ]);
    }
}
