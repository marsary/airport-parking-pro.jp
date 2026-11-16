<!-- パスワード変更 -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list"><a href="{{ route('manage.master') }}">マスタ設定一覧</a></li>
        <li class="l-breadcrumb__list">パスワード変更</li>
      </ul>

      @include('include.messages.errors')
      @include('include.messages.success')

      <div class="l-container__inner">
        <div class="p-password-change">
          <p class="p-password-change__user">ログイン中のユーザー：{{ $user->name }}（{{ $user->email }}）</p>
          <form action="{{ route('manage.password.update') }}" method="POST" class="p-password-change__form">
            @csrf
            @method('PUT')
            <div class="p-password-change__row">
              <label for="current_password">現在のパスワード</label>
              <input type="password" name="current_password" id="current_password" class="c-input__admin" autocomplete="current-password" required>
            </div>
            <div class="p-password-change__row">
              <label for="password">新しいパスワード</label>
              <input type="password" name="password" id="password" class="c-input__admin" autocomplete="new-password" required>
            </div>
            <div class="p-password-change__row">
              <label for="password_confirmation">新しいパスワード（確認）</label>
              <input type="password" name="password_confirmation" id="password_confirmation" class="c-input__admin" autocomplete="new-password" required>
            </div>
            <p class="p-password-change__note">8文字以上で、英字と数字を両方含めてください。現在のパスワードと同じものは使えません。</p>
            <input type="submit" value="変更する" class="c-button__submit">
          </form>
        </div>
      </div>
    </main>
@endsection

@push('css')
<style>
  .p-password-change { max-width: 560px; }
  .p-password-change__user { margin-bottom: 24px; }
  .p-password-change__row { margin-bottom: 20px; }
  .p-password-change__row label { display: block; margin-bottom: 6px; font-weight: 500; }
  .p-password-change__row .c-input__admin { width: 100%; }
  .p-password-change__note { font-size: 14px; color: #555; margin-bottom: 24px; }
</style>
@endpush
