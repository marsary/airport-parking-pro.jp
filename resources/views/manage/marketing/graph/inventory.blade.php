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
          <button class="c-button__apply u-w90">月別</button>
          <button class="c-button__apply u-w90">週別</button>
          <button class="c-button__apply u-w-auto">時間別</button>
          <button class="c-button__apply u-w-auto">任意の期間</button>
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
				<div class="c-graph__header">
					<div class="l-flex--sb c-graph__header-top">
						<div class="u-font--bold c-graph__selected-month">2024.01</div>
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

				<div><img src="../images/dummy/gulp_ship.png" width="100%" height="auto" alt=""></div>
				<div class="c-button__prev absolute-VerticalCenter"></div>
				<div class="c-button__next absolute-VerticalCenter--right"></div>
			</div>
      <!-- グラフ -->

    </div>
  </main><!-- /.l-container__main -->
@endsection
