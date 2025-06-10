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
    // events:
    // function(info, successCallback, failureCallback) {
    //   url = BASE_PATH +  '/calendar/load_dates';

    //   const response = apiRequest.get(url, {
    //     start: info.startStr,
    //     end: info.endStr,
    //   })

    //   response.then(data => {
    //     // console.log(data);

    //     successCallback(
    //       data
    //     )
    //   });
    // },
    // eventDidMount: function(e) {
    //   let el = e.el;
    //   const startDate = luxon.DateTime.fromJSDate(e.event.start);
    //   const loadDateObj = parseDateInput(loadDateInput.value);
    //   if(loadDateObj.isValid && startDate.hasSame(loadDateObj, 'day')) {
    //     //イベントが表示される場所の親をたどって各日の枠にたどり着いたらclassを追加
    //     el.closest('.fc-daygrid-day').classList.add('day_selected');
    //     // if(!selectedDateTime) {
    //       dispLoadHourTable()
    //     // }
    //   } else if(startDate.hasSame(initLoadDate, 'day')) {
    //     //イベントが表示される場所の親をたどって各日の枠にたどり着いたらclassを追加
    //     el.closest('.fc-daygrid-day').classList.add('day_selected');
    //     dispLoadHourTable()
    //   }
    //   if(e.event.toPlainObject().title == '×') {
    //     el.classList.add('event_full');
    //     el.closest('.fc-daygrid-day').classList.add('day_full');
    //   }
    // },
    // eventClick: function(info) {
    //   if(info.el.classList.contains("fc-event-past") || info.el.classList.contains("event_full")) {
    //     return;
    //   }
    //   alert(info.event.start);
    //   const startDate = luxon.DateTime.fromJSDate(info.event.start);
    //   loadDateInput.value = startDate.toISODate();
    //   loadDateInput.dispatchEvent(new Event('change'));
    //   dispLoadHourTable()
    //   calcNumDays()
    //   // alert('Event: ' + info.event.title);
    //   // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
    //   // alert('View: ' + info.view.type);
    //   removeDaySelected(calendarEl1)
    //   info.el.closest('td').classList.add("day_selected");
    // },
    // dateClick: function(info) {
    //   if(info.dayEl.classList.contains("fc-day-past") || info.dayEl.classList.contains("day_full")) {
    //     return;
    //   }
    //   // alert(info.date);
    //   loadDateInput.value = luxon.DateTime.fromJSDate(info.date).toISODate();
    //   loadDateInput.dispatchEvent(new Event('change'));
    //   dispLoadHourTable()
    //   calcNumDays()
    //   removeDaySelected(calendarEl1)
    //   info.dayEl.classList.add("day_selected");
    // },
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


  const cal1PrevButton = document.getElementById('cal1_prev');
  if (cal1PrevButton) cal1PrevButton.addEventListener('click', () => calendar1.prev());
  const cal1NextButton = document.getElementById('cal1_next');
  if (cal1NextButton) cal1NextButton.addEventListener('click', () => calendar1.next());
});
