// 合計金額を更新
function updateTotalAmount(input) {
    const modalAreaOption = input.closest('.modal-option');
    const inputs = modalAreaOption.querySelectorAll('.c-buttonQuantity__input');
    let totalAmount = 0;

    inputs.forEach(input => {
      const quantity = parseInt(input.value, 10);
      const price = parseInt(input.getAttribute('data-price'), 10);
      totalAmount += quantity * price;
    });

    modalAreaOption.querySelector('.total-amount').textContent = totalAmount;
  }

  function asObject(value = []) {
    if(value != null && value.constructor.name === "Object") {
        return value;
    }
    if(Array.isArray(value)) {
        return { ...value }
    }
    return {};
  }
