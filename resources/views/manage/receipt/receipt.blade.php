@extends('layouts.member.authenticated')

@section('content')
    @include('include.messages.errors')
<div id="common">
<div id="header">
    <input type="button" onclick="self.print();window.close();" value="印刷" style="float: left;" id="print_button">
    <dev id="time_display">読込時刻：<span id="time_display_text"></span></dev>
    <form action="{{route('manage.receipts.update_register', $deal->id)}}" method="post" style="text-align: right;">
        @csrf
        @method('PUT')
        レジ番号：
        <input type="text" id="cash_register_id" name="cash_register_id" value="{{$payment->cash_register_id ?? ''}}">
        {{--  <input type="submit" value="再読み込み" id="reload_button">  --}}
    </form>
</div>
    <div class="page">
        <section class="ticket">
            <div class="ticket_meta">
                <p class="receipt_time">
                    {{$receiptTime->format('Y/m/d H:i')}}
                </p>
                <p>
                    {{$office->name}}
                </p>
            </div>
            <!-- ticket_meta -->

            <div class="ticket_number">
                <p>
                    {{$deal->receipt_code}}
                </p>
                <p>
                    {{$member?->used_num}}
                </p>
            </div>
            <!-- ticket_number -->

            <div class="ticket_detail">
                <div class="ticket_detail_item">
                    <p style="white-space: nowrap; max-width: 330px !important;" id="user_name">
                        {{$deal->kana ? mb_convert_kana($deal->kana, "Vc"): '　'}}
                    </p>
                    <p style="white-space: nowrap; max-width: 390px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;" id="car_model">
                        {{$memberCar?->car->carMaker->name}} {{$memberCar?->car->name}}
                    </p>
                </div>
                <div class="ticket_detail_item">
                    <p>
                        {{$payment->load_date?->format('Y年m月d日')}}
                    </p>
                    <p>
                        {{$memberCar?->number}}
                    </p>
                </div>
                <div class="ticket_detail_item">
                    <p>
                        {{$payment->unload_date_plan?->format('Y年m月d日')}}
                    </p>
                    <p>
                        {{$memberCar?->carColor->name}}
                    </p>
                </div>
            </div>
            <!-- ticket_detail -->

            <div class="ticket_name">
                <p>
                    {{$payment->user_name}}
                </p>
            </div>
            <!-- ticket_name -->
        </section>
        <!-- ticket -->

        <section class="receipt">
            <div class="receipt_date">
                <p style="padding-right: 35px;">
                    {{$receiptTime->format('Y')}}
                </p>
                <p style="padding-right: 35px;">
                    {{$receiptTime->format('m')}}
                </p>
                <p>
                    {{$receiptTime->format('d')}}
                </p>
            </div>
            <!-- receipt_date -->

            <div class="receipt_money">
                <p>
                    {{$payment->total_price > 0 ? number_format($payment->total_price) . ' -' : '＊＊＊＊＊＊＊＊';}}
                </p>
            </div>
            <!-- receipt_money -->

            <div class="receipt_about">
                <p style="padding-bottom: 8px;">
                    {{$office->name ? $office->name : '　'}}
                </p>
                <p style="padding-bottom: 8px;">
                    {{$office->receipt_address ? $office->receipt_address : '　'}}
                </p>
                <p style="padding-bottom: 8px;">
                    {{$office->receipt_tel ? 'TEL:' . $office->receipt_tel : '　'}}

                </p>
            </div>
            <!-- receipt_about -->

            <div class="receipt_detail">
                <p>
                    {{number_format($payment->total_price - $payment->tax_free)}}
                </p>
                <p>
                    {{number_format($payment->total_tax)}}
                </p>
                <p>
                    {{number_format($payment->tax_free)}}
                </p>
            </div>
            <!-- receipt_detail -->
        </section>
        <!-- receipt -->

        <section class="remarks">
            <div>
                <div class="remark_header">
                    <div><span class="remarks_title">※弊社使用欄※　<small>（お客様より収集した個人情報は駐車場管理に適切に使用いたします。）</small></span></div>
                    <div class="remarks_user_num">{{$deal->num_members}}人</div>
                </div>
                <table class="remarks_table">
                    <tbody>
                        <tr>
                            <th>駐車場</th>
                            <td>{{$office->name ? $office->name : '　'}}</td>
                            <th>顧客ID</th>
                            <td>{{$member?->member_code ? $member?->member_code : '　'}}</td>

                            <th>氏名</th>
                            <td>
                                <?php
                                    $name = '';
                                    if($deal->name) {
                                        $name = $deal->name;
                                    } elseif($deal->kana) {
                                        $name = mb_convert_kana($deal->kana, "Vc");
                                    }
                                ?>
                                <?= $name ?>
                            </td>
                        </tr>
                        <tr>
                            <th>受付ID</th>
                            <td>{{$deal->receipt_code}}{{$member?->used_num ? '（' . $member?->used_num . '回目）' : ''}}</td>

                            </td>
                            <th>住所</th>
                            <td colspan="3">
                            </td>
                        </tr>
                        <tr>
                            <th>携帯</th>
                            <td></td>
                            <th>TEL</th>
                            <td>{{$deal->tel}}</td>
                            <th>車種</th>
                            <td>
                                {{$memberCar?->car->name}} {{$memberCar?->number}} {{$memberCar?->carColor->name}}
                            </td>

                            </td>
                        </tr>
                        <tr>
                            <th>日程</th>
                            <td>
                                @if ($deal->transaction_type != \App\Enums\TransactionType::PURCHASE_ONLY->value)
                                    @if ($payment->days == 1)
                                        {{$payment->load_date?->format('Y.m.d')}}（１日利用）
                                    @else
                                        {{$payment->load_date?->format('Y.m.d')}}-{{$payment->unload_date_plan?->format('Y.m.d')}}({{$payment->days}}日間)
                                    @endif
                                @endif
                            </td>
                            <th>到着便</th>
                            <td colspan="3">
                            </td>
                        </tr>
                        <tr>
                            <th nowrap>合計金額</th>
                            <td>{{number_format($payment->total_price)}} 円</td>
                            <th>割引率</th>
                            <td>
                            </td>
                            <th>割引券</th>
                            <td>
                                {{$couponTotal ? number_format($couponTotal) . ' 円' : ''}}
                                {{!empty($couponDetails) ? ' (' . implode(', ', $couponDetails) . '引き) ':''}}
                            </td>
                        </tr>
                        <tr>
                            @php
                                $maxColCount = 3;
                                $currentColIdx = 0;
                            @endphp
                            @foreach ($paymentDetails as $idx =>  $item)
                                @if ($currentColIdx < $maxColCount && \App\Enums\PaymentMethodType::tryFrom($item->paymentMethod->type) != \App\Enums\PaymentMethodType::ACCOUNTS_RECEIVABLE)
                                    <th>支払 {{$currentColIdx + 1}}</th>
                                    <td>
                                        {{\App\Services\View\Receipt::getPaymentDetail($paymentDetails, $idx)}}
                                    </td>
                                    @php
                                        $currentColIdx++;
                                    @endphp
                                @endif
                                @endforeach
                        </tr>
                        <tr>
                            <th>追加pt</th>
                            <td>

                            </td>
                            <th>使用pt</th>
                            <td>

                            </td>
                            <th>累積pt</th>

                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th>代理店</th>
                            <td>
                                {{$deal->agency?->name}}
                            </td>
                            <th>マイル</th>
                            <td>
                            </td>
                            <th></th>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <th>商品</th>
                            <td>
                            </td>
                            <th>取扱</th>
                            <td>
                            </td>
                            <th>レジ</th>
                            <td>{{$payment->user_name}}（レジNo：{{$payment->cash_register_id}}）</td>
                        </tr>
                        <tr>
                            <th>　</th>
                            <td>　
                            </td>
                            <th>　</th>
                            <td>　
                            </td>
                            <th>　</th>
                            <td>　
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="bottom_time receipt_time">{{$receiptTime->format('Y/m/d H:i')}}</p>
                {{--  <p class="bottom_time"><?= date('Y/m/d H:i', strtotime($html['reciept_time'])); ?></p>  --}}
            </div>
            <!-- receipt_detail -->
        </section>
        <!-- receipt -->
    </div>
