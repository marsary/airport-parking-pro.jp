@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">入出庫上限・在庫設定</li>
  </ul>

  <div class="l-container__inner">

    <h2 class="c-title__lv2 u-mb2">入出庫上限登録</h2>

    <!-- 一括登録ボタン -->
    <button class="c-button__register l-grid--self-end u-font--normal" formaction="" onclick="openPeriodModal()"
      style="float:right;margin-bottom:10px;">期間登録</button>



    <!-- カレンダー -->
    <!-- ページネーション -->
    <div class="c-pager__pagination-container">
      <button class="c-pager__button c-button__prev" id="prevButton" onclick="prevPage()"></button>
      <div class="c-pager__year-list-wrapper">
        <div class="c-pager__year-list" id="yearList">
          <div class="c-pager__year-item">2015</div>
          <div class="c-pager__year-item">2016</div>
          <div class="c-pager__year-item">2017</div>
          <div class="c-pager__year-item">2018</div>
          <div class="c-pager__year-item">2019</div>
          <div class="c-pager__year-item">2020</div>
          <div class="c-pager__year-item">2021</div>
          <div class="c-pager__year-item">2022</div>
          <div class="c-pager__year-item">2023</div>
          <div class="c-pager__year-item --selected">2024</div>
        </div>
      </div>
      <button class="c-pager__button c-button__next" id="nextButton" onclick="nextPage()"></button>
    </div>

    <!-- カレンダー本体 -->
    <div class="p-reserve__wrap">
        <p class="text-center u-mb2 u-font--medium">pメッセージ</p>
        <div>
            <div class="c-title__calendar" id="left_calendar1_title"></div>
        </div>
        <div class="calendar_section">
            <div class="prev_button">
              <button type="button" class="c-pager__button c-button__prev" id="cal1_prev"></button>
            </div>
            <div id='calendar'></div>
            <div class="next_button">
              <button type="button" class="c-pager__button c-button__next" id="cal1_next"></button>
            </div>
        </div>
    </div>
  </div><!-- ./l-container__inner -->
</main>
<!-- 「期間登録」をクリックしたら出てくるmodal -->
<div id="modalAreaOption_Period_" class="l-modal">
  <!-- モーダルのinnerを記述   -->
  <div class="l-modal__inner l-modal--trash">
    <div class="l-modal__head">期間登録</div>
    <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
    <div class="l-modal__content">

      <form method="POST" action="?" class="l-grid--col2-1fr_160 l-grid--gap2 u-font--md l-grid--start">
        <!-- 開始日 -->
        <div class="l-grid--col2 l-grid--gap2">
          <div class="form-item">
            <label for="start_date">開始日</label>
            <input type="date" id="start_date" name="start_date" class="u-mb0 c-input u-w-full-wide" required="">
          </div>
          <div class="form-item">
            <label for="end_date">終了日</label>
            <input type="date" id="end_date" name="end_date" class="u-mb0 c-input u-w-full-wide" required="">
          </div>
        </div>

        <!-- 一括登録ボタン -->
        <button class="c-button__register l-grid--self-end u-font--normal" formaction="">登録</button>

        <div class="l-grid--col5 l-grid--gap1">
          <!-- 入庫上限 -->
          <div class="form-item">
            <label for="max_entry">入庫上限</label>
            <input type="text" id="max_entry" name="max_entry" class="c-input u-w-full-wide" required="">
          </div>
          <!-- 出庫上限 -->
          <div class="form-item">
            <label for="max_withdrawal">出庫上限</label>
            <input type="text" id="max_withdrawal" name="max_withdrawal" class="c-input u-w-full-wide" required="">
          </div>
          <div class="form-item">
            <label for="end_stock">おわり在庫</label>
            <input type="text" id="end_stock" name="end_stock" class="c-input u-w-full-wide" required="">
          </div>
          <div class="form-item">
            <label for="limit_per_15min">15分あたりの上限</label>
            <input type="text" id="limit_per_15min" name="limit_per_15min" class="c-input u-w-full-wide" required="">
          </div>
        </div>
        <div class="l-grid__right-submitButton--button c-button__csv--upload u-mb1 u-pt1-half">

          <button type="button" class="c-button__load upload">クリア</button>
        </div>
      </form>
    </div><!-- ./l-modal__content -->

    <!-- 編集の場合のデータ削除ボタン -->
  </div><!-- ./l-modal inner -->
</div>

