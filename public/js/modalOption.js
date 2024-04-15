// オプション選択したらモーダル表示

const modalAreaOption = document.getElementById('modalAreaOption');
const modalCloseOption = document.querySelectorAll('.modal_optionClose');
const modalButtons = document.querySelectorAll('.button_select');

modalButtons.forEach(button => {
  button.addEventListener('click', function () {
    modalAreaOption.classList.add('is-active');
  });
});

modalCloseOption.forEach(button => {
  button.addEventListener('click', function () {
    modalAreaOption.classList.remove('is-active');
  });
})