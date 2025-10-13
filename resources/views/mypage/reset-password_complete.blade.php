<!-- N-20 -->
<!-- ログインする前のパスワード再設定とは違う
 こちらはログイン後 -->
@extends('layouts.mypage.authenticated')
@section('content')
  <div class="p-user-input__inner u-mt4">
    <div class="c-form__admin--title">パスワードを変更しました。</div>
    <div class="l-flex--center u-mt3">
      <a href="/user/n-5.php" class="c-button__submit u-w170">マイページに戻る</a>
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