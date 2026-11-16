@extends('layouts.manage.app')

@section('content')
<div style="text-align: right; padding-right: 50px;">事業再構築　R2128E00068000</div>
<div class="l-container__admin">
  <figure class="u-mb1"><img src="{{asset('images/logo/logo.png')}}" alt="ロゴ" width="255px" height="auto" class="u-horizontal-auto is-block"></figure>
  <div class="c-form__admin--title">パスワードを忘れた方</div>
  <form action="{{route('manage.password.email')}}" method="POST" class="c-form__admin">
    @csrf
    @if (session('session_expired'))
      <p class="p-auth-expired">{{ session('session_expired') }}</p>
    @endif
    <p class="p-auth-note">登録済みのユーザーID（メールアドレス）を入力してください。<br>パスワード再設定用のURLをメールでお送りします。</p>
    @if (session('status'))
      <p class="p-auth-status">{{ session('status') }}</p>
    @endif
    <div class="u-mb2">
      <label for="email">ユーザーID（メールアドレス）</label>
      <input type="email" name="email" id="email" value="{{ old('email') }}" class="c-input__admin" autocomplete="username" required />
    </div>
    @error('email')
      <p style="color:red">{{ $message }}</p>
    @enderror
    <input type="submit" value="再設定メールを送信" class="c-button__submit u-horizontal-auto" style="width:auto;padding-left:24px;padding-right:24px;" />
    <p class="p-auth-link"><a href="{{ route('manage.login') }}">ログイン画面に戻る</a></p>
  </form>
</div>
@endsection
@include('manage.auth.partials.auth-style')
