<!-- 営業支援ページ -->
@extends('layouts.manage.authenticated')

@section('content')

    <main class="l-wrap__main">

      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">営業支援</li>
      </ul>

      <div class="l-container__inner">
        <div class="l-grid--col5 l-grid--rgap2">
          <a href="{{ route('manage.marketing.graph.inventory') }}"  class="c-button__submit c-button--yellow c-link-no-border">入出庫グラフ</a>
          <a href="{{ route('manage.marketing.reservation_graph.index') }}"  class="c-button__submit c-button--yellow c-link-no-border">予約実績グラフ</a>
          <a href="/manage/ledger/reservation_result"  class="c-button__submit c-button--yellow c-link-no-border">予定実績表</a>
        </div>
      </div><!-- ./l-container__inner -->

    </main><!-- /.l-wrap__main -->
@endsection