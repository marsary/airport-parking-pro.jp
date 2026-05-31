@extends('layouts.manage.app')

@section('content')
<section class="p-error-page">
  <div class="p-error-page__inner">
    <img src="{{ asset('images/logo/logo.png') }}" alt="Sunparking" class="p-error-page__logo">
    <h1 class="p-error-page__title">サーバーエラーが発生しました</h1>
    <p class="p-error-page__text">しばらく経ってから再試行してください</p>
    <div class="p-error-page__actions">
    <!-- リンクは必要に応じて変更 -->
      <a href="{{ url('/') }}" class="p-error-page__button p-error-page__button--primary">トップへ戻る</a>
    </div>
  </div>
</section>
@endsection