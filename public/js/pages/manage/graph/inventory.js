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
let chartDataset = [];

monthlyChartBtn.addEventListener('click', async (e)=> {
  currentView = "monthly"
  currentStartDate = luxon.DateTime.fromJSDate(new Date()).startOf('month');
  json = await loadChartData()

  handleChartData(json)
})


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

