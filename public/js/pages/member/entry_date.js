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


  const initLoadDate = luxon.DateTime.fromISO(loadDateInput.value);
  const initUnloadDate = luxon.DateTime.fromISO(unloadDateInput.value);

  const loadTimetableTitle = document.getElementById('load_timetable_title');
  const loadTimeSectionElem = document.getElementById('load_time_section');
  const leftCalendar1Title = document.getElementById('left_calendar1_title');
  const rightCalendar1Title = document.getElementById('right_calendar1_title');
  const leftCalendar2Title = document.getElementById('left_calendar2_title');
  const rightCalendar2Title = document.getElementById('right_calendar2_title');

  const dispNumDaysElem = document.getElementById('disp_num_days');
  const dispLoadDateElem = document.getElementById('disp_load_date');
  const dispUnloadDateElem = document.getElementById('disp_unload_date_plan');

  const hourLabelCells = Array.from(document.getElementsByClassName('hour_label_cell'));
  const quarterHourLabelCells = Array.from(document.getElementsByClassName('quarter_hour_label_cell'));
  const hourVacancyCells = Array.from(document.getElementsByClassName('hour_vacancy'));
  const quarterHourVacancyCells = Array.from(document.getElementsByClassName('quarter_hour_vacancy'));

  let selectedDateTime;

  openBtnElem.addEventListener('click', function() {
    loadPriceTable()
  });

  // 到着便情報の表示
  async function loadPriceTable() {
    const agencyCode = agencyCodeInput.value;
    const loadDate = loadDateInput.value;
    const unloadDate = unloadDateInput.value;

    if(loadDate == '' || unloadDate == '') {
      return;
    }

    // 到着便・到着日をAPIに送信
    const json = await apiRequest.get(BASE_PATH + "/prices/table",
      {load_date:loadDate, unload_date:unloadDate, agency_code:agencyCode}
    )

    console.log(json); // `data.json()` の呼び出しで解釈された JSON データ
    if(json.success){
      // 到着便情報の取得
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
      priceRowsElem
    }
  }

  hourLabelCells.forEach(cellElem => {
    cellElem.addEventListener('click', function(){
      removeHourSelected()
      cellElem.classList.add("hour_selected");
      updateQuarterMinTable(cellElem)
    })
  });

  quarterHourLabelCells.forEach(cellElem => {
    cellElem.addEventListener('click', function(){
      removeQuaterHourSelected()
      cellElem.classList.add("quater_hour_selected");
      loadTimeInput.value = cellElem.dataset.time;
    })
  });

  var calendarEl1 = document.getElementById('calendar1');
  var calendar1 = new FullCalendar.Calendar(calendarEl1, {
    initialView: 'multiMonthTwoMonth',
    views: {
      multiMonthTwoMonth: {
        type: 'multiMonth',
        duration: { months: 2 },
      }
    },
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
      const endDate = luxon.DateTime.fromJSDate(dateInfo.end).minus({days: 1});
      leftCalendar1Title.textContent = startDate.toFormat('yyyy年M月')
      rightCalendar1Title.textContent = endDate.toFormat('yyyy年M月')
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
      const startDate = luxon.DateTime.fromJSDate(e.event.start);
      if(startDate.hasSame(initLoadDate, 'day')) {
        let el = e.el;
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
      alert(info.date);
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
    initialView: 'multiMonthTwoMonth',
    views: {
      multiMonthTwoMonth: {
        type: 'multiMonth',
        duration: { months: 2 }
      }
    },
    multiMonthTitleFormat: { month: 'numeric', year: 'numeric' },
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
      const endDate = luxon.DateTime.fromJSDate(dateInfo.end).minus({days: 1});
      leftCalendar2Title.textContent = startDate.toFormat('yyyy年M月')
      rightCalendar2Title.textContent = endDate.toFormat('yyyy年M月')
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
      const startDate = luxon.DateTime.fromJSDate(e.event.start);
      if(startDate.hasSame(initUnloadDate, 'day')) {
        let el = e.el;
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
      alert(info.date);
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
  function removeHourSelected() {
    Array.from(loadTimeSectionElem.getElementsByClassName('hour_selected')).forEach(elem => {
      elem.classList.remove("hour_selected")
    })
  }
  function removeQuaterHourSelected() {
    Array.from(loadTimeSectionElem.getElementsByClassName('quater_hour_selected')).forEach(elem => {
      elem.classList.remove("quater_hour_selected")
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
    const loadDate = luxon.DateTime.fromISO(loadDateInput.value);
    const unloadDate = luxon.DateTime.fromISO(unloadDateInput.value);
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
  })
  loadTimeInput.addEventListener('change', function() {
    updateSelectedDateTime()
  })
  unloadDateInput.addEventListener('change', function() {
    updateDispUnloadDate()
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
    let loadDate = luxon.DateTime.fromISO(loadDateInput.value);
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
    const loadDate = luxon.DateTime.fromISO(loadDateInput.value);
    if(!loadDate.isValid) {
        dispLoadDateElem.textContent = ''
        return
      }
    dispLoadDateElem.textContent = loadDate.toFormat(`M/dd`) + '(' + loadDate.weekdayShort + ')';
    loadTimetableTitle.textContent = loadDate.toFormat('yyyy年M月d日');
  }

  function updateDispUnloadDate() {
    const unloadDate = luxon.DateTime.fromISO(unloadDateInput.value);
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
    removeHourSelected()
    // 入庫日をAPIに送信
    const json = await apiRequest.get(BASE_PATH + "/calendar/load_hours",
      {load_date:loadDate}
    )

    console.log(json); // `data.json()` の呼び出しで解釈された JSON データ
    if(json.success){
      // 入庫時間空き情報の取得
      //   alert(json.data);
      hourlyData = json.data.hourlyData;
      const loadDateObj = luxon.DateTime.fromISO(loadDateInput.value);
      let isSelectedDay = false;
      if(selectedDateTime && loadDateObj.hasSame(selectedDateTime, 'day')) {
          isSelectedDay = true;
      }
      hourVacancyCells.forEach(hourVacancyCell => {
        const hour = hourVacancyCell.dataset.hour;
        hourVacancyCell.textContent = hourlyData[hour]?.status;
        setVacancyColor(hourVacancyCell, hourlyData[hour]?.status)
      });
      hourLabelCells.forEach(cellElem => {
        const hour = cellElem.dataset.hour;
        if(isSelectedDay && isSelectedHour(hour)) {
          removeHourSelected()
          cellElem.classList.add("hour_selected");
          updateQuarterMinTable(cellElem, true)
        }
      });

      if(!isSelectedDay) {
        resetQuarterMinTable()
      }
    }
  }

  function isSelectedHour(hour) {
    return hour == selectedDateTime.hour
  }

  function isSelectedmin(min) {
    return min == selectedDateTime.minute
  }

  function updateQuarterMinTable(cellElem, isSelectedDayHour = false) {
    removeQuaterHourSelected()
    const hour = cellElem.dataset.hour;
    quarterHourLabelCells.forEach(labelCell => {
      const min = labelCell.dataset.min;
      labelCell.textContent = hour + ':' + min + '～';
      labelCell.dataset.time = hour + ':' + min;

      if(isSelectedDayHour && isSelectedmin(min)) {
        removeQuaterHourSelected()
        labelCell.classList.add("quater_hour_selected");
      }
    });
    quarterHourVacancyCells.forEach(vacancyCell => {
      const min = vacancyCell.dataset.min;
      vacancyCell.textContent = hourlyData[hour][min];
      setVacancyColor(vacancyCell, hourlyData[hour][min])
    });
  }
  function resetQuarterMinTable() {
    removeQuaterHourSelected()
    quarterHourLabelCells.forEach(labelCell => {
      const min = labelCell.dataset.min;
      labelCell.textContent = ':' + min + '～';
      labelCell.dataset.time = ':' + min;
    });
    quarterHourVacancyCells.forEach(vacancyCell => {
      vacancyCell.textContent = '';
    });
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
