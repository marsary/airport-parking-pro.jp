<!-- B-1-0 ログイン -->
@extends('layouts.member.app')

@section('content')
<div class="l-container__user-login">
    <form action="{{ route('login') }}" method="POST" class="">
      @csrf
      <div>
        <label for="user_name">メールアドレスまたは携帯電話番号</label>
        <input type="text" name="user_name" class="u-w-full-wide" />
      </div>
      <div class="u-mb2">
        <label for="password">パスワード</label>
        <input type="password" name="password" class="u-w-full-wide" />
      </div>
      @error('name')
        <p style="color:red">{{ $message }}</p>
      @enderror
      <p class="text-center u-font--14 u-mb3">パスワードを忘れた方は<a href="{{route('password.request')}}" class="c-link-text">こちら</a>をクリック​</p>
      <input type="submit" value="ログイン" class="c-button__submit--yellow u-w240 u-mb2 u-horizontal-auto" />
      {{--  <input type="submit" value="お得な新規会員登録​" class="c-button__submit--yellow u-w240 u-horizontal-auto" />  --}}
      @if (\Auth::guard('web')->check())
        <a href="{{route('reserves.entry_info', ['register_user' => 1])}}" id="skip_registration" class="c-button__submit--yellow u-w240 u-mb2 u-horizontal-auto">会員登録して予約する​</a>
        <a href="{{route('reserves.entry_info')}}" id="skip_registration" class="c-button__submit--green u-w240 u-horizontal-auto">会員登録なしで予約する​</a>
      @else
        <a href="{{route('reserves.entry_date', ['register_user' => 1])}}" id="skip_registration" class="c-button__submit--yellow u-w240 u-mb2 u-horizontal-auto">会員登録して予約する​</a>
        <a href="{{route('reserves.entry_date')}}" id="skip_registration" class="c-button__submit--green u-w240 u-horizontal-auto">会員登録なしで予約する​</a>
      @endif
    </form>
  </div>
@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
  #skip_registration {
    text-decoration: none;
  }
</style>
@endpush
