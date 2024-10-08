// close_buttonをクリックしたらis-activeをremoveしてis-noneをaddする
// 最初は非表示で、close_buttonをクリックすると表示されるようにする

const closeButtons = document.querySelectorAll('.close_button');
const toggleArea = document.querySelectorAll('.is-active');

closeButtons.forEach((closeButton, index) => {
  closeButton.addEventListener('click', () => {
    toggleArea[index].classList.toggle('is-none');
    closeButton.textContent = closeButton.textContent === '閉じる' ? '開く' : '閉じる';
    closeButton.classList.toggle('c-button__open');
    closeButton.classList.toggle('c-button__close');
  });
});

// シンプルな表示非表示の切り替え
const openButton    = document.getElementById('open_button');
const closeButton   = document.getElementById('close_button');
const toggleElement = document.getElementById('toggle_element');

openButton?.addEventListener('click', () => {
  toggleElement.classList.remove('is-none');
});
closeButton?.addEventListener('click', () => {
  toggleElement.classList.add('is-none');
});
