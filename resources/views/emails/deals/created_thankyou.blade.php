@php

@endphp

<p>{{ $deal->name }}　様</p>
<p>「サンパーキング 成田店」です。<br>
    ご予約ありがとうございます。<br>
    本メールは出庫日まで大切に保存ください。<br><br>
    お客様のご予約番号は<br>
    [{{ $deal->reserve_code }}]　になります。
</p>

<p>予約詳細は以下の通りです。<br>
    =================================================
</p>

<p>予約番号：
{{ $deal->reserve_code }}
</p>

<p>予約日：
{{ $deal->reserve_date?->format('Y/m/d') }}
</p>

<p>入庫予定：
{{ $deal->loadDateTime() }}
</p>

<p>出庫予定：
{{ $deal->unload_date_plan?->format("Y/m/d") }}
</p>

<p>出庫予定：
{{ $deal->unload_date_plan?->format("Y/m/d") }}
</p>

<p>駐車料金：
{{ $deal->loadDateTime() }}
</p>

<p>支払方法：
入庫時にお支払い
</p>

<p>------------------------------------</p>

<p>利用料金：
{{ $deal->loadDateTime() }}
</p>

<p>※オプション（洗車、旅行保険など）料金は含みません。</p>

<p>=======================<br>
    スタッフ一同、<br>
    {{ $deal->name }}様の<br>
    お越しをお待ちしています。<br>
</p>

<p>【ご予約の確認・変更・キャンセル】<br>
    ご予約の確認・変更・キャンセルはこちらから<br>
    TEL：0476-33-1166<br>
    ＊上記からの確認、変更。キャンセルはご入庫前日 23 時までとなります<br>
</p>

<p>【アクセス】
    <a href= "https://goo.gl/maps/g7J2DC7xtiykyKmr7">https://goo.gl/maps/g7J2DC7xtiykyKmr7</a>
</p>

<p>■お問合せ■<br>
    サン予約センター<br>
    TEL：0476-33-1123<br>
    E-mail：center@sunparking.co.jp<br>
    &lt;営業時間&gt; 9：00～18：00
</p>

<p>■当日のお問合せ■<br>
    サンパーキング成田店<br>
    TEL：0476-33-1166<br>
    E-mail：center@sunparking.co.jp<br>
    &lt;営業時間&gt; 5：00～22：30（成田空港最終便到着まで対応）
</p>

<p>★次回のご予約は LINE 公式アカウントからが<br>
    断然！便利でおトク！★<br>
    公式 LINE アカウントなら<br>
    ・LINE からおトク情報が届きます！<br>
    ・LINE からすぐ予約ができます！<br>
    ・LINE から到着電話もできます！<br>
    ・LINE から旅先からの到着日や到着便の<br>
    　変更連絡だってできちゃいます！<br>
    ・LINE 友だちなら来店ポイント貯められます！
</p>

<p>まだの方は、今すぐ！こちらから<br>
    <a href= "https://lin.ee/bn2blwU">https://lin.ee/bn2blwU</a>
</p>

<p>
    <img src="{{ asset('images/form/2026sm_CP_02.webp') }}" alt="Sunparking" class="p-error-page__logo">
</p>
