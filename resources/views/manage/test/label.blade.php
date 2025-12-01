<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="" type="image/svg+xml">

    <title>ラベル印刷プレビュー</title>
</head>
<body>
    <div class="preview-container">

        <div class="label">
            <div class="label-content">
                <div class="logo-section"></div>
                <!-- 左側: 予約情報 -->
                <div class="info-section">
                    <div>
                        <div class="info-line">{{ $receipt_code ?? '--' }}</div>
                        <div class="info-line">{{ $member_name ?? '' }}</div>
                        <div class="info-line">{{ '到着日 ' . $arrive_date }}</div>
                        <div class="info-line">{{ $arrival_flight_name . ' ' . $arrive_time }}</div>
                        <div class="info-line">{{ '出発地 ' . $dep_airport_code }}</div>
                        {{--  左からナンバー・車名・車の色  --}}
                        <div class="info-line label-text">{{ $car_number . ' ' . $car_name . ' ' . $car_color_name }}</div>
                    </div>
                </div>

                <!-- 右側: 表と日付 -->
                <div class="table-section">
                    <table class="grid-table">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>

                    <div class="print-date">
                        {{ $print_date }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
