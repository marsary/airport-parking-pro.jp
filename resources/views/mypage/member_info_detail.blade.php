<!-- N-14 -->
<!-- 顧客情報詳細 -->
@extends('layouts.mypage.authenticated')
@section('content')
  <div class="p-user-input__inner">
    <!-- パンくず -->
    <ul class="p-user-breadcrumb">
      <li class="p-user-breadcrumb__list"><a href="/user/n-5.php">マイページ</a></li>
      <li class="p-user-breadcrumb__list">顧客情報詳細</li>
    </ul>
    <!-- 顧客情報 -->
    <div class="c-title__user">顧客情報</div>
    <table class="l-user-table-confirm">
      <tr>
        <th>顧客コード</th>
        <td>1234567890</td>
        <th>お客様氏名</th>
        <td>サン太郎</td>
        <th>ふりがな</th>
        <td>さんたろう</td>
        <th>郵便番号</th>
        <td>111-0000</td>
        <th>電話番号</th>
        <td>090-1234-5678</td>
        <th>Mail</th>
        <td>testaaatestaaaatestaaatest@test.jp</td>
        <th>利用回数</th>
        <td>8回</td>
        <th>会員ランク</th>
        <td>シルバー</td>
        <th>ラベル2</th>
        <td>ダミーダミー</td>
        <th>ラベル3</th>
        <td>ダミーダミー</td>
        <th>ラベル4</th>
        <td>ダミーダミー</td>
      </tr>
    </table>
    <!-- 顧客情報編集ページへのリンク -->
    <div class="l-flex--column l-grid--rgap1 l-flex--center u-mt2">
      <a href="n-15.php" class="c-button__submit">編集</a>
      <a href="/user/n-5.php" class="c-button__submit">マイページに戻る</a>
    </div>

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