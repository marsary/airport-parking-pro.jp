document.addEventListener('DOMContentLoaded', function() {
  // テーブルヘッダーのソートボタンを全て選択
  var sortButtons = document.querySelectorAll('.l-table-list__head th div.c-button-sort');

  // ソートボタンクリック時の処理
  function handleSortClick(event) {
      var clickedButton = event.currentTarget;

      if (clickedButton.classList.contains('--asc')) {
          // 昇順から降順へ
          clickedButton.classList.remove('--asc');
          clickedButton.classList.add('--desc');
      } else if (clickedButton.classList.contains('--desc')) {
          // 降順から昇順へ
          clickedButton.classList.remove('--desc');
          clickedButton.classList.add('--asc');
      } else {
          // 他の全てのボタンから昇順・降順クラスを削除
          sortButtons.forEach(function(button) {
              button.classList.remove('--asc', '--desc');
          });
          // クリックされたボタンを昇順に
          clickedButton.classList.add('--asc');
      }
  }

  // 各ソートボタンにクリックイベントリスナーを追加
  sortButtons.forEach(function(button) {
      button.addEventListener('click', handleSortClick);
  });
});

/**
 * テーブルの行をクリックされた列に基づいてソート
 * 現在のソート状態に応じて、昇順または降順を切り替え。
 *
 * @param {Event} e - クリックイベントからのイベントオブジェクト。テーブルヘッダのクリックが発生元である必要
 * @param {string} tableSelector - テーブル識別するための CSS セレクタ
 */
function sortRows(e, tableSelector) {
    const cell = e.target.parentElement;
    const table = document.querySelector(tableSelector);
    const records = [];
    for (let i = 1; i < table.rows.length; i++) {
        const record = {};
        record.row = table.rows[i];
        record.key = table.rows[i].cells[cell.cellIndex].textContent;
        records.push(record);
    }
    if (e.target.classList.contains('--asc')) {
        records.sort(compareKeysReverse);
    } else {
        records.sort(compareKeys);
    }
    for (let i = 0; i < records.length; i++) {
        table.querySelector('tbody').appendChild(records[i].row);
    }
}

function compareKeys(a, b) {
    if (a.key < b.key) return -1;
    if (a.key > b.key) return 1;
    return 0;
}

function compareKeysReverse(a, b) {
    if (a.key < b.key) return 1;
    if (a.key > b.key) return -1;
    return 0;
}
