@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">入出庫予約実績表
    </li>
  </ul>

  <div class="l-container__inner">

    <!-- カレンダー -->
    <!-- ページネーション -->
    <div class="c-pager__pagination-container">
      <div class="c-pager__year-list-wrapper">
        <div class="c-pager__year-list" id="yearList">
          {{--  <div class="c-pager__year-item">2021</div>
          <div class="c-pager__year-item">2022</div>
          <div class="c-pager__year-item">2023</div>
          <div class="c-pager__year-item">2024</div>
          <div class="c-pager__year-item --selected">2025</div>
          <div class="c-pager__year-item">2026</div>
          <div class="c-pager__year-item">2027</div>
          <div class="c-pager__year-item">2028</div>
          <div class="c-pager__year-item">2029</div>
          <div class="c-pager__year-item">2030</div>  --}}
          @foreach ($yearList as $year)
            <div class="c-pager__year-item  {{ $year == (\Carbon\Carbon::today()->year) ? '--selected' : '' }}">{{$year}}</div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- カレンダー本体 -->
    <div class="p-reserve__wrap">
        <div>
            <div class="c-title__calendar" id="left_calendar1_title"></div>
        </div>
        <div class="calendar_section">
            <div class="prev_button">
              <button type="button" class="c-pager__button c-button__prev" id="cal1_prev"></button>
            </div>
            <div id='calendar1'></div>
            <div class="next_button">
              <button type="button" class="c-pager__button c-button__next" id="cal1_next"></button>
            </div>
        </div>
    </div>
  </div><!-- ./l-container__inner -->
</main>
</div>
@endsection
@push("scripts")
<script src="{{ asset('js/index.global.min.js') }}"></script>
<script src="{{ asset('js/reservation_result.js') }}"></script>
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

  /* イベントのスタイル */
  .fc .fc-daygrid-event {
      background-color: transparent !important;
      color: black !important;
      border: none !important;
      text-align: left;
      padding-left: 20%;
  }
  .fc-h-event .fc-event-main {
      color: black !important;
  }

</style>
@endpush
