@php

@endphp

<p>{{ $deal->name }}　様</p>
<p>「サンパーキング 成田店」です。<br>
    ご予約ありがとうございます。<br>
    本メールは出庫日まで大切に保存ください。<br><br>
</p>

<p>予約詳細は以下の通りです。<br>
    =================================================
</p>

<p>予約日：
{{ $deal->reserve_date?->isoFormat('YYYY/MM/DD(ddd)') }}
</p>

<p>入庫予定：
{{ $deal->loadDateTimeISO() }}
</p>

<p>出庫予定：
{{ $deal->unload_date_plan?->isoFormat("YYYY/MM/DD(ddd)") }}
</p>

<p>駐車料金：
{{ number_format($deal->total_price + $deal->total_tax) }} 円（税込）
</p>

@if($deal->season_price)
<p>シーズン料金：
{{ number_format($deal->season_price + $deal->season_price_tax) }} 円（税込）
</p>
@endif

<p>------------------------------------</p>

<p>合計金額：
{{ number_format($deal->total_price + $deal->total_tax + ($deal->season_price ?? 0) + ($deal->season_price_tax ?? 0)) }} 円（税込）
</p>

<p>内消費税：
{{ number_format($deal->total_tax + ($deal->season_price_tax ?? 0)) }} 円（10％）
</p>
<br />

<p>支払方法： 入庫時にお支払い</p>

<p>※オプション（洗車、旅行保険など）料金は含みません。</p>

<p>=================================================<br>
    スタッフ一同、<br>
    {{ $deal->name }}様の<br>
    お越しをお待ちしています。<br>
=================================================
</p>

<p>【お問い合わせ、ご予約の確認・変更・キャンセル】<br>
    お問い合わせ、ご予約の確認・変更・キャンセルはこちらから<br>
    サン予約センター<br>
    TEL：0476-33-1123<br>
    E-mail：center@sunparking.co.jp<br>
    &lt;営業時間&gt; 9：00～18：00
</p>
<br />
<p>【アクセス】
    <a href= "https://goo.gl/maps/g7J2DC7xtiykyKmr7">https://goo.gl/maps/g7J2DC7xtiykyKmr7</a>
</p>
<br />
<p>■当日のお問合せ■<br>
    サンパーキング成田店<br>
    TEL：0476-33-1166<br>
    &lt;営業時間&gt; 5：00～22：30（成田空港最終便到着まで対応）
</p>
<br />

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
