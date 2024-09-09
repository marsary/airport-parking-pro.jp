<?php
namespace App\Services\Graphs;

use Carbon\Carbon;
use Illuminate\Http\Request;

trait GraphCommon
{
    /**
     * リクエストのビューに基づいて、開始日と終了日を取得
     *
     * @param Request $request
     * @return array 開始日と終了日を返す
     */
    private function getStartEndDateFromView(Request $request)
    {
        switch ($request->view) {
            case 'monthly':
                $startDate = Carbon::parse($request->currentStartDate);
                if($request->has('nextPrev')) {
                    if($request->nextPrev == 'next') {
                        $startDate->addMonth();
                    } elseif($request->nextPrev == 'prev') {
                        $startDate->subMonth();
                    }
                }
                $endDate = (clone $startDate)->endOfMonth();
                break;
            case 'weekly':
                $startDate = Carbon::parse($request->currentStartDate);
                if($request->nextPrev == 'next') {
                    $startDate->addWeek();
                } elseif($request->nextPrev == 'prev') {
                    $startDate->subWeek();
                }
                $endDate = (clone $startDate)->endOfWeek();
                break;
            case 'manual':
                $startDate = Carbon::parse($request->startDate);
                $endDate = Carbon::parse($request->endDate);
                break;
        }

        return [$startDate, $endDate];
    }
}
