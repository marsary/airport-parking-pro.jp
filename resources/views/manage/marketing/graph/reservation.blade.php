<!-- 予約実績グラフ　L-2 -->
<!-- L-1 入出庫グラフ -->
@extends('layouts.manage.authenticated')

@section('content')
  <main class="l-wrap__main">
    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list">予約実績グラフ</li>
      </li>
    </ul>

    <div class="l-container__inner">
      <!-- 表示期間選択 -->
      <div class="l-flex l-flex--sb l-flex--item-end l-grid--gap2 u-mb2">
        <!-- 月別 週別 時間別 任意の期間 button-->
        <div class="l-grid--col4-auto l-grid--gap05 u-mb1">
          <button class="c-button__submit u-w90">月別</button>
          <button class="c-button__submit u-w90">週別</button>
          <button class="c-button__submit u-w-auto">時間別</button>
          <button class="c-button__submit u-w-auto">任意の期間</button>
        </div>
        <div>
          <div>
            <label for="start">表示期間</label>
            <input type="date" name="start" id="start" class="u-w210">
            <span>～</span>
            <input type="date" name="end" id="end" class="u-w210">
          </div>
          <!-- 比較する期間 -->
          <div>
            <label for="compare">比較する期間</label>
            <input type="date" name="compare" id="compare_start" class="u-w210">
            <span>～</span>
            <input type="date" name="compare" id="compare_end" class="u-w210">
          </div>
        </div>
      </div>

      <!-- グラフ -->
      <div class="relative c-graph__wrap">
        <div class="c-button__prev absolute-VerticalCenter"></div>
        <div class="c-button__next absolute-VerticalCenter--right"></div>
      </div>
      <!-- グラフ -->

    </div>
  </main><!-- /.l-container__main -->
@endsection