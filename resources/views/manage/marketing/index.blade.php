<!-- 営業支援ページ -->
@extends('layouts.manage.authenticated')

@section('content')

    <main class="l-wrap__main">

      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">営業支援</li>
      </ul>

      <div class="l-container__inner" style="position: relative;">
        <ul class="l-flex--start">
          <li class="u-border--right u-pr1 u-mr1"><a href="{{ route('manage.marketing.graph.inventory') }}" class="p-index__link">入出庫グラフ</a></li>
          <li><a href="{{ route('manage.marketing.reservation_graph.index') }}" class="p-index__link">予約実績グラフ</a></li>
        </ul>
      
      </div><!-- /.l-container__inner -->

    </main><!-- /.l-wrap__main -->
@endsection