<!-- 「日付」をクリックしたら出てくるmodal -->
<div id="modalAreaOption_edit_" class="l-modal">
  <!-- モーダルのinnerを記述   -->
  <div class="l-modal__inner l-modal--trash">
    <div class="l-modal__head">編集</div>
    <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
    <div class="l-modal__content">

      <form method="POST" action="?" class="l-grid--col2-1fr_160 l-grid--gap2 u-font--md l-grid--start">
        <!-- 開始日 -->
        <div class="l-grid--col2 l-grid--gap2">
          <div class="form-item">
            <label for="start_date">開始日</label>
            <input type="date" id="start_date" name="start_date" class="u-mb0 c-input u-w-full-wide" required="">
          </div>
          <div class="form-item">
            <label for="end_date">終了日</label>
            <input type="date" id="end_date" name="end_date" class="u-mb0 c-input u-w-full-wide" required="">
          </div>
        </div>

        <!-- 一括登録ボタン -->
        <button class="c-button__register l-grid--self-end u-font--normal" formaction="">登録</button>

        <div class="l-grid--col5 l-grid--gap1">
          <!-- 入庫上限 -->
          <div class="form-item">
            <label for="max_entry">入庫上限</label>
            <input type="text" id="max_entry" name="max_entry" class="c-input u-w-full-wide" required="">
          </div>
          <!-- 出庫上限 -->
          <div class="form-item">
            <label for="max_withdrawal">出庫上限</label>
            <input type="text" id="max_withdrawal" name="max_withdrawal" class="c-input u-w-full-wide" required="">
          </div>
          <div class="form-item">
            <label for="end_stock">おわり在庫</label>
            <input type="text" id="end_stock" name="end_stock" class="c-input u-w-full-wide" required="">
          </div>
          <div class="form-item">
            <label for="limit_per_15min">15分あたりの上限</label>
            <input type="text" id="limit_per_15min" name="limit_per_15min" class="c-input u-w-full-wide" required="">
          </div>
        </div>
        <div class="l-grid__right-submitButton--button c-button__csv--upload u-mb1 u-pt1-half">

          <button type="button" class="c-button__load upload">クリア</button>
        </div>
      </form>
    </div><!-- ./l-modal__content -->

    <!-- 編集の場合のデータ削除ボタン -->
    <form id="" action="" method="post">
      <input type="hidden" name="_token" value="qIGxKKiuIH8lzCDJTrm9yKTGgauEe99Kq1K8DMGf" autocomplete="off">
      <input type="hidden" name="_method" value="DELETE">
    </form>
    <div class="l-modal__trashButton" onclick="">
      <img src="https://system.airport-parking-pro.jp/images/svg/trash.svg" alt="ゴミ箱" width="100%"
        class="l-modal--trashButton">
    </div>
  </div><!-- ./l-modal inner -->
</div>
@endsection
@push("scripts")
<script src="{{ asset('js/index.global.min.js') }}"></script>
@endpush
@push('css')
<style>
    .calendar_section {
        position: relative;
        display: flex;
        align-items: center;
    }

    .prev_button {
        position: absolute;
        left: -30px;
    }

    .next_button {
        position: absolute;
        right: -30px;
    }

  {{--  単数月用  --}}

  {{--  .fc .fc-scrollgrid {
    border-left-width: 0px;
  }

  table.fc-col-header {
    padding-top: 8px;
    border-spacing: 4px;
    border-collapse: separate;
  }

  table.fc-col-header tr {
    height: 25px;
  }

  table.fc-scrollgrid-sync-table {
    border-spacing: 4px;
    border-collapse: separate;
  }  --}}

  {{--  複数月用  --}}
  .fc-multimonth-title {
    display: none;
  }

  div.fc-multimonth-month {
    padding: 8px 10px 8px !important;
  }

  table.fc-multimonth-daygrid-table {
    border-spacing: 4px;
    border-collapse: separate;
  }

  table.fc-multimonth-header-table {
    border-spacing: 4px;
    border-collapse: separate;
  }

  table.fc-multimonth-header-table tr {
    height: 25px;
  }

  .fc-day-disabled {
      border: none !important;
      background-color: #fff !important;
  }

  .fc-daygrid-day {
      background-color: #eee;
      text-align: center;
      height: 90px !important;
  }

  .fc-day.fc-day-today.fc-daygrid-day:not(.day_full),
  .fc-day.fc-day-future.fc-daygrid-day:not(.day_full) {
      cursor: pointer;
  }

  .fc-daygrid-day-events {
      margin: 5px;
  }

  .fc-col-header-cell.fc-day {
      background-color: black;
      color: #fff;
  }

  .fc-col-header-cell.fc-day.fc-day-sat {
      background-color: #1f6aaa;
  }

  .fc-col-header-cell.fc-day.fc-day-sun {
      background-color: #d82528;
  }

  a.fc-col-header-cell-cushion {
      padding: 10px !important;
  }

  .fc-daygrid-day-events::after,
  .fc-daygrid-day-events::before,
  .fc-daygrid-event-harness::after,
  .fc-daygrid-event-harness::before {
      content: none;
  }

  a.fc-daygrid-day-number {
      display: block;
      width: 100%;
  }

  .fc .fc-daygrid-day-top {
      margin-top: 3px;
  }
</style>
@endpush