@endsection
@push("scripts")
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script>
        const pad = (num) => String(num).padStart(2, '0');
        function getCurrentTime() {
            const now = new Date();
            const year = now.getFullYear();
            const month = now.getMonth() + 1;
            const day = now.getDate();
            const Hour = now.getHours();
            const Min = now.getMinutes();

            return {
                year: year,
                month: month,
                day: day,
                Hour: Hour,
                Min: Min,
            }
        }

        // 画面表示された際に印刷ボタンにフォーカスする
        $(document).ready( function(){
            // 印刷ボタンにフォーカス
            $('#print_button').focus();
            const {Hour, Min} = getCurrentTime();

            $("#time_display_text").text(pad(Hour) + "時" + pad(Min) + "分");

        });
        const userNameText = $('#user_name').text();
        const carModelText = $('#car_model').text();
        const userNameTextElem = document.getElementById("user_name");
        const carModelTextElem = document.getElementById("car_model");
        function selectUserName() {
            userNameTextElem.innerText = userNameText;
            resizeUserText();
        }

        function selectCarModel() {
            carModelTextElem.innerText = carModelText;
            resizeCarText();
        }

        function resizeUserText() {
            /* 文字数が少なくなったときのため、フォントサイズを戻せるようにします。
            他にstyleの属性があればfont-sizeに関するところを除いてstyleに上書きしましょう。
            今回はないのでstyle属性ごと削除します。*/
            var nameSize = 24;
            userNameTextElem.removeAttribute('style');
            console.log(userNameTextElem.getBoundingClientRect().width , userNameTextElem.scrollWidth);
            userNameTextElem.style.whiteSpace ='nowrap';
            userNameTextElem.style.maxWidth ='330px';
            userNameTextElem.style.textOverflow = 'ellipsis';
            userNameTextElem.style.overflow = 'hidden';
            for (
            let size = 24;
            userNameTextElem.getBoundingClientRect().width< userNameTextElem.scrollWidth && size >= 15;
            size -= 3
            /* 文字がはみ出すサイズが存在していたので、1ずつ減らすのを3ずつ減らすという少し速いペースでフォントサイズを小さくしてみました。
            こちらには正解不正解はなく、場合によって調整して遊んでみてください。*/
            ) {
                userNameTextElem.style.fontSize = size + "px";
                nameSize = size;
            // userNameTextElem.setAttribute("style", `font-size: ${size}px`); // こちらも可能
            }

            if (nameSize <= 15) {
                userNameTextElem.style.whiteSpace = 'normal';
                userNameTextElem.style.lineHeight = '15px';
                userNameTextElem.style.marginTop = 'auto';
                userNameTextElem.style.marginBottom = 'auto';
                userText =  $('#user_name').text();
                $('#user_name').text(userText.substring(0 ,65) + '...');

            }
        }

        function resizeCarText() {
            /* 文字数が少なくなったときのため、フォントサイズを戻せるようにします。
            他にstyleの属性があればfont-sizeに関するところを除いてstyleに上書きしましょう。
            今回はないのでstyle属性ごと削除します。*/
            carModelTextElem.removeAttribute('style');
            console.log(carModelTextElem.getBoundingClientRect().width , carModelTextElem.scrollWidth);
            carModelTextElem.style.whiteSpace ='nowrap';
            carModelTextElem.style.maxWidth ='390px';
            carModelTextElem.style.textOverflow = 'ellipsis';
            carModelTextElem.style.overflow = 'hidden';
            for (
            let size = 24;
            carModelTextElem.getBoundingClientRect().width< carModelTextElem.scrollWidth && size >= 15;
            size -= 3
            /* 文字がはみ出すサイズが存在していたので、1ずつ減らすのを3ずつ減らすという少し速いペースでフォントサイズを小さくしてみました。
            こちらには正解不正解はなく、場合によって調整して遊んでみてください。*/
            ) {
                carModelTextElem.style.fontSize = size + "px";
            // carModelTextElem.setAttribute("style", `font-size: ${size}px`); // こちらも可能
            }
        }

        setInterval(function () {
            const {
                year,
                month,
                day,
                Hour,
                Min,
            } = getCurrentTime();
            $("#time_display_text").text(pad(Hour) + "時" + pad(Min) + "分");
            const formattedDateTime = `${year}/${pad(month)}/${pad(day)} ${pad(Hour)}:${pad(Min)}`;
            $(".receipt_time").text(formattedDateTime);
        }, 5000);

        //ブラウザを開いたとき
        selectUserName();
        selectCarModel();
    </script>
