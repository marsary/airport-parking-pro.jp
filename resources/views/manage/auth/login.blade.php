@extends('layouts.manage.app')

@section('content')
<div style="text-align: right; padding-right: 50px;">事業再構築　R2128E00068000</div>
<div class="l-container__admin">
  <figure class="u-mb1"><img src="{{asset('images/logo/logo.png')}}" alt="ロゴ" width="255px" height="auto" class="u-horizontal-auto is-block"></figure>
  <div class="c-form__admin--title">ユーザーログイン</div>
  <form action="{{route('manage.login')}}" method="POST" class="c-form__admin">
    @csrf
    @if (session('session_expired'))
      <p class="p-auth-expired">{{ session('session_expired') }}</p>
    @endif
    @if (session('status'))
      <p class="p-auth-status">{{ session('status') }}</p>
    @endif
    <div>
      <label for="email">ユーザーID</label>
      <input type="text" name="email" value="{{ old('email') }}" class="c-input__admin" />
    </div>
    <div class="u-mb2">
      <label for="password">パスワード</label>
      <input type="password" name="password" class="c-input__admin" />
    </div>
    @error('email')
      <p style="color:red">{{ $message }}</p>
    @enderror
    <input type="submit" value="ログイン" class="c-button__submit u-horizontal-auto" />
    <p class="p-auth-link"><a href="{{ route('manage.password.request') }}">パスワードを忘れた方</a></p>
  </form>
</div>


@endsection
@push("scripts")
<script>

</script>
@endpush
@include('manage.auth.partials.auth-style')
