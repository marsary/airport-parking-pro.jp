@push('css')
<style>
  .p-auth-note { font-size: 14px; line-height: 1.7; margin-bottom: 16px; }
  .p-auth-status { background: #e8f6ef; color: #04794d; border: 1px solid #9bd8bb; padding: 10px 12px; font-size: 14px; line-height: 1.6; margin-bottom: 16px; }
  .p-auth-expired { background: #fff4e5; color: #9a5b00; border: 1px solid #ffc978; padding: 10px 12px; font-size: 14px; line-height: 1.6; margin-bottom: 16px; }
  .p-auth-link { text-align: center; margin-top: 20px; font-size: 14px; }
  .p-auth-link a { text-decoration: underline; }
</style>
@endpush

@push('scripts')
<script>
  // 画面を長時間開いたままにすると、フォーム内のCSRFトークンがセッション切れで無効になり
  // 送信時に 419 になるため、有効期限が近づいた画面は自動で読み込み直す
  (function () {
    var loadedAt = Date.now();
    // セッション有効期限（分）の5分前に再読み込み（最短1分）
    var limitMs = Math.max({{ (int) config('session.lifetime') }} - 5, 1) * 60 * 1000;

    function isStale() {
      return Date.now() - loadedAt >= limitMs;
    }
    function reloadIfStale() {
      if (isStale()) {
        location.reload();
      }
    }

    // 戻る／進む・タブ復元でキャッシュから表示された場合は必ず読み込み直す
    window.addEventListener('pageshow', function (e) {
      if (e.persisted) {
        location.reload();
      }
    });
    // 画面に戻ってきたとき（タブ切り替え・PCスリープ復帰など）と1分ごとに確認
    document.addEventListener('visibilitychange', function () {
      if (document.visibilityState === 'visible') {
        reloadIfStale();
      }
    });
    window.addEventListener('focus', reloadIfStale);
    setInterval(reloadIfStale, 60 * 1000);
  })();
</script>
@endpush