@endpush
@push('css')
    <style>
        /* 全体のスタイル */
        body {
            font-family: SJIS;
            /*font-family: 'MS Gothic', monospace;*/
            margin: 0;
            padding: 0;
            background-color: white;
        }
        #common {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        #header {
            background-color: black;
            color: white;
            padding: 4px 6px;
            height: 60px;
        }

        #header input {
            margin-bottom: 0 !important;
            border-radius: 0;
        }

        #print_button, #reload_button {
            width: 120px;
            height: 40px;
            background-color: white;
            color: black;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        #time_display {
            color: white;
            font-size: 12px;
            margin-left: 10px;
        }

        /* ページ全体のレイアウト */
        .page {
            display: flex;
            flex-direction: column;
            padding: 20px;
            background-color: white;
        }

        /* チケット部分 */
        section.ticket {
            margin-bottom: 10px;
            padding: 15px;
            background-color: white;
            font-weight: bold;
        }

        .ticket_meta {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .ticket_number {
            display: flex;
            justify-content: space-around;
            text-align: left;
            margin: 15px 0;
        }


        .ticket_number p:first-child {
            font-size: 18px;
            margin: 0 0 5px 0;
            min-width: 200px;
        }

        .ticket_number p:last-child {
            font-size: 16px;
            margin: 0;
            min-width: 200px;
            text-align: center;
        }

        .ticket_detail {
            margin: 15px 0;
        }

        .ticket_detail_item {
            display: flex;
            justify-content: space-around;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .ticket_detail_item p {
            text-align: left;
            margin: 0;
            padding: 2px 5px;
            min-width: 200px;
        }

        .ticket_name {
            text-align: right;
            margin-top: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        /* 領収書部分 */
        section.receipt {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
        }

        .receipt_date {
            display: flex;
            justify-content: end;
            margin-bottom: 20px;
            font-size: 12px;
            padding-right: 15%;
        }

        .receipt_date p {
            margin: 0;
            padding: 0 10px;
            min-width: 25px;
            text-align: center;
        }

        .receipt_money {
            text-align: center;
            margin: 30px 0;
            font-size: 25px;
            font-weight: bold;
        }

        .receipt_about {
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.6;
            padding: 0 40%;
            white-space: nowrap
        }

        .receipt_about p {
            margin: 0 auto;
            padding: 4px 0;
        }

        .receipt_detail {
            margin: 20px 0;
            font-size: 14px;
            padding-right: 70%;
        }

        .receipt_detail p {
            margin: 0;
            text-align: right;
        }

        /* 備考欄 */
        section.remarks {
            padding: 10px;
            background-color: white;
        }

        .remark_header {
            display: flex;
            justify-content: space-between;
        }

        .remarks_title {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }

        .remarks_user_num {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .remarks_table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .remarks_table tr,
        .remarks_table th,
        .remarks_table td {
            border: 1px solid black !important;
            padding: 3px 5px;
            text-align: left;
            vertical-align: top;
        }

        .remarks_table th {
            font-weight: bold;
            white-space: nowrap;
            width: 80px;
        }

        .remarks_table td {
            background-color: white;
        }

        .bottom_time {
            text-align: right;
            margin-top: 10px;
            font-size: 12px;
            font-weight: bold;
        }

        /* 印刷用スタイル */
        @media print {
            #header {
                display: none;
            }

            body {
                font-family: SJIS;
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }
        }

        #print_button, #reload_button {
            width: 150px;
            height: 50px;
        }
        #time_display {
            float: left;
            margin-top: 16px;
            margin-left: 10px;
        }
    </style>
@endpush
