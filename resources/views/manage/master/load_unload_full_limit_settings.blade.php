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
          @foreach ($yearList as $year)
            <div class="c-pager__year-item  {{ $year == ($persistedYear ?? \Carbon\Carbon::today()->year) ? '--selected' : '' }}">{{$year}}</div>
          @endforeach
        </div>
      </div>
      <button class="c-pager__button c-button__next" id="nextButton" onclick="nextPage()"></button>
    </div>

    <!-- カレンダー本体 -->
    <div class="calendar-container">
      <div class="prev_button">
        <button type="button" class="c-pager__button c-button__prev" id="cal1_prev"></button>
      </div>
      <!-- 1ヶ月目のカレンダー -->
      <div class="calendar1-headers">
        <div class="month-header">
          <div class="month-header-year" id="cal1Year">
            2024
          </div>
          <div class="month-header-month" id="cal1Month">
            1
          </div>
        </div>
        <div class="stock-container">
          @for ($i = 0; $i < 5; $i++)
            <div class="stock-header-container">
              <div class="stock-headers">
                <div class="stock-header-item">入庫上限</div>
                <div class="stock-header-item">出庫上限</div>
                <div class="stock-header-item">おわり在庫</div>
                <div class="stock-header-item">15分あたり上限</div>
              </div>
            </div>
          @endfor
        </div>
      </div>
      <div class="month-calendar">
        <div id="calendar1"></div>
      </div>

      <!-- 2ヶ月目のカレンダー -->
      <div class="calendar2-headers">
        <div class="month-header">
          <div class="month-header-year" id="cal2Year">
            2024
          </div>
          <div class="month-header-month" id="cal2Month">
            2
          </div>
        </div>
        <div class="stock-container">
          @for ($i = 0; $i < 5; $i++)
            <div class="stock-header-container">
              <div class="stock-headers">
                <div class="stock-header-item">MAX在庫</div>
                <div class="stock-header-item">入庫上限</div>
                <div class="stock-header-item">出庫上限</div>
                <div class="stock-header-item">おわり在庫</div>
                <div class="stock-header-item">15分あたり上限</div>
              </div>
            </div>
          @endfor
        </div>
      </div>
      <div class="month-calendar">
        <div id="calendar2"></div>
      </div>
      <div class="next_button">
        <button type="button" class="c-pager__button c-button__next" id="cal1_next"></button>
      </div>
    </div>
  </div><!-- ./l-container__inner -->
