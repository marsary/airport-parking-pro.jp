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
          <button id="monthlyChartBtn" class="c-button__submit u-w90">月別</button>
          <button id="weeklyChartBtn" class="c-button__submit u-w90">週別</button>
          <button id="dailyChartBtn" class="c-button__submit u-w-auto">時間別</button>
          <button id="manualChartBtn" class="c-button__submit u-w-auto">任意の期間</button>
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
            <input disabled="disabled" type="date" name="compare" id="compare_start" class="u-w210">
            <span>～</span>
            <input disabled="disabled" type="date" name="compare" id="compare_end" class="u-w210">
          </div>
        </div>
      </div>

      <!-- グラフ -->
      <div class="relative c-graph__wrap">
      <div class="c-graph__header">
					<div class="l-flex--sb c-graph__header-top">
						<!-- checkedで選択・未選択の切替　デフォルトで選択した状態にしたければ"checked"つける -->
						<ul class="l-flex u-font--12 palt c-graph__select-button --inventory">
							<li>
								電話予約（自）<label class="c-button-toggle">
								<input class="c-button-toggle__input" type="checkbox" checked role="switch">
								<span class="c-button-toggle__slider"></span>
								</label>
							</li>
							<li>電話予約（代）<label class="c-button-toggle">
								<input class="c-button-toggle__input" type="checkbox" checked role="switch">
								<span class="c-button-toggle__slider"></span>
								</label></li>
							<li>当日受付予約<label class="c-button-toggle">
								<input class="c-button-toggle__input" type="checkbox" checked role="switch">
								<span class="c-button-toggle__slider"></span>
								</label></li>
							<li>ネット予約（自）<label class="c-button-toggle">
								<input class="c-button-toggle__input" type="checkbox" checked role="switch">
								<span class="c-button-toggle__slider"></span>
								</label></li>
							<li>ネット予約（代）<label class="c-button-toggle">
								<input class="c-button-toggle__input" type="checkbox" checked role="switch">
								<span class="c-button-toggle__slider"></span>
								</label></li>
						</ul>
					</div>
					<div class="text-right">
						前年同月を表示
						<label class="c-button-toggle">
							<input class="c-button-toggle__input" type="checkbox" role="switch">
							<span class="c-button-toggle__slider"></span>
						</label>
					</div>
				</div>

        <div id="chart_title">タイトル</div>
        <div id="chartReport">
          <canvas id="myChart"></canvas>
          <img src="../images/dummy/gulp_rsv.png" width="100%" height="auto" alt="">
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
<script src="{{asset('js/pages/manage/graph/reservation.js')}}" defer></script>
<script>
</script>
@endpush
@push('css')
<style>
  .c-graph__wrap {
    display: flex;
    align-items: center;
  }
  .relative {
    position: relative;
  }
  #chart_title {
    position: absolute;
    top: 14px;
    left: 23px;
    font-size: 25px;
  }
  .absolute-VerticalCenter {
    position: absolute;
    left: -30px;
  }
  .absolute-VerticalCenter--right {
    position: absolute;
    right: -30px;
  }
 #myChart {
    width: 100%;
 }
 #chartReport {
    width: 100%;
    margin-top: 17px;
 }
 .prev_button {
    position: absolute;
    left: -30px;
  }
  .next_button {
    position: absolute;
    right: -30px;
  }
</style>
@endpush
