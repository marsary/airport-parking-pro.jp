// レジTOPページの計算機の計算処理を記述
// 現在使用してない

document.addEventListener('DOMContentLoaded', function () {
  const buttonCalculator = document.getElementById('button_calculator');
  const taxDivision = document.getElementById('tax_division');
  const price = document.getElementById('price');
  const total = document.getElementById('total');

  buttonCalculator.addEventListener('click', function() {
    const taxDivisionValue = taxDivision.value;
    const priceValue = price.value;
    const totalValue = taxDivisionValue * priceValue;
    total.textContent = totalValue.toLocaleString() + '円';
  });
});