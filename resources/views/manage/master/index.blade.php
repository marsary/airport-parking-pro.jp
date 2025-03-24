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
            <a href="/manage/master/good_categories" class="c-button--yellow c-link-no-border">商品カテゴリーマスタ</a>
            <a href="/manage/master/goods" class="c-button--yellow c-link-no-border">商品マスタ</a>
            <a href="/manage/master/agencies" class="c-button--yellow c-link-no-border">代理店マスタ</a>
            <a href="/manage/master/coupons" class="c-button--yellow c-link-no-border">クーポンマスタ</a>
        </div>
    </div><!-- ./l-container__inner -->
</main>

</div>
@endsection