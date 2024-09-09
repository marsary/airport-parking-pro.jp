let currentStartDate;
let currentEndDate;
let currentView;
let chart;
let legendItems;

/**
 * スイッチの状態に基づいて、グラフのデータセットの表示を切り替え
 *
 * @param {HTMLElement} switchElem - スイッチの要素
 * @param {string} switchLabel - スイッチに関連付けられたラベルテキスト
 * @return {void}
 */
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

  /**
   * 現在のビューに応じて、グラフのタイトルを設定
   *
   * @return {void}
   */
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

  /**
   * ラベルに基づいて、現在の期間を設定
   *
   * @param {Array<string>} labels - 期間のラベル（開始日と終了日を含む配列）
   * @return {void}
   */
  function setCurrentPeriod(labels = []) {
    if(typeof labels[0] !== 'undefined') {
      currentStartDate = luxon.DateTime.fromSQL(labels[0]);
    }
    if(labels.length > 0 && typeof labels[labels.length - 1] != null) {
      currentEndDate = luxon.DateTime.fromSQL(labels[labels.length - 1]);
    }

  }

  /**
   * 取得したJSONデータを元にグラフを更新
   *
   * @param {Object} json - 取得したJSONデータ
   * @return {void}
   */
  function handleChartData(json) {
    if(json.success){
      setCurrentPeriod(json.data.labels)
      console.log(json.data)
      renderChart(json.data)
    }
  }


  /**
   * 日次データを処理してグラフを更新
   *
   * @param {Object} json - 日次データを含むJSON
   * @return {void}
   */
  function handleDailyChartData(json) {
    if(json.success){
      currentStartDate = luxon.DateTime.fromISO(json.data.currentDate);
      currentEndDate = currentStartDate.set();
      console.log(json.data)
      renderChart(json.data, false)
    }
  }

/**
 * チェックボックスの状態に基づいて、データセットをフィルタリング
 *
 * @return {void}
 */
function filterDatasets() {
    const checkboxes = Array.from(document.querySelectorAll('input.c-button-toggle__input[type="checkbox"]'));
    checkboxes.map((checkbox) => {
      const liElem = checkbox.closest('li');
      if(!liElem) return;

      const legendItem = legendItems.find(legendItem => legendItem.text == liElem.textContent.trim())

      if(!legendItem) {
        return;
        // throw new Error(liElem.textContent.trim() + 'に対応するチャートのlegend項目が取得できません。');
      }

      if(checkbox.checked) {
        chart.setDatasetVisibility(legendItem.datasetIndex, true);

      } else {
        chart.setDatasetVisibility(legendItem.datasetIndex, false);
      }
    })
    chart.update();
  }
