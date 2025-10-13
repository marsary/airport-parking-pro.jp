@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">入出庫上限・在庫設定</li>
  </ul>


  @include('include.messages.errors')

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
          </div>
          <div class="month-header-month" id="cal1Month">
          </div>
        </div>
        <div class="stock-container">
          @for ($i = 0; $i < 6; $i++)
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
          </div>
          <div class="month-header-month" id="cal2Month">
          </div>
        </div>
        <div class="stock-container">
          @for ($i = 0; $i < 6; $i++)
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
<script src="https://cdn.jsdelivr.net/gh/osamutake/japanese-holidays-js@v1.0.10/lib/japanese-holidays.min.js"></script>
<script>
  // コントローラーから渡された永続化された値
  const jsPersistedYear = {{ $persistedYear ?? \Carbon\Carbon::today()->year }};
  const jsPersistedMonth1 = {{ $persistedMonth1 ?? \Carbon\Carbon::today()->month }};

  let currentDisplayedYear = jsPersistedYear;
  let currentDisplayedMonth1 = jsPersistedMonth1;

  const calendar1StockData = new Map();
  const calendar2StockData = new Map();

  // 年ページネーションとカレンダー更新のためのグローバルハンドラー
  const globalYearHandler = {
    yearItems: [],
    yearListContainer: null,
    calendar1: null,
    calendar2: null,
    cal1YearEl: null,
    cal1MonthEl: null,
    cal2YearEl: null,
    cal2MonthEl: null,

    init: function(params) {
      this.yearListContainer = document.getElementById('yearList');
      if (this.yearListContainer) {
        this.yearItems = Array.from(this.yearListContainer.getElementsByClassName('c-pager__year-item'));
      }
      this.calendar1 = params.calendar1;
      this.calendar2 = params.calendar2;
      this.cal1YearEl = params.cal1YearEl;
      this.cal1MonthEl = params.cal1MonthEl;
      this.cal2YearEl = params.cal2YearEl;
      this.cal2MonthEl = params.cal2MonthEl;

      this.yearItems.forEach(item => {
        item.addEventListener('click', () => {
          this.handleYearChange(item.textContent);
        });
      });

      // 選択された年またはカレンダーのデフォルトに基づいて初期設定
      const initiallySelectedYearItem = this.yearListContainer ? this.yearListContainer.querySelector('.c-pager__year-item.--selected') : null;

      if (!initiallySelectedYearItem || parseInt(initiallySelectedYearItem.textContent) !== jsPersistedYear) {
        this.updateSelectedYearClass(jsPersistedYear.toString());
      }

      // カレンダーヘッダーとアクティブ状態の初期表示を設定
      const initialCal1Date = luxon.DateTime.fromObject({ year: jsPersistedYear, month: jsPersistedMonth1, day: 1 });
      this.updateCalendarHeaderDisplays(initialCal1Date);
      this.updateActiveCalendarState(jsPersistedYear, jsPersistedMonth1);
    },

    // Function to update hidden inputs in forms and global JS state
    updateActiveCalendarState: function(year, month1) {
      currentDisplayedYear = parseInt(year);
      currentDisplayedMonth1 = parseInt(month1);

      const yearStr = currentDisplayedYear.toString();
      const month1Str = currentDisplayedMonth1.toString();

      ['period_active_calendar_year', 'edit_active_calendar_year', 'delete_active_calendar_year'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = yearStr;
      });
      ['period_active_calendar_month1', 'edit_active_calendar_month1', 'delete_active_calendar_month1'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = month1Str;
      });
    },

    updateCalendarHeaderDisplays: function(cal1Date) {
        const cal2Date = cal1Date.plus({ months: 1 });
        if (this.cal1YearEl) this.cal1YearEl.textContent = cal1Date.year;
        if (this.cal1MonthEl) this.cal1MonthEl.textContent = cal1Date.month;
        if (this.cal2YearEl) this.cal2YearEl.textContent = cal2Date.year;
        if (this.cal2MonthEl) this.cal2MonthEl.textContent = cal2Date.month;
    },

    updateCalendarDisplays: function(year) { // 年ページャーが年を変更したときに呼び出される
      const yearNum = parseInt(year);
      if (isNaN(yearNum)) {
        console.error("Invalid year for calendar display:", year);
        return;
      }
      // 年ページャー経由で年が変更された場合、現在の表示月を維持する
      const cal1Date = luxon.DateTime.fromObject({ year: yearNum, month: currentDisplayedMonth1, day: 1 });


      if (this.calendar1) {
        this.calendar1.gotoDate(cal1Date.toISODate());
      }
      if (this.calendar2) {
        this.calendar2.gotoDate(cal1Date.plus({ months: 1 }).toISODate());
      }
      this.updateCalendarHeaderDisplays(cal1Date);
      this.updateActiveCalendarState(yearNum, currentDisplayedMonth1);
    },

    updateSelectedYearClass: function(selectedYearText) {
      const selectedYearNum = parseInt(selectedYearText);
      this.yearItems.forEach(item => {
        item.classList.toggle('--selected', parseInt(item.textContent) === selectedYearNum);
      });
    },

    handleYearChange: function(newYearText) {
      this.updateSelectedYearClass(newYearText);
      this.updateCalendarDisplays(newYearText);
    },

    prevPage: function() { // 年ページャー 前へ
      if (!this.yearListContainer) return;
      const currentSelected = this.yearListContainer.querySelector('.c-pager__year-item.--selected');
      if (!currentSelected) { // 何も選択されていない場合、永続化された年または最初の年を選択
        const targetYear = jsPersistedYear.toString(); // 永続化された年を文字列に変換
        const targetItem = this.yearItems.find(item => item.textContent === targetYear);
        if (targetItem) this.handleYearChange(targetYear);
        else if (this.yearItems.length > 0) this.handleYearChange(this.yearItems[0].textContent);
        return;
      }
      const currentIndex = this.yearItems.indexOf(currentSelected);
      if (currentIndex > 0) {
        const prevYearItem = this.yearItems[currentIndex - 1];
        this.handleYearChange(prevYearItem.textContent);
      }
    },

    nextPage: function() { // 年ページャー 次へ
      if (!this.yearListContainer) return;
      const currentSelected = this.yearListContainer.querySelector('.c-pager__year-item.--selected');
      if (!currentSelected) { // 何も選択されていない場合、永続化された年または最初の年を選択
        const targetYear = jsPersistedYear.toString();
        const targetItem = this.yearItems.find(item => item.textContent === targetYear);
        if (targetItem) this.handleYearChange(targetYear);
        else if (this.yearItems.length > 0) this.handleYearChange(this.yearItems[0].textContent);
        return;
      }
      const currentIndex = this.yearItems.indexOf(currentSelected);
      if (currentIndex < this.yearItems.length - 1) {
        const nextYearItem = this.yearItems[currentIndex + 1];
        this.handleYearChange(nextYearItem.textContent);
      }
    }
  };

  // HTMLのonclick属性によって呼び出される関数
  function prevPage() { globalYearHandler.prevPage(); }
  function nextPage() { globalYearHandler.nextPage(); }

  // 月ナビゲーション関数
  function navigateMonths(direction) {
    if (!globalYearHandler.calendar1 || !globalYearHandler.calendar2) return;

    let cal1CurrentLuxonDate = luxon.DateTime.fromJSDate(globalYearHandler.calendar1.getDate());
    let newCal1LuxonDate;
    if (direction === 'prev') {
      newCal1LuxonDate = cal1CurrentLuxonDate.minus({ months: 2 });
    } else if (direction === 'next') {
      newCal1LuxonDate = cal1CurrentLuxonDate.plus({ months: 2 });
    }
    globalYearHandler.calendar1.gotoDate(newCal1LuxonDate.toISODate());
    globalYearHandler.calendar2.gotoDate(newCal1LuxonDate.plus({ months: 1 }).toISODate());

    const newYear = newCal1LuxonDate.year;
    const newMonth1 = newCal1LuxonDate.month;

    // 月ナビゲーションによって年が変更された場合、年ページャーを更新
    if (newYear !== parseInt(globalYearHandler.yearListContainer.querySelector('.c-pager__year-item.--selected')?.textContent)) {
      globalYearHandler.updateSelectedYearClass(newYear.toString());
    }

    globalYearHandler.updateCalendarHeaderDisplays(newCal1LuxonDate);
    globalYearHandler.updateActiveCalendarState(newYear, newMonth1);
  }

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

    // 選択された日付の在庫データを取得
    // FullCalendarのdateClickから得られるselectedDateはLuxon DateTimeオブジェクト
    // calendarStockDataのキーと一致させるために 'YYYY-MM-DD' 形式にフォーマットする必要がある
    const dateStr = selectedDate.toISODate(); // 'YYYY-MM-DD' 形式にフォーマット
    const stockEntry = calendarStockData.get(dateStr);

    document.getElementById('edit_target_date').value = dateStr;
    document.getElementById('delete_target_date').value = dateStr;
    if (stockEntry && stockEntry.stock) {
      const stock = stockEntry.stock;
      // フォームフィールドに値を設定
      document.getElementById('edit_load_limit').value = stock.load_limit || '';
      document.getElementById('edit_unload_limit').value = stock.unload_limit || '';
      document.getElementById('edit_at_closing_time').value = stock.at_closing_time || '';
      document.getElementById('edit_per_fifteen_munites').value = stock.per_fifteen_munites || '';
    } else {
      // データが見つからない場合、オプションでフォームをクリアするかデフォルト値を設定
      document.getElementById('edit_load_limit').value = '';
      document.getElementById('edit_unload_limit').value = '';
      document.getElementById('edit_at_closing_time').value = '';
      document.getElementById('edit_per_fifteen_munites').value = '';
      console.warn(`No stock data found for ${dateStr}`);
    }
    editModal.classList.add('is-active');
  }
  function closePeriodModal() {
    document.getElementById(`modalAreaOption_Period_`).classList.remove('is-active');
  }
  function closeEditModal() {
    document.getElementById(`modalAreaOption_edit_`).classList.remove('is-active');
  }

  document.addEventListener('DOMContentLoaded', function() {
    const BASE_PATH = document.getElementById('base_path').value;
    const initialDateCal1 = luxon.DateTime.fromObject({ year: jsPersistedYear, month: jsPersistedMonth1, day: 1 }).toISODate();
    const initialDateCal2 = luxon.DateTime.fromObject({ year: jsPersistedYear, month: jsPersistedMonth1, day: 1 }).plus({ months: 1 }).toISODate();

    // カレンダー1の初期化 (1月)
    const calendar1El = document.getElementById('calendar1');
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
      dayCellDidMount: function(info) {
        // 祝日判定
        if (JapaneseHolidays.isHoliday(info.date)) {
          info.el.classList.add('holiday');
        }
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
          el.closest('.fc-day').classList.add('active');
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
      eventClick: function(info) {
        if(!info.el.closest('.fc-day').classList.contains("active")) {
            return;
        }
        // alert(info.event.start);
        const selectedDate = luxon.DateTime.fromJSDate(info.event.start);
        openEditModal(selectedDate, calendar1StockData);
      },
    });
    calendar1Instance.render();

    // カレンダー2の初期化 (2月)
    const calendar2El = document.getElementById('calendar2');
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
      dayCellDidMount: function(info) {
        // 祝日判定
        if (JapaneseHolidays.isHoliday(info.date)) {
          info.el.classList.add('holiday');
        }
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
          el.closest('.fc-day').classList.add('active');
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
      eventClick: function(info) {
        if(!info.el.closest('.fc-day').classList.contains("active")) {
            return;
        }
        // alert(info.event.start);
        const selectedDate = luxon.DateTime.fromJSDate(info.event.start);
        openEditModal(selectedDate, calendar2StockData);
      },
    });
    calendar2Instance.render();


    // グローバル年ハンドラーを初期化
    globalYearHandler.init({
      calendar1: calendar1Instance,
      calendar2: calendar2Instance,
      cal1YearEl: document.getElementById('cal1Year'),
      cal1MonthEl: document.getElementById('cal1Month'),
      cal2YearEl: document.getElementById('cal2Year'),
      cal2MonthEl: document.getElementById('cal2Month')
    });

    // 月ナビゲーションボタンを接続
    const cal1PrevButton = document.getElementById('cal1_prev');
    if (cal1PrevButton) cal1PrevButton.addEventListener('click', () => navigateMonths('prev'));
    const cal1NextButton = document.getElementById('cal1_next');
    if (cal1NextButton) cal1NextButton.addEventListener('click', () => navigateMonths('next'));


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
    // margin-bottom: 30px;
    // align-items: center;
  }

  .prev_button {
      position: absolute;
      left: -30px;
      top: 480px;
  }

  .next_button {
      position: absolute;
      right: -30px;
      top: 480px;
  }

  .calendar1-headers, .calendar2-headers {
    width: 75px;
    text-align: left;
    padding-right: 5px;
    display: flex;
    flex-direction: column; /* 縦方向 */
    height: 100%;
    align-self: stretch;
  }

  .stock-container {
    margin-top: 30px;
    display:flex;
    flex-direction: column;
    gap: 68px;
    justify-content: space-between;
  }

  .month-calendar {
    flex: 1;
    margin-top: 65px;
  }

  .fc table {
      border-collapse: separate;
  }

  table.fc-col-header {
    border-spacing: 4px;
    border-collapse: separate;
    font-size: small;
  }

  a.fc-col-header-cell-cushion {
      line-height: 22px;
  }

  .fc-day-disabled {
      border: none !important;
      background-color: #fff !important;
  }

  .fc-daygrid-day {
      background-color: #fff !important;
      color:black !important;
      text-align: center;
      height: 90px !important;
  }

  .fc .fc-daygrid-day.fc-day-today {
    background-color: rgba(255, 220, 40, 0.15);
  }

  /* 曜日の背景色 */
  .fc-col-header-cell.fc-day {
      background-color: black;
      color: #fff !important;
  }

  .fc-col-header-cell.fc-day.fc-day-sat {
      background-color: #1f6aaa;
  }

  .fc-col-header-cell.fc-day.fc-day-sun {
      background-color: #d82528;
  }


  /* 日付の文字色 */
  a.fc-daygrid-day-number {
      color: black !important;
      display: block;
      width: 100%;
  }

  .fc-day-sat a.fc-daygrid-day-number {
    color: #0066cc !important;
  }

  .fc-day-sun a.fc-daygrid-day-number,
  .holiday a.fc-daygrid-day-number {
    color: #cc0000 !important;
  }

  /* 日付セルのボーダー */
  .fc-day-mon:not(.fc-day-disabled),
  .fc-day-tue:not(.fc-day-disabled),
  .fc-day-wed:not(.fc-day-disabled),
  .fc-day-thu:not(.fc-day-disabled),
  .fc-day-fri:not(.fc-day-disabled) {
    color: black;
    border-bottom: 2px solid black !important;
  }

  .fc-day-sat:not(.fc-day-disabled) {
    border-bottom: 2px solid #0066cc !important;
  }

  .fc-day-sun:not(.fc-day-disabled),
  .holiday:not(.fc-day-disabled) {
    border-bottom: 2px solid #cc0000 !important;
  }
  .fc-day-mon.active,
  .fc-day-tue.active,
  .fc-day-wed.active,
  .fc-day-thu.active,
  .fc-day-fri.active {
    color: black;
    cursor: pointer;
  }

  .fc-day-sat.active {
    cursor: pointer;
  }

  .fc-day-sun.active,
  .holiday.active {
    cursor: pointer;
  }

  /* イベントのスタイル */
  .fc .fc-daygrid-event {
    background-color: transparent !important;
    color: black !important;
    border: none !important;
  }
  .fc-h-event .fc-event-main {
    color: black !important;
  }

  /* 在庫情報スタイル */
  .stock-info {
    font-size: 11px;
    margin-top: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .stock-item {
    margin-bottom: 2px;
  }


  /* 月ヘッダー */
  .month-header {
    display: flex;
    flex-direction: column;
  }

  .month-header-year {
    font-size: 18px;
    margin-top: 20px;
    padding: 5px;
    text-align: center;
  }
  .month-header-month {
    font-size: 40px;
    margin-bottom: 10px;
    text-align: center;
  }

  /* 在庫ヘッダー */
  .stock-headers {
    width: 100px;
    text-align: left;
    padding-right: 5px;
  }

  .stock-header-item {
    height: 18px;
    margin-bottom: 4px;
    font-size: 11px;
    font-weight: bold;
  }

  .l-modal__trashButton {
    cursor: pointer;
  }

  /* カレンダーの位置調整 */
  .fc .fc-scrollgrid-liquid {
    margin-left: 100px;
  }

  .fc-daygrid-day-events {
    height: 89px;
  }
</style>
@endpush
