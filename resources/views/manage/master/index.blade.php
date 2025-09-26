<!-- マスタ登録 -->
<!-- register.php -->
<!-- G-0 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">マスタ設定一覧</li>
    </ul>

    <div class="l-container__inner">
        <div class="l-grid--col5-1fr l-grid--rgap2 l-grid--cgap1 u-font--white">
            <a href="{{route('manage.master.good_categories.index')}}" class="c-button--yellow c-link-no-border">商品カテゴリーマスタ</a>
            <a href="{{route('manage.master.goods.index')}}" class="c-button--yellow c-link-no-border">商品マスタ</a>
            <a href="{{route('manage.master.agencies.index')}}" class="c-button--yellow c-link-no-border">代理店マスタ</a>
            <a href="{{route('manage.master.coupons.index')}}" class="c-button--yellow c-link-no-border">クーポンマスタ</a>
            <a href="{{route('manage.master.prices.index')}}" class="c-button--yellow c-link-no-border">金額マスタ</a>
            <a href="{{route('manage.master.agency_prices.index')}}" class="c-button--yellow c-link-no-border">代理店金額マスタ</a>
            <a href="{{route('manage.master.load_unload_full_limit_settings.index')}}" class="c-button--yellow c-link-no-border">上限設定マスタ</a>
        </div>
    </div><!-- ./l-container__inner -->
</main>

</div>
@endsection
