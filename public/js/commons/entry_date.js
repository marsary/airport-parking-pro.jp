let hourlyData = [];

document.addEventListener('DOMContentLoaded', function () {
  const BASE_PATH = document.getElementById('base_path').value;
  const priceRowsElem = document.getElementById('price_rows');
  const tableNumDaysElem = document.getElementById('num_days');
  const subTotalElem = document.getElementById('sub_total');
  const taxLabelElem = document.getElementById('tax_label');
  const taxElem = document.getElementById('tax');
  const totalElem = document.getElementById('total');
  const openBtnElem = document.getElementById('open_button');

  const agencyCodeInput = document.querySelector('input[name=agency_code]')
  const loadDateInput = document.querySelector('input[name=load_date]')
  const loadTimeInput = document.querySelector('input[name=load_time]')
  const unloadDateInput = document.querySelector('input[name=unload_date_plan]')
  const numDaysInput = document.querySelector('input[name=num_days]')

  let initLoadDate = parseDateInput(loadDateInput.value);
  let initUnloadDate = parseDateInput(unloadDateInput.value);

  const loadTimetableTitle = document.getElementById('load_timetable_title');
  const loadTimeSectionElem = document.getElementById('load_time_section');
  const leftCalendar1Title = document.getElementById('left_calendar1_title');
  const leftCalendar2Title = document.getElementById('left_calendar2_title');

  const dispNumDaysElem = document.getElementById('disp_num_days');
  const dispLoadDateElem = document.getElementById('disp_load_date');
  const dispUnloadDateElem = document.getElementById('disp_unload_date_plan');

  const timeLabelCells = Array.from(document.getElementsByClassName('time_label_cell'));
  const timeVacancyCells = Array.from(document.getElementsByClassName('time_vacancy'));

  let selectedDateTime;

  openBtnElem.addEventListener('click', function() {
    loadPriceTable()
  });

  // 駐車料金明細情報の表示
  async function loadPriceTable() {
    const agencyCode = agencyCodeInput.value;
    const loadDate = loadDateInput.value;
    const unloadDate = unloadDateInput.value;

    if(loadDate == '' || unloadDate == '') {
      return;
    }

    // 入出庫情報をAPIに送信
    const json = await apiRequest.get(BASE_PATH + "/prices/table",
      {load_date:loadDate, unload_date:unloadDate, agency_code:agencyCode}
    )

    console.log(json); // `data.json()` の呼び出しで解釈された JSON データ
    if(json.success){
      // alert(json.data);

      tableNumDaysElem.textContent = json.data.table.numDays  + '日間';
      subTotalElem.textContent = formatCurrency(json.data.table.subTotal, '￥');
      taxLabelElem.textContent = json.data.table.taxLabel;

      taxElem.textContent = formatCurrency(json.data.table.tax, '￥');
      totalElem.textContent = formatCurrency(json.data.table.total, '￥', '(税込)');

      // 料金明細表
      removeAllChildNodes(priceRowsElem)
      json.data.table.rows.forEach((row) => {
        const div1 = document.createElement('div')
        const div2 = document.createElement('div')
        const rowDate = luxon.DateTime.fromISO(row.date);
        div1.textContent = rowDate.toFormat(`M/dd`) + '(' + rowDate.weekdayShort + ')';;
        div2.textContent = formatCurrency(row.price, '￥')
        priceRowsElem.appendChild(div1)
        priceRowsElem.appendChild(div2)
      })
    }
  }

  timeLabelCells.forEach(cellElem => {
    cellElem.addEventListener('click', function(){
      removeTimeSelected()
      cellElem.classList.add("time_selected");
      loadTimeInput.value = cellElem.dataset.time;
      loadTimeInput.dispatchEvent(new Event('change'));
    })
  });
  function removeTimeSelected() {
    Array.from(loadTimeSectionElem.getElementsByClassName('time_selected')).forEach(elem => {
      elem.classList.remove("time_selected")
    })
  }


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
      leftCalendar1Title.textContent = startDate.toFormat('yyyy年M月')
    },

    eventColor: 'rgba(255, 255, 255, 0)',
    eventTextColor: '#5b915b',
    events:
    function(info, successCallback, failureCallback) {
      url = BASE_PATH +  '/calendar/load_dates';

      const response = apiRequest.get(url, {
        start: info.startStr,
        end: info.endStr,
      })

      response.then(data => {
        // console.log(data);

        successCallback(
          data
        )
      });
    },
    eventDidMount: function(e) {
      let el = e.el;
      const startDate = luxon.DateTime.fromJSDate(e.event.start);
      const loadDateObj = parseDateInput(loadDateInput.value);
      if(loadDateObj.isValid && startDate.hasSame(loadDateObj, 'day')) {
        //イベントが表示される場所の親をたどって各日の枠にたどり着いたらclassを追加
        el.closest('.fc-daygrid-day').classList.add('day_selected');
        // if(!selectedDateTime) {
          dispLoadHourTable()
        // }
      } else if(startDate.hasSame(initLoadDate, 'day')) {
        //イベントが表示される場所の親をたどって各日の枠にたどり着いたらclassを追加
        el.closest('.fc-daygrid-day').classList.add('day_selected');
        dispLoadHourTable()
      }
      if(e.event.toPlainObject().title == '×') {
        el.classList.add('event_full');
        el.closest('.fc-daygrid-day').classList.add('day_full');
      }
    },
    eventClick: function(info) {
      if(info.el.classList.contains("fc-event-past") || info.el.classList.contains("event_full")) {
        return;
      }
      alert(info.event.start);
      const startDate = luxon.DateTime.fromJSDate(info.event.start);
      loadDateInput.value = startDate.toISODate();
      loadDateInput.dispatchEvent(new Event('change'));
      dispLoadHourTable()
      calcNumDays()
      // alert('Event: ' + info.event.title);
      // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
      // alert('View: ' + info.view.type);
      removeDaySelected(calendarEl1)
      info.el.closest('td').classList.add("day_selected");
    },
    dateClick: function(info) {
      if(info.dayEl.classList.contains("fc-day-past") || info.dayEl.classList.contains("day_full")) {
        return;
      }
      // alert(info.date);
      loadDateInput.value = luxon.DateTime.fromJSDate(info.date).toISODate();
      loadDateInput.dispatchEvent(new Event('change'));
      dispLoadHourTable()
      calcNumDays()
      removeDaySelected(calendarEl1)
      info.dayEl.classList.add("day_selected");
    },
  });
  if(initLoadDate.isValid) {
      calendar1.gotoDate( initLoadDate.toISODate() );
  }
  calendar1.render();

  var calendarEl2 = document.getElementById('calendar2');
  var calendar2 = new FullCalendar.Calendar(calendarEl2, {
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
      leftCalendar2Title.textContent = startDate.toFormat('yyyy年M月')
    },
    eventColor: 'rgba(255, 255, 255, 0)',
    eventTextColor: '#5b915b',
    events:
    function(info, successCallback, failureCallback) {
      url = BASE_PATH +  '/calendar/unload_dates';

      const response = apiRequest.get(url, {
        start: info.startStr,
        end: info.endStr,
      })

      response.then(data => {
        // console.log(data);

        successCallback(
          data
        )
      });
    },
    eventDidMount: function(e) {
      let el = e.el;
      const startDate = luxon.DateTime.fromJSDate(e.event.start);
      const unloadDateObj = parseDateInput(unloadDateInput.value);
      if(unloadDateObj.isValid && startDate.hasSame(unloadDateObj, 'day')) {
        //イベントが表示される場所の親をたどって各日の枠にたどり着いたらclassを追加
        el.closest('.fc-daygrid-day').classList.add('day_selected');
      } else if(startDate.hasSame(initUnloadDate, 'day')) {
        //イベントが表示される場所の親をたどって各日の枠にたどり着いたらclassを追加
        el.closest('.fc-daygrid-day').classList.add('day_selected');
      }
      if(e.event.toPlainObject().title == '×') {
        el.classList.add('event_full');
        el.closest('.fc-daygrid-day').classList.add('day_full');
      }
    },
    eventClick: function(info) {
      if(info.el.classList.contains("fc-event-past") || info.el.classList.contains("event_full")) {
          return;
      }
      alert(info.event.start);
      unloadDateInput.value = luxon.DateTime.fromJSDate(info.event.start).toISODate();
      unloadDateInput.dispatchEvent(new Event('change'));
      calcNumDays()
      // alert('Event: ' + info.event.title);
      // alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
      // alert('View: ' + info.view.type);
      removeDaySelected(calendarEl2)
      info.el.closest('td').classList.add("day_selected");
    },
    dateClick: function(info) {
      if(info.dayEl.classList.contains("fc-day-past") || info.dayEl.classList.contains("day_full")) {
        return;
      }
      // alert(info.date);
      unloadDateInput.value = luxon.DateTime.fromJSDate(info.date).toISODate();
      unloadDateInput.dispatchEvent(new Event('change'));
      calcNumDays()
      removeDaySelected(calendarEl2)
      info.dayEl.classList.add("day_selected");
    },
  });
  if(initUnloadDate.isValid) {
      calendar2.gotoDate( initUnloadDate.toISODate() );
  }
  calendar2.render();

  function removeDaySelected(calElem) {
    Array.from(calElem.getElementsByClassName('day_selected')).forEach(elem => {
      elem.classList.remove("day_selected")
    })
  }




  document.getElementById('cal1_prev').addEventListener('click', function() {
    calendar1.prev();
  });
  document.getElementById('cal1_next').addEventListener('click', function() {
    calendar1.next();
  });
  document.getElementById('cal2_prev').addEventListener('click', function() {
    calendar2.prev();
  });
  document.getElementById('cal2_next').addEventListener('click', function() {
    calendar2.next();
  });

  function calcNumDays() {
    const loadDate = parseDateInput(loadDateInput.value);
    const unloadDate = parseDateInput(unloadDateInput.value);
    if(!loadDate.isValid || !unloadDate.isValid) {
      numDaysInput.value = ''
      return
    }
    const diffInDays = unloadDate.diff(loadDate, 'days').plus({ days: 1 });
    numDaysInput.value = Math.ceil(diffInDays.as('days'));
    numDaysInput.dispatchEvent(new Event('change'));
    loadPriceTable()
  }

  loadDateInput.addEventListener('change', function() {
    updateDispLoadDate()
    initLoadDate = parseDateInput(loadDateInput.value);
  })
  loadTimeInput.addEventListener('change', function() {
    updateSelectedDateTime()
  })
  unloadDateInput.addEventListener('change', function() {
    updateDispUnloadDate();
    initUnloadDate = parseDateInput(unloadDateInput.value);
  })
  numDaysInput.addEventListener('change', function() {
    updateDispNumDays()
  })
  agencyCodeInput.addEventListener('change', function() {
    loadPriceTable()
  })

  function updateSelectedDateTime() {
    if(loadTimeInput.value == '') {
      selectedDateTime = null;
    }
    let loadDate = parseDateInput(loadDateInput.value)
    if(!loadDate.isValid) {
      return
    }
    if(loadTimeInput.value != '') {
        const [hour, min] = loadTimeInput.value.split(':')
        loadDate = loadDate.plus({ hours: hour, minutes: min });
    }
    selectedDateTime = loadDate
  }

  function updateDispLoadDate() {
    const loadDate = parseDateInput(loadDateInput.value);
    if(!loadDate.isValid) {
        dispLoadDateElem.textContent = ''
        return
      }
    dispLoadDateElem.textContent = loadDate.toFormat(`M/dd`) + '(' + loadDate.weekdayShort + ')';
    loadTimetableTitle.textContent = loadDate.toFormat('yyyy年M月d日');

  }

  function updateDispUnloadDate() {
    const unloadDate = parseDateInput(unloadDateInput.value)
    if(!unloadDate.isValid) {
        dispUnloadDateElem.textContent = ''
        return
      }
    dispUnloadDateElem.textContent = unloadDate.toFormat(`M/dd`) + '(' + unloadDate.weekdayShort + ')';
  }

  function updateDispNumDays() {
    if(!isNaN(numDaysInput.value)) {
        dispNumDaysElem.textContent = numDaysInput.value + '日間';
    } else {
        dispNumDaysElem.textContent = '';

    }
  }


  async function dispLoadHourTable() {
    const loadDate = loadDateInput.value;

    if(loadDate == '') {
      return;
    }
    removeTimeSelected()
    // 入庫日をAPIに送信
    const json = await apiRequest.get(BASE_PATH + "/calendar/load_hours",
      {load_date:loadDate}
    )

    console.log(json); // `data.json()` の呼び出しで解釈された JSON データ
    if(json.success){
      // 入庫時間空き情報の取得
      //   alert(json.data);
      hourlyData = json.data.hourlyData;
      const loadDateObj = parseDateInput(loadDateInput.value);
      let isSelectedDay = false;
      if(selectedDateTime && loadDateObj.hasSame(selectedDateTime, 'day')) {
          isSelectedDay = true;
      }
      timeVacancyCells.forEach(timeVacancyCell => {
        const [hour, min] = timeVacancyCell.dataset.time.split(':');
        setVacancyColor(timeVacancyCell, hourlyData[hour][min] || '-')
      });
      timeLabelCells.forEach(cellElem => {
        const [hour, min] = cellElem.dataset.time.split(':');
        if(isSelectedDay && isSelectedTime(hour, min)) {
          cellElem.classList.add("time_selected");
        }
      });
    }
  }

  function isSelectedTime(hour, min) {
    if(!selectedDateTime) {
      return false
    }
    return hour == selectedDateTime.hour && min == selectedDateTime.minute
  }


  function setVacancyColor(cell, status) {
    removeAllChildNodes(cell);
    const img = document.createElement('img')

    switch (status) {
      case '〇':
        img.src = BASE_PATH +  '/images/svg/calendar_available.svg';
        cell.appendChild(img);
        break;
      case '△':
        img.src = BASE_PATH +  '/images/svg/calendar_some-available.svg';
        cell.appendChild(img);
        break;
      case '×':
        img.src = BASE_PATH +  '/images/svg/calendar_reserved.svg';
        cell.appendChild(img);
        break;
      case '-':
        img.src = BASE_PATH +  '/images/svg/calendar_none.svg';
        cell.appendChild(img);
        break;

      default:
        break;
    }
  }


  // 初期表示
  updateDispLoadDate()
  updateDispUnloadDate()
  calcNumDays()
  updateSelectedDateTime()
});
