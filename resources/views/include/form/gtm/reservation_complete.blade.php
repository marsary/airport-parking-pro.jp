{{--
  予約完了時のデータ送信（CV）
  - GTM_ID が設定された環境（本番）のみ
  - 予約登録の直後に1回だけ出力（store() で渡すフラッシュ値と予約番号が一致したときのみ）
    → 再読み込みやURL直打ちでは出力しない
--}}
@if (config('services.gtm.id') && $deal && session('gtm_reservation_complete') === $deal->reserve_code)
@php
    // 合計金額（予約完了メールの「合計金額」と同じ：駐車料金＋シーズン料金、税込）
    $gtmValue = (int) ($deal->total_price + $deal->total_tax + ($deal->season_price ?? 0) + ($deal->season_price_tax ?? 0));

    // 電話番号を国番号付き（+81、先頭の0を除く）に変換
    $gtmDigits = preg_replace('/\D/', '', (string) $deal->tel);
    if (str_starts_with($gtmDigits, '0')) {
        $gtmPhone = '+81' . substr($gtmDigits, 1);
    } elseif (str_starts_with($gtmDigits, '81')) {
        $gtmPhone = '+' . $gtmDigits;
    } else {
        $gtmPhone = '';
    }
@endphp
<script>
window.dataLayer = window.dataLayer || [];
dataLayer.push({
  event: 'reservation_complete',
  transaction_id: @json((string) $deal->reserve_code),
  value: {{ $gtmValue }},
  email: @json((string) $deal->email),
  phone: @json($gtmPhone),
});
</script>
@endif