</main>
<!-- 「期間登録」をクリックしたら出てくるmodal -->
<div id="modalAreaOption_Period_" class="l-modal">
  <!-- モーダルのinnerを記述   -->
  <div class="l-modal__inner l-modal--trash">
    <div class="l-modal__head">期間登録</div>
    <div class="l-modal__close modal_optionClose" onclick="closePeriodModal()">×</div>
    <div class="l-modal__content">

      <form method="POST" action="{{route('manage.master.load_unload_full_limit_settings.store_all')}}" class="l-grid--col2-1fr_160 l-grid--gap2 u-font--md l-grid--start">
        @csrf
        <!-- 開始日 -->
        <input type="hidden" name="active_calendar_year" id="period_active_calendar_year">
        <input type="hidden" name="active_calendar_month1" id="period_active_calendar_month1">
        <div class="l-grid--col2 l-grid--gap2">
          <div class="form-item">
            <label for="start_date">開始日</label>
            <input type="date" id="start_date" name="start_date" class="u-mb0 c-input u-w-full-wide" value="{{ old('start_date') }}" required="">
          </div>
          <div class="form-item">
            <label for="end_date">終了日</label>
            <input type="date" id="end_date" name="end_date" class="u-mb0 c-input u-w-full-wide" value="{{ old('end_date') }}" required="">
          </div>
        </div>

        <!-- 一括登録ボタン -->
        <button class="c-button__register l-grid--self-end u-font--normal">登録</button>

        <div class="l-grid--col5 l-grid--gap1">
          <!-- 入庫上限 -->
          <div class="form-item">
            <label for="load_limit">入庫上限</label>
            <input type="text" id="load_limit" name="load_limit" class="c-input u-w-full-wide" value="{{ old('load_limit') }}" required="">
          </div>
          <!-- 出庫上限 -->
          <div class="form-item">
            <label for="unload_limit">出庫上限</label>
            <input type="text" id="unload_limit" name="unload_limit" class="c-input u-w-full-wide" value="{{ old('unload_limit') }}" required="">
          </div>
          <div class="form-item">
            <label for="at_closing_time">おわり在庫</label>
            <input type="text" id="at_closing_time" name="at_closing_time" class="c-input u-w-full-wide" value="{{ old('at_closing_time') }}" required="">
          </div>
          <div class="form-item">
            <label for="per_fifteen_munites">15分あたりの上限</label>
            <input type="text" id="per_fifteen_munites" name="per_fifteen_munites" class="c-input u-w-full-wide" value="{{ old('per_fifteen_munites') }}" required="">
          </div>
        </div>
        <div class="l-grid__right-submitButton--button c-button__csv--upload u-mb1 u-pt1-half">

          <button type="reset" class="c-button__load upload">クリア</button>
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
    <div class="l-modal__close modal_optionClose" onclick="closeEditModal()">×</div>
    <div class="l-modal__content">

      <form  id="editForm" method="POST" action="{{route('manage.master.load_unload_full_limit_settings.update_by_date')}}" class="l-grid--col2-1fr_160 l-grid--gap2 u-font--md l-grid--start">
        @csrf
        @method('PUT')
        <input type="hidden" name="active_calendar_year" id="edit_active_calendar_year">
        <input type="hidden" name="active_calendar_month1" id="edit_active_calendar_month1">
        <!-- 対象日 -->
        <div class="l-grid--col2 l-grid--gap2">
          <div class="form-item">
            <input type="hidden" id="edit_target_date" name="edit_target_date" value="{{ old('edit_target_date') }}">
          </div>
        </div>

        <!-- 登録ボタン -->
        <button type="submit" class="c-button__register l-grid--self-end u-font--normal" >登録</button>

        <div class="l-grid--col5 l-grid--gap1">
          <!-- 入庫上限 -->
          <div class="form-item">
            <label for="edit_load_limit">入庫上限</label>
            <input type="text" id="edit_load_limit" name="edit_load_limit" class="c-input u-w-full-wide" value="{{ old('edit_load_limit') }}" required="">
          </div>
          <!-- 出庫上限 -->
          <div class="form-item">
            <label for="edit_unload_limit">出庫上限</label>
            <input type="text" id="edit_unload_limit" name="edit_unload_limit" class="c-input u-w-full-wide" value="{{ old('edit_unload_limit') }}" required="">
          </div>
          <div class="form-item">
            <label for="edit_at_closing_time">おわり在庫</label>
            <input type="text" id="edit_at_closing_time" name="edit_at_closing_time" class="c-input u-w-full-wide" value="{{ old('edit_at_closing_time') }}" required="">
          </div>
          <div class="form-item">
            <label for="edit_per_fifteen_munites">15分あたりの上限</label>
            <input type="text" id="edit_per_fifteen_munites" name="edit_per_fifteen_munites" class="c-input u-w-full-wide" value="{{ old('edit_per_fifteen_munites') }}" required="">
          </div>
        </div>
        <div class="l-grid__right-submitButton--button c-button__csv--upload u-mb1 u-pt1-half">

          <button type="reset" class="c-button__load upload">クリア</button>
        </div>
      </form>
    </div><!-- ./l-modal__content -->

    <!-- 編集の場合のデータ削除ボタン -->
    <form id="deleteForm" action="{{route('manage.master.load_unload_full_limit_settings.delete_by_date')}}" method="post">
      @csrf
      @method('DELETE')
      <input type="hidden" name="active_calendar_year" id="delete_active_calendar_year">
      <input type="hidden" name="active_calendar_month1" id="delete_active_calendar_month1">
      <input type="hidden" id="delete_target_date" name="delete_target_date">
      <div class="l-modal__trashButton" onclick="handleDelete()">
        <img src="https://system.airport-parking-pro.jp/images/svg/trash.svg" alt="ゴミ箱" width="100%"
          class="l-modal--trashButton">
      </div>
    </form>
  </div><!-- ./l-modal inner -->
</div>
@endsection
@push("scripts")
<script src="{{ asset('js/index.global.min.js') }}"></script>
<script>

  const calendar1StockData = new Map();
  const calendar2StockData = new Map();


  function handleDelete() {
    if(confirm("本当に削除しますか？")) {
      const deleteForm = document.getElementById('deleteForm');
      // document.getElementById('delete_active_calendar_year').value = currentDisplayedYear;
      // document.getElementById('delete_active_calendar_month1').value = currentDisplayedMonth1;
      deleteForm.submit();
    }
  }

