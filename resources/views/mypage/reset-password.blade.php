<!-- N-20 -->
<!-- 
 パスワード変更
 ログイン前のページとは異なる
 -->
@extends('layouts.mypage.authenticated')
@section('content')
  <div class="p-user-input__inner">
    <!-- パンくず -->
    <ul class="p-user-breadcrumb">
      <li class="p-user-breadcrumb__list"><a href="">マイページ</a></li>
      <li class="p-user-breadcrumb__list">パスワード再設定</li>
    </ul>
    <!-- パスワード変更 -->
    <div class="l-container__admin">
      <div class="c-form__admin--title">パスワード再設定</div>
      <form action="n-21.php" method="POST" class="c-form__admin">
        <div>
          <label for="password">新しいパスワードを入力してください</label>
          <input type="text" name="password" class="c-input__admin" />
          <label for="password_confirm">もう一度入力してください</label>
          <input type="text" name="password_confirm" class="c-input__admin" />
        </div>
        <input type="submit" value="更新" class="c-button__submit u-horizontal-auto" />
      </form>
      <a href="/user/n-5.php" class="c-button__submit u-w170 u-horizontal-auto">マイページに戻る</a>
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