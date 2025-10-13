<!-- N-15 -->
<!-- 顧客編集 -->
@extends('layouts.mypage.authenticated')
@section('content')
  <div class="p-user-input__inner--sm">
    <!-- パンくず -->
    <ul class="p-user-breadcrumb">
      <li class="p-user-breadcrumb__list"><a href="/user/n-5.php">マイページ</a></li>
      <li class="p-user-breadcrumb__list">顧客情報編集</li>
    </ul>
    <h2 class="c-title__user">顧客情報</h2>
    <form action="" method="POST">
      <div class="l-flex l-grid--cgap1 l-flex--column--md l-flex--items-start--md">
        <div class="u-w-full-wide">
          <label for="name">氏名※</label>
          <input type="text" id="name" name="name" class="u-w-full-wide">
        </div>
        <div class="u-w-full-wide">
          <label for="kana">ふりがな※</label>
          <input type="text" id="kana" name="kana" class="u-w-full-wide">
        </div>
      </div>

      <label for="tel">携帯番号※</label>
      <input type="tel" id="tel" name="tel" class="u-w-half u-w-full-wide--md">

      <label for="zip">郵便番号</label>
      <input type="text" id="zip" name="zip" class="u-w-half">

      <label for="email">メールアドレス※</label>
      <input type="email" id="email" name="email" class="u-w-half u-w-full-wide--md">

      <label for="email-confirm">メールアドレス※（確認用）</label>
      <input type="email" id="email-confirm" name="email-confirm" class="u-w-half u-w-full-wide--md">
      <!-- ボタン　サブミット -->
      <div class="l-flex--column l-grid--rgap1 l-flex--center u-mt2">
        <button type="submit" class="c-button__submit">更新</button>
        <a href="/user/n-14.php" class="c-button__submit">戻る</a>
      </div>
    </form>
  </div><!-- ./p-user-input__inner -->
@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush