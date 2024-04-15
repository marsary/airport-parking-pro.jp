// 入出庫の表示切り替え等
//　最初から[.contentOne]が表示される

const buttonOne = document.getElementById('buttonOne');
const buttonTwo = document.getElementById('buttonTwo');
const contentOne = document.querySelector('.contentOne');
const contentTwo = document.querySelector('.contentTwo');

buttonOne.addEventListener('click', function() {
  buttonOne.classList.add('is-active');
  contentOne.classList.add('is-active');
  contentOne.classList.remove('is-none');
  buttonTwo.classList.remove('is-active');
  contentTwo.classList.remove('is-active');
  contentTwo.classList.add('is-none');
  
});

buttonTwo.addEventListener('click', function() {
  buttonTwo.classList.add('is-active');
  contentTwo.classList.add('is-active');
  contentTwo.classList.remove('is-none');
  buttonOne.classList.remove('is-active');
  contentOne.classList.remove('is-active');
  contentOne.classList.add('is-none');
});