<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Services\Soc\AfterSyncService;
use App\Services\Soc\SyncDealService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealController extends Controller
{
    /**
     * 同期が必要な（sync_flg が false の）取引一覧を取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDealsForSync(SyncDealService $syncDealService)
    {
        DB::listen(function ($query) {
            logger($query->sql, $query->bindings);
        });

        $deals = Deal::where('sync_flg', false)
            ->with(['agency', 'member', 'payment.paymentDetails', 'dealGoods.good', 'memberCar.car', 'arrivalFlight.airline', 'arrivalFlight.depAirport'])
            ->get();

        $syncData = $syncDealService->setDeals($deals)->getSortedDataForSync();

        return response()->json([
            'status' => 'success',
            'count' => count($syncData),
            'data' => $syncData
        ]);
    }

    /**
     * 同期完了後、指定された取引IDの同期フラグと関連データを更新
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAfterSync(Request $request)
    {
            error_log('request'."\n",3,"../storage/logs/test.log");

        try {
            $validated = $request->validate([
                'rsvs' => 'required|array',
                'rsvs.*.rsv_id1' => 'required|integer',
                'rsvs.*.sync_flg' => 'required|integer',
                'rsvs.*.u_id' => 'nullable|integer',
                'rsvs.*.member_flg' => 'nullable|boolean',
            ]);

            $service = app(AfterSyncService::class);
            $service->updateAfterSync($validated['rsvs']);

            return response()->json([
                'status' => 'success',
                'message' => $service->syncCount . ' 件の取引を同期済みとしてマークしました。',
                'results' => $service->results,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'バリデーションエラーが発生しました。',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => '更新中にエラーが発生しました。',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
