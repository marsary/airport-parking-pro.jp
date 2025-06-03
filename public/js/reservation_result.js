let hourlyData = [];

document.addEventListener('DOMContentLoaded', function () {
  const leftCalendar1Title = document.getElementById('left_calendar1_title');
//   const rightCalendar1Title = document.getElementById('right_calendar1_title');

  var calendarEl1 = document.getElementById('calendar1');
  var calendar1 = new FullCalendar.Calendar(calendarEl1, {
    initialView: 'dayGridMonth',
    // initialView: 'multiMonthTwoMonth',
    // views: {
    //   multiMonthTwoMonth: {
    //     type: 'multiMonth',
    //     duration: { months: 2 },
    //   }
    // },
    // multiMonthTitleFormat: {},//{ month: 'numeric', year: 'numeric' },
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
    //   const endDate = luxon.DateTime.fromJSDate(dateInfo.end).minus({days: 1});
      leftCalendar1Title.textContent = startDate.toFormat('yyyy年M月')
    //   rightCalendar1Title.textContent = endDate.toFormat('yyyy年M月')
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
  // if(initLoadDate.isValid) {
  //     calendar1.gotoDate( initLoadDate.toISODate() );
  // }
  calendar1.render();


  document.getElementById('cal1_prev').addEventListener('click', function() {
    calendar1.prev();
  });
  document.getElementById('cal1_next').addEventListener('click', function() {
    calendar1.next();
  });

});
