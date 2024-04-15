const modalArea = document.getElementById('modalArea');
const modalOpen = document.getElementById('modal_open');
const modalClose = document.getElementById('modal_close');
modalOpen.addEventListener('click', function() {
  modalArea.classList.add('is-active');
});
modalClose.addEventListener('click', function() {
  modalArea.classList.remove('is-active');
});