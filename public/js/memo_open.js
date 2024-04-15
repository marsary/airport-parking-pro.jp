// 対象をクリックしたら、吹き出しを表示する

document.addEventListener('DOMContentLoaded', function () {
  const buttonOpenList = document.querySelectorAll('.button_open');
  const buttonCloseList = document.querySelectorAll('.button_close');
  const balloonBoxList = document.querySelectorAll('.c-balloon-memo__box');

  buttonOpenList.forEach(function(buttonOpen, index) {
    buttonOpen.addEventListener('click', function() {
      // 対応する吹き出しの表示
      balloonBoxList[index].classList.add('is-inline-block');
    });
  });

  buttonCloseList.forEach(function(buttonClose, index) {
    buttonClose.addEventListener('click', function() {
      // 吹き出しを閉じる
      balloonBoxList[index].classList.remove('is-inline-block');
    });
  });
});