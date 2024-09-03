const BASE_PATH = document.getElementById('base_path').value;
const startDateInput = document.getElementById('start_date');
const endDateInput = document.getElementById('end_date');
const monthlyChartBtn = document.getElementById('monthlyChartBtn');
const weeklyChartBtn = document.getElementById('weeklyChartBtn');
const dailyChartBtn = document.getElementById('dailyChartBtn');
const manualChartBtn = document.getElementById('manualChartBtn');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const chartTitle = document.getElementById('chart_title');
// const comparedPeriodSwitch = document.getElementById('compared_period');
const loadSwitch = document.getElementById('load');
const unloadSwitch = document.getElementById('unload');
// const maxStockSwitch = document.getElementById('max_stock');
// const endingStockSwitch = document.getElementById('ending_stock');

let chartDataset = [];

monthlyChartBtn.addEventListener('click', async (e)=> {
  currentView = "monthly"
  currentStartDate = luxon.DateTime.fromJSDate(new Date()).startOf('month');
  json = await loadChartData()

  handleChartData(json)
})
weeklyChartBtn.addEventListener('click', async (e)=> {
  currentView = "weekly"
  currentStartDate = luxon.DateTime.fromJSDate(new Date()).startOf('week', {useLocaleWeeks: true});
  json = await loadChartData()

  handleChartData(json)
})
dailyChartBtn.addEventListener('click', async (e)=> {
  currentView = "daily"
  currentStartDate = luxon.DateTime.fromJSDate(new Date()).startOf('day');
  json = await loadChartData()

  handleDailyChartData(json)
})
manualChartBtn.addEventListener('click', async (e)=> {
  if(startDateInput.value == '' || endDateInput.value == '') return;
  currentView = "manual"
  currentStartDate = startDateInput.value;
  json = await loadChartData()

  handleChartData(json)
})

nextBtn.addEventListener('click', async (e)=> {
  if(currentView == null) return;

  json = await loadChartData('next')

  switch (currentView) {
    case 'daily':
      handleDailyChartData(json)
      break;
    default:
      handleChartData(json)
      break;
  }
})
prevBtn.addEventListener('click', async (e)=> {
  if(currentView == null) return;

  json = await loadChartData('prev')
  switch (currentView) {
    case 'daily':
      handleDailyChartData(json)
      break;
    default:
      handleChartData(json)
      break;
  }
})

// comparedPeriodSwitch.addEventListener('change', () => {
//   toggleSwitch(comparedPeriodSwitch, COMPARED_PERIOD);
// })
loadSwitch.addEventListener('change', () => {
  toggleSwitch(loadSwitch, LOAD);
})
unloadSwitch.addEventListener('change', () => {
  toggleSwitch(unloadSwitch, UNLOAD);
})
// maxStockSwitch.addEventListener('change', () => {
//   toggleSwitch(maxStockSwitch, MAX_STOCK);
// })
// endingStockSwitch.addEventListener('change', () => {
//   toggleSwitch(endingStockSwitch, ENDING_STOCK);
// })

function renderChart(data, useTickDate = true) {
  chartDataset = []
  Object.keys(data.datasets).forEach(label => {
    const dataset = data.datasets[label];
    const categoryData = []
    data.labels.forEach(dataKey => {
      categoryData.push(dataset[dataKey])
    })
    chartDataset.push({
      label: label,
      data: categoryData,
      fill: false,
      pointBackgroundColor: lineColors.get(label),
      borderColor: lineColors.get(label),
    })
  });
  let chartStatus = Chart.getChart("myChart"); // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy();
  }
  document.querySelector("#chartReport").innerHTML = '<canvas id="myChart"></canvas>';
  const ctx = document.getElementById('myChart');
  chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: data.labels,
        datasets: chartDataset,
    },
    options: {
      responsive: false,
      scales: {
        x: {
          beginAtZero: true,
        //   stacked: true,
          grid: {
            display: false
          },
          ticks: {                      // 目盛り
            callback: function(value, index, ticks) {
              if(useTickDate) {
                const tickDate = new Date(data.labels[index]);
                //   console.log(tickDate.getDate().toString())
                return tickDate.getDate().toString();
              }
            //   console.log(data.labels);
            //   return data.labels[index];
              return Number(data.labels[index]);
            }
          },
        },
        y: {
            min: 0,                        // 最小値
            max: 15,                       // 最大値
            beginAtZero: true,
            // stacked: true,
            ticks: {                       // 目盛り
              count:5,
              autoSkip: false,
              stepSize: 3,                   // 軸間隔
            //   fontColor: "blue",             // 目盛りの色
              fontSize: 14                   // フォントサイズ
          },
        }
      },
      plugins: {
        title: {
          display: false,
        },
        legend: {
          display: false,
          align: 'end',
        },
        tooltip: {
            enabled: true,
            callbacks: {
              title: function(tooltipData) {
                return tooltipData[0].dataset.label;
              },
              label: function (tooltipData) {
                  const value = tooltipData.dataset.data[tooltipData.dataIndex];
                  return String(value) + '台'
              },
            },
            backgroundColor: "#ffefd6",
            titleColor: "#7a7670",
            bodyColor: "#7a7670",
            // titleFont: { weight: 'bold' },
            padding: 10,
            // cornerRadius: 10,
            // borderColor: "#042a0b",
            borderWidth: "0",
            xAlign: "left"
        },
    },
    }
  });

  legendItems = chart.options.plugins.legend.labels.generateLabels(chart);
  console.log(legendItems);

  switch (currentView) {
    case 'manual':
      prevBtn.classList.add('hidden')
      nextBtn.classList.add('hidden')
      break;
    default:
      prevBtn.classList.remove('hidden')
      nextBtn.classList.remove('hidden')
      break;
  }
  renderTitle()
  filterDatasets()
}

async function loadChartData(nextPrev = null) {
  // 取得条件をAPIに送信
  // グラフデータの取得
  const startDate = startDateInput.value
  const endDate = endDateInput.value
  let $path = ''
  switch (currentView) {
    case 'daily':
      $path = "/manage/marketing/graph/inventory/chart_by_hour"
      break;
    default:
      $path = "/manage/marketing/graph/inventory/chart_by_day"
      break;
  }
  const json = await apiRequest.get(BASE_PATH + $path, {
    view:currentView,
    startDate,
    endDate,
    currentStartDate,
    nextPrev
  });

  return json
}


// const COMPARED_PERIOD = '比較期間を表示';
const LOAD = '入庫';
const UNLOAD = '出庫';
const MAX_STOCK = 'MAX在庫';
const ENDING_STOCK = 'おわり在庫';

const lineColors = new Map([
//   [COMPARED_PERIOD, "gray"],
  [LOAD, "#df7a42"],
  [UNLOAD, "red"],
  [MAX_STOCK, "#4372b8"],
  [ENDING_STOCK, "black"],
]);
