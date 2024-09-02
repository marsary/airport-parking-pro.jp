<!-- L-1 入出庫グラフ -->
@extends('layouts.manage.authenticated')

@section('content')
  <main class="l-wrap__main">
    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list">入出庫グラフ</li>
      </li>
    </ul>

    <div class="l-container__inner">
      <!-- 表示期間選択 -->
      <div class="l-flex l-flex--sb l-flex--item-end l-grid--gap2 u-mb2">
        <!-- 月別 週別 時間別 任意の期間 button-->
        <div class="l-grid--col4-auto l-grid--gap05 u-mb1">
          <button id="monthlyChartBtn" class="c-button__apply u-w90">月別</button>
          <button id="weeklyChartBtn" class="c-button__apply u-w90">週別</button>
          <button id="dailyChartBtn" class="c-button__apply u-w-auto">時間別</button>
          <button id="manualChartBtn" class="c-button__apply u-w-auto">任意の期間</button>
        </div>
        <div>
          <div>
            <label for="start">表示期間</label>
            <input type="date" name="start" id="start_date" class="u-w210">
            <span>～</span>
            <input type="date" name="end" id="end_date" class="u-w210">
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
	  <div class="c-graph__header">
        <div class="l-flex--sb c-graph__header-top">
          <div id="chart_title" class="u-font--bold c-graph__selected-month">2024.01</div>
            <!-- checkedで選択・未選択の切替　デフォルトで選択した状態にしたければ"checked"つける -->
          <ul class="l-flex u-font--12 palt c-graph__select-button --performance">
              <li>
                      比較期間を表示<label class="c-button-toggle">
                      <input id="compare_periods" class="c-button-toggle__input" type="checkbox" role="switch">
                      <span class="c-button-toggle__slider"></span>
                  </label>
              </li>
              <li>入庫<label class="c-button-toggle">
                      <input id="load" class="c-button-toggle__input" type="checkbox" checked role="switch">
                      <span class="c-button-toggle__slider"></span>
                  </label></li>
              <li>出庫<label class="c-button-toggle">
                      <input id="unload" class="c-button-toggle__input" type="checkbox" checked role="switch">
                      <span class="c-button-toggle__slider"></span>
                  </label></li>
              <li>MAX在庫<label class="c-button-toggle">
                      <input id="max_stock" class="c-button-toggle__input" type="checkbox" checked role="switch">
                      <span class="c-button-toggle__slider"></span>
                  </label></li>
              <li>おわり在庫<label class="c-button-toggle">
                      <input id="ending_stock" class="c-button-toggle__input" type="checkbox" checked role="switch">
                      <span class="c-button-toggle__slider"></span>
                  </label></li>
          </ul>
        </div>
        </div>
        <div id="chartReport">
          <canvas id="myChart"></canvas>
          {{--  <img src="../images/dummy/gulp_ship.png" width="100%" height="auto" alt="">  --}}
        </div>
          <div id="prevBtn" class="c-button__prev absolute-VerticalCenter"></div>
          <div id="nextBtn" class="c-button__next absolute-VerticalCenter--right"></div>
        </div>
      <!-- グラフ -->

    </div>
  </main><!-- /.l-container__main -->
@endsection
@push("scripts")
<script src="{{asset('js/chartjs/chart.umd.js')}}" defer></script>
<script src="{{asset('js/pages/manage/graph/graph_common.js')}}" defer></script>
<script src="{{asset('js/pages/manage/graph/inventory.js')}}" defer></script>
<script>
</script>
@endpush
@push('css')
<style>
 #myChart {
    width: 100%;
 }
 #chartReport {
    width: 100%;
    margin-top: 17px;
 }
</style>
@endpush
