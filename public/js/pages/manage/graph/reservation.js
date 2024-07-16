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

let currentStartDate;
let currentEndDate;
let chartDataset = [];
let currentView;

monthlyChartBtn.addEventListener('click', async (e)=> {
  currentView = "monthly"
  currentStartDate = luxon.DateTime.fromJSDate(new Date()).startOf('month');
  json = await loadChartData()

  handleChartData(json)
})


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

function renderChart(data) {
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
      backgroundColor: barColors.get(label),
    })
  });
  let chartStatus = Chart.getChart("myChart"); // <canvas> id
  if (chartStatus != undefined) {
    chartStatus.destroy();
  }
  document.querySelector("#chartReport").innerHTML = '<canvas id="myChart"></canvas>';
  const ctx = document.getElementById('myChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
        labels: data.labels,
        datasets: chartDataset,
        // [{
        //   label: '# of Votes',
        //   data: [12, 19, 3, 5, 2, 3],
        //   borderWidth: 1
        // }]
    },
    options: {
      responsive: false,
      scales: {
        x: {
          barPercentage: 0.5,
          beginAtZero: true,
          stacked: true,
          grid: {
            display: false
          },
        },
        y: {
            min: 0,                        // 最小値
            max: 15,                       // 最大値
            beginAtZero: true,
            stacked: true,
            ticks: {                       // 目盛り
              count:5,
              autoSkip: false,
              stepSize: 3,                   // 軸間隔
              fontColor: "blue",             // 目盛りの色
              fontSize: 14                   // フォントサイズ
          },
        }
      },
      plugins: {
        title: {
          display: false,
        },
        legend: {
          display: true,
          align: 'end',
        },
        tooltip: {
            enabled: true,
            callbacks: {
              title: function(tooltipData) {
                return tooltipData[0].dataset.label;
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

}

async function loadChartData(nextPrev = null) {
  // 取得条件をAPIに送信
  // グラフデータの取得
  const startDate = startDateInput.value
  const endDate = endDateInput.value
  let $path = ''
  switch (currentView) {
    case 'daily':
      $path = "/manage/marketing/reservation_graph/chart_by_hour"
      break;
    default:
      $path = "/manage/marketing/reservation_graph/chart_by_day"
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


const SELF_PHONE = '電話予約（自）';
const STAFF_PHONE = '電話予約（代）';
const COUNTER = '当日受付予約';
const SELF_WEB = 'ネット予約（自）';
const STAFF_WEB = 'ネット予約（代）';

const barColors = new Map([
  [SELF_PHONE, "#4372b8"],
  [STAFF_PHONE, "#5693c7"],
  [COUNTER, "#6fa756"],
  [SELF_WEB, "#df7a42"],
  [STAFF_WEB, "#f5b338"],
]);
