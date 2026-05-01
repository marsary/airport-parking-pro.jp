<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Services\Soc\AfterSyncService;
use App\Services\Soc\SyncDealService;
use Illuminate\Http\Request;

class DealController extends Controller
{
    /**
     * 同期が必要な（sync_flg が false の）取引一覧を取得
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDealsForSync(SyncDealService $syncDealService)
    {
        $deals = Deal::where('sync_flg', false)
            ->with(['agency', 'member', 'payment.paymentDetails', 'dealGoods.good', 'memberCar.car', 'arrivalFlight.airline', 'arrivalFlight.depAirport'])
            ->get();

        $syncData = $syncDealService->setDeals($deals)->getSortedDataForSync();

        return response()->json([
            'status' => 'success',
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

        try {
            $service = app(AfterSyncService::class);
            $service->updateAfterSync($request['rsvs']);

            return response()->json([
                'status' => 'success',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }
}