function openPeriodModal() {
    const modal = document.getElementById('modalAreaOption_Period_');
    const inputs = modal.querySelectorAll('input[type="text"], input[type="date"]');

    inputs.forEach(input => {
        input.value = '';
    });

    modal.classList.add('is-active');
}
  function openEditModal(selectedDate, calendarStockData) {
    const editModal = document.getElementById(`modalAreaOption_edit_`);

    const dateStr = selectedDate.toISODate();
    const stockEntry = calendarStockData.get(dateStr);

    document.getElementById('edit_target_date').value = dateStr;
    document.getElementById('delete_target_date').value = dateStr;

    editModal.classList.add('is-active');
  }

  document.addEventListener('DOMContentLoaded', function() {
    const BASE_PATH = document.getElementById('base_path').value;
    let calendar1Instance = null;
    let calendar2Instance = null;

    // カレンダー1の初期化 (1月)
    const calendar1El = document.getElementById('calendar1');
    if(calendar1El) {
      const calendar1Instance = new FullCalendar.Calendar(calendar1El, {
        initialView: 'dayGridMonth',
        initialDate:  initialDateCal1,
        headerToolbar: false,
        firstDay:1,
        fixedWeekCount: false,
        showNonCurrentDates: false,
        headerToolbar: false,
        navLinks: false,
        editable: false,
        businessHours: false,
        displayEventTime: false,
        eventDisplay: 'block',
        locale: 'ja',
        contentHeight: 'auto',
        dayCellContent: function (e) {
            return e.date.getDate();
        },
        eventContent: function (arg) {
          return {
            html: arg.event.title // HTML をそのまま出力
          };
        },
        events:
        function(info, successCallback, failureCallback) {
          url = BASE_PATH +  '/manage/master/load_unload_full_limit_settings/calendar';

          const response = apiRequest.get(url, {
            start: info.startStr,
            end: info.endStr,
          })

          response.then(data => {
            const eventData = [];
            // console.log(data);
            data.forEach(row => {
              calendar1StockData.set(row.start, row);

              const eventTitle = `
              <div class="stock-item"><span>${row.stock.load_limit ?? ''}</span></div>
              <div class="stock-item"><span>${row.stock.unload_limit ?? ''}</span></div>
              <div class="stock-item"><span>${row.stock.at_closing_time ?? ''}</span></div>
              <div class="stock-item"><span>${row.stock.per_fifteen_munites ?? ''}</span></div>
              `;

              eventData.push({
                  id : row.start,
                  title :eventTitle,
                  start : row.start,
                  end : row.end,
                  allDay : true,
              });

            })


            successCallback(
              eventData
            )
          }).catch(failureCallback);
          console.log(calendar1StockData);
        },
        eventDidMount: function(e) {
          let el = e.el;
          if(e.event.toPlainObject().title != '') {
            //イベントが表示される場所の親をたどって各日の枠にたどり着いたらclassを追加
            el.closest('.fc-daygrid-day').classList.add('active');
          }
        },
        dateClick: function(info) {
        if(!info.dayEl.classList.contains("active")) {
          return;
        }
        // alert(info.date);
        const selectedDate = luxon.DateTime.fromJSDate(info.date);
        openEditModal(selectedDate, calendar1StockData);
        },
      });
      calendar1Instance.render();
    }

    // カレンダー2の初期化 (2月)
    const calendar2El = document.getElementById('calendar2');
    if (calendar2El) {
      const calendar2Instance = new FullCalendar.Calendar(calendar2El, {
        initialView: 'dayGridMonth',
        initialDate: initialDateCal2,
        headerToolbar: false,
        firstDay:1,
        fixedWeekCount: false,
        showNonCurrentDates: false,
        headerToolbar: false,
        navLinks: false,
        editable: false,
        businessHours: false,
        displayEventTime: false,
        eventDisplay: 'block',
        locale: 'ja',
        contentHeight: 'auto',
        dayCellContent: function (e) {
            return e.date.getDate();
        },
        eventContent: function (arg) {
          return {
            html: arg.event.title // HTML をそのまま出力
          };
        },
        events:
        function(info, successCallback, failureCallback) {
          url = BASE_PATH +  '/manage/master/load_unload_full_limit_settings/calendar';

          const response = apiRequest.get(url, {
            start: info.startStr,
            end: info.endStr,
          })

          response.then(data => {
            const eventData = [];
            // console.log(data);
            data.forEach(row => {
              calendar2StockData.set(row.start, row);

              const eventTitle = `
              <div class="stock-item"><span>${row.stock.load_limit ?? ''}</span></div>
              <div class="stock-item"><span>${row.stock.unload_limit ?? ''}</span></div>
              <div class="stock-item"><span>${row.stock.at_closing_time ?? ''}</span></div>
              <div class="stock-item"><span>${row.stock.per_fifteen_munites ?? ''}</span></div>
              `;

              eventData.push({
                  id : row.start,
                  title :eventTitle,
                  start : row.start,
                  end : row.end,
                  allDay : true,
              });

            })


            successCallback(
              eventData
            )
          }).catch(failureCallback);
        },
        eventDidMount: function(e) {
          let el = e.el;
          if(e.event.toPlainObject().title != '') {
            //イベントが表示される場所の親をたどって各日の枠にたどり着いたらclassを追加
            el.closest('.fc-daygrid-day').classList.add('active');
          }
        },
        dateClick: function(info) {
          if(!info.dayEl.classList.contains("active")) {
            return;
          }
          // alert(info.date);
          const selectedDate = luxon.DateTime.fromJSDate(info.date);
          openEditModal(selectedDate, calendar2StockData);
        },
      });
      calendar2Instance.render();
    }


  });
</script>
@endpush
@push('css')
<style>
  .l-container__inner {
    width:100% !important;
  }
  .calendar-container {
    position: relative;
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
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

  .calendar_section {
      position: relative;
      display: flex;
      align-items: center;
  }

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
