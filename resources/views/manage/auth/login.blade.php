@extends('layouts.manage.app')

@section('content')
<div class="l-container__admin">
  <figure class="u-mb1"><img src="{{asset('images/svg/logo.svg')}}" alt="ロゴ" width="255px" height="auto" class="u-horizontal-auto is-block"></figure>
  <div class="c-form__admin--title">ユーザーログイン</div>
  <form action="{{route('manage.login')}}" method="POST" class="c-form__admin">
    @csrf
    <div>
      <label for="name">ユーザーID</label>
      <input type="text" name="name" class="c-input__admin" />
    </div>
    <div class="u-mb2">
      <label for="password">パスワード</label>
      <input type="password" name="password" class="c-input__admin" />
    </div>
    @error('name')
      <p style="color:red">{{ $message }}</p>
    @enderror
    <input type="submit" value="ログイン" class="c-button__submit u-horizontal-auto" />
  </form>
</div>


@endsection
@push("scripts")
<script>

</script>
@endpush
@push('css')
<style>
</style>
@endpush
