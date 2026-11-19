@php

@endphp

<p>ウェブ予約による新規予約が実行されました。</p>

<p>お名前：
{{ $deal->name }}　様
</p>

<p>ふりがな：
{{ $deal->kana }}　様
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