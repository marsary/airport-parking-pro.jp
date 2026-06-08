<?php

return [
    'office_id' => env('OFFICE_ID', 1),
    // 予約カレンダーの選択できる日付は5か月先まで
    'reserve_cal_month_periods' => env('RESERVE_CAL_MONTH_PERIODS', 5),
];
