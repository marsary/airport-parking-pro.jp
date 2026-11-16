@extends('layouts.manage.app')

@section('content')
<div style="text-align: right; padding-right: 50px;">事業再構築　R2128E00068000</div>
<div class="l-container__admin">
  <figure class="u-mb1"><img src="{{asset('images/logo/logo.png')}}" alt="ロゴ" width="255px" height="auto" class="u-horizontal-auto is-block"></figure>
  <div class="c-form__admin--title">パスワード再設定</div>
  <form action="{{route('manage.password.store')}}" method="POST" class="c-form__admin">
    @csrf
    @if (session('session_expired'))
      <p class="p-auth-expired">{{ session('session_expired') }}</p>
    @endif
    <input type="hidden" name="token" value="{{ $token }}">
    <div>
      <label for="email">ユーザーID（メールアドレス）</label>
      <input type="email" name="email" id="email" value="{{ old('email', $email) }}" class="c-input__admin" autocomplete="username" required />
    </div>
    <div>
      <label for="password">新しいパスワード</label>
      <input type="password" name="password" id="password" class="c-input__admin" autocomplete="new-password" required />
    </div>
    <div class="u-mb2">
      <label for="password_confirmation">新しいパスワード（確認）</label>
      <input type="password" name="password_confirmation" id="password_confirmation" class="c-input__admin" autocomplete="new-password" required />
    </div>
    <p class="p-auth-note">8文字以上で、英字と数字を両方含めてください。</p>
    @if ($errors->any())
      @foreach ($errors->all() as $message)
        <p style="color:red">{{ $message }}</p>
      @endforeach
    @endif
    <input type="submit" value="パスワードを再設定" class="c-button__submit u-horizontal-auto" style="width:auto;padding-left:24px;padding-right:24px;" />
    <p class="p-auth-link"><a href="{{ route('manage.login') }}">ログイン画面に戻る</a></p>
  </form>
</div>
@endsection
@include('manage.auth.partials.auth-style')
