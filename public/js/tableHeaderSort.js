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