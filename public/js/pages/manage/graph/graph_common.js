let currentStartDate;
let currentEndDate;
let currentView;
let chart;
let legendItems;

function toggleSwitch(switchElem, switchLabel) {
    if(!chart) return;

    const legendItem = legendItems.find(legendItem => legendItem.text == switchLabel)
    if(switchElem.checked) {
      chart.setDatasetVisibility(legendItem.datasetIndex, true);

    } else {
      chart.setDatasetVisibility(legendItem.datasetIndex, false);

    }
    chart.update();
  }

  function renderTitle() {
    switch (currentView) {
      case 'monthly':
        chartTitle.textContent = currentStartDate.toFormat('yyyy/M')
        break;
      case 'weekly':
      case 'manual':
        chartTitle.textContent = currentStartDate.toFormat('yyyy/M/dd') + '～' + currentEndDate.toFormat('yyyy/M/dd')
        break;
      case 'daily':
        chartTitle.textContent = currentStartDate.toFormat('yyyy/M/dd')
        break;
      default:
        break;
    }
  }

  function setCurrentPeriod(labels = []) {
    if(typeof labels[0] !== 'undefined') {
      currentStartDate = luxon.DateTime.fromSQL(labels[0]);
    }
    if(labels.length > 0 && typeof labels[labels.length - 1] != null) {
      currentEndDate = luxon.DateTime.fromSQL(labels[labels.length - 1]);
    }

  }

  function handleChartData(json) {
    if(json.success){
      setCurrentPeriod(json.data.labels)
      console.log(json.data)
      renderChart(json.data)
    }
  }
  function handleDailyChartData(json) {
    if(json.success){
      currentStartDate = luxon.DateTime.fromISO(json.data.currentDate);
      currentEndDate = currentStartDate.set();
      console.log(json.data)
      renderChart(json.data, false)
    }
  }


function filterDatasets() {
    const checkboxes = Array.from(document.querySelectorAll('input.c-button-toggle__input[type="checkbox"]'));
    checkboxes.map((checkbox) => {
      const liElem = checkbox.closest('li');
      if(!liElem) return;

      const legendItem = legendItems.find(legendItem => legendItem.text == liElem.textContent.trim())

      if(!legendItem) {
        throw new Error(liElem.textContent.trim() + 'に対応するチャートのlegend項目が取得できません。');
      }

      if(checkbox.checked) {
        chart.setDatasetVisibility(legendItem.datasetIndex, true);

      } else {
        chart.setDatasetVisibility(legendItem.datasetIndex, false);
      }
    })
    chart.update();
  }
