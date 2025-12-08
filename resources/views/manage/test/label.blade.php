<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="preload" as="style" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'" />
    <!-- css -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- web slides -->
    <!-- Optional - CSS SVG Icons (Font Awesome) -->
    <link rel="stylesheet" type='text/css' href="{{ asset('css/app.css') }}">
    <title>ラベル印刷プレビュー</title>
    {{--  <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: SJIS;
            background-color: #f0f0f0;
            padding: 20px;
            display: flex;
            justify-content: center; /* 横方向中央 */
            align-items: center;     /* 縦方向中央 */
            height: 100vh;
        }

        .preview-container {
            background: white;
            padding: 20px;
            max-width: 900px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .print-buttons {
            display: flex;
            justify-content: center;
            text-align: center;
            margin-bottom: 20px;
        }

        .print-buttons button {
            margin: 0 10px;
            font-weight: bolder !important;
            color:#333;
        }

        /* ラベル本体 - プレビューは横長表示 */
        .label {
            width: 80mm;
            height: 48mm;
            border: 1px solid #333;
            background: white;
            margin: 0 auto;
            overflow: hidden;
        }

        /* 印刷時は90度回転 */
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
                width: 48mm;
                height: 80mm;
            }
            .preview-container {
                box-shadow: none;
                padding: 0;
                transform: rotate(-90deg);
                width: 80mm;
                height: 48mm;
            }

            .print-buttons {
                display: none;
            }

            .label {
                border: none;
                margin: 0;
            }

            @page {
                size: 48mm 80mm;
                margin: 0;
            }
        }

        /* レイアウト: 左側と右側に分割 */
        .label-content {
            display: flex;
            height: 100%;
            padding: 5mm;
            padding-top: 8mm;
        }

        .logo-section {
            width:10mm;
        }

        /* 左側: 予約情報エリア */
        .info-section {
            flex: 1;
            padding-right: 3mm;
            /*border-right: 1px solid #ccc;*/
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            font-size: 6pt;
            line-height: 1.3;
        }

        .info-section .info-line {
            margin-bottom: 1mm;
        }

        .info-section .label-text {
            font-size: 7pt;
        }

        /* 右側: 表と日付エリア */
        .table-section {
            width: 30mm;
            padding-left: 3mm;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* 4×4の表 */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
        }

        .grid-table td {
            width: 25%;
            height: 6mm;
            border: 1px solid #333;
            text-align: center;
            vertical-align: middle;
        }

        /* 印刷日付 */
        .print-date {
            text-align: center;
            font-size: 7pt;
        }

    </style>  --}}
    <style>
        body {
            font-family: SJIS;
            display: flex;
            justify-content: center; /* 横方向中央 */
            align-items: center;     /* 縦方向中央 */
            background: white;
            margin: 0;
            padding: 0;
            width: 80mm;
            height: 48mm;
        }
        .preview-container {
            box-shadow: none;
            padding: 0;
            width: 80mm;
            height: 48mm;
        }

        .print-buttons {
            display: none;
        }

        .label {
            border: none;
            margin: 0;
        }
        /* レイアウト: 左側と右側に分割 */
        .label-content {
            width:100%;
            height: 100%;
            padding: 5mm;
            padding-top: 8mm;
        }

        .logo-section {
            float:left;
            width:10mm;
        }

        /* 左側: 予約情報エリア */
        .info-section {
            float:left;
            width: 37mm;
            font-size: 6pt;
            line-height: 1.3;
        }

        .info-section .info-line {
            margin-bottom: 1mm;
        }

        .info-section .label-text {
            font-size: 7pt;
        }

        /* 右側: 表と日付エリア */
        .table-section {
            width: 30mm;
            float:right;
        }

        /* 4×4の表 */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2mm;
        }

        .grid-table td {
            width: 25%;
            height: 6mm;
            border: 1px solid #333;
            text-align: center;
            vertical-align: middle;
        }

        /* 印刷日付 */
        .print-date {
            text-align: center;
            font-size: 7pt;
        }
    </style>
</head>
<body>
    <div class="preview-container">
        <div class="print-buttons">
            <button class="c-button--green pointer hover" onclick="window.print()">印刷</button>
            <button class="c-button--gray pointer hover" onclick="window.history.back()">キャンセル</button>
        </div>

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
