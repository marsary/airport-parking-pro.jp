document.addEventListener('DOMContentLoaded', function () {
  const BASE_PATH = document.getElementById('base_path').value;
  const leftCalendar1Title = document.getElementById('left_calendar1_title');

  let currentDisplayedMonth1 = null;
  let currentDisplayedYear1 = null;

  var calendarEl1 = document.getElementById('calendar1');
  var calendar1 = new FullCalendar.Calendar(calendarEl1, {
    initialView: 'dayGridMonth',
    firstDay:1,
    showNonCurrentDates:false,
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
    datesSet: function(dateInfo) {
      const startDate = luxon.DateTime.fromJSDate(dateInfo.start);
      leftCalendar1Title.textContent = startDate.toFormat('yyyy年M月')
      currentDisplayedMonth1 = startDate.month;
      currentDisplayedYear1 = startDate.year;

    },
    eventContent: function (arg) {
      return {
        html: arg.event.title // HTML をそのまま出力
      };
    },
    events:
    function(info, successCallback, failureCallback) {
      url = BASE_PATH +  '/manage/ledger/reservation_result/calendar';

      const response = apiRequest.get(url, {
        start: info.startStr,
        end: info.endStr,
      })

      response.then(data => {
        const eventData = [];
        console.log(data);
        data.forEach(row => {
          let eventTitle = '';
          if(!row.stock.no_data) {
            eventTitle = `
            <div class="stock-item"><span>入庫 ${row.stock.load_quantity ?? ''}</span></div>
            <div class="stock-item"><span>出庫 ${row.stock.unload_quantity ?? ''}</span></div>
            <div class="stock-item"><span>在庫 ${row.stock.stock_quantity ?? ''}</span></div>
            `;
          }


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
    // eventColor: 'rgba(255, 255, 255, 0)',
    // eventTextColor: '#5b915b',
  });
  calendar1.render();


  // 年ページネーションとカレンダー更新のためのグローバルハンドラー
  const globalYearHandler = {
    yearItems: [],
    yearListContainer: null,
    calendar1: null,

    init: function(params) {
      this.yearListContainer = document.getElementById('yearList');
      if (this.yearListContainer) {
        this.yearItems = Array.from(this.yearListContainer.getElementsByClassName('c-pager__year-item'));
      }
      this.calendar1 = params.calendar1;

      this.yearItems.forEach(item => {
        item.addEventListener('click', () => {
          this.handleYearChange(item.textContent);
        });
      });
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
  };

  // グローバル年ハンドラーを初期化
  globalYearHandler.init({
    calendar1: calendar1,
  });


  // 月ナビゲーション関数
  function navigateMonths(direction) {
    if (!globalYearHandler.calendar1) return;

    let cal1CurrentLuxonDate = luxon.DateTime.fromJSDate(globalYearHandler.calendar1.getDate());
    let newCal1LuxonDate;
    if (direction === 'prev') {
      globalYearHandler.calendar1.prev();
      newCal1LuxonDate = cal1CurrentLuxonDate.minus({ months: 1 });
    } else if (direction === 'next') {
      globalYearHandler.calendar1.next();
      newCal1LuxonDate = cal1CurrentLuxonDate.plus({ months: 1 });
    }

    const newYear = newCal1LuxonDate.year;

    // 月ナビゲーションによって年が変更された場合、年ページャーを更新
    if (newYear !== parseInt(globalYearHandler.yearListContainer.querySelector('.c-pager__year-item.--selected')?.textContent)) {
      globalYearHandler.updateSelectedYearClass(newYear.toString());
    }
  }


  const cal1PrevButton = document.getElementById('cal1_prev');
  if (cal1PrevButton) cal1PrevButton.addEventListener('click', () => navigateMonths('prev'));
  const cal1NextButton = document.getElementById('cal1_next');
  if (cal1NextButton) cal1NextButton.addEventListener('click', () => navigateMonths('next'));
});
