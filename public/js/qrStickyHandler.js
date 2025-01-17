// left　navのQRコードをスクロールで固定する
document.addEventListener('DOMContentLoaded', function() {
  const qrElement = document.querySelector('.c-nav__qr');
  
  function handlePosition() {
    const rect = qrElement.getBoundingClientRect();
    const topPosition = rect.top + window.scrollY;

    if (topPosition >= 600) {
      qrElement.classList.add('--sticky');
    } else {
      qrElement.classList.remove('--sticky');
    }
  }

  // スクロールイベントとリサイズイベントにhandlePosition関数を紐付け
  window.addEventListener('scroll', handlePosition);
  window.addEventListener('resize', handlePosition);

  // 初期表示時にも実行
  handlePosition();
});