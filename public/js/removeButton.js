
document.addEventListener('DOMContentLoaded', function() {
  const removeButtons = document.querySelectorAll('.button_remove');

  removeButtons.forEach(button => {
    button.addEventListener('click', function() {
      const parent = this.closest('.item-container');
      if (parent) {
        parent.remove();
      }
    });
  });
});
