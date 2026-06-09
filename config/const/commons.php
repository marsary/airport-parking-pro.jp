<?php

return [
    'office_id' => env('OFFICE_ID', 1),
    // 予約カレンダーの選択できる日付は5か月先まで
    'reserve_cal_month_periods' => env('RESERVE_CAL_MONTH_PERIODS', 5),
    // 予約開始可能日
    'reservable_start_date' => env('RESERVABLE_START_DATE', '2026-07-01'),
];
