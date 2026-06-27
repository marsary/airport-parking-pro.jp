<footer class="l-footer-user" id="footer">
  <div class="l-footer-user__inner">
    <div>
      <figure class="l-footer-user__logo">
        <img src="{{ asset('images/logo/logo.png') }}" width="100%" alt="logo">
      </figure>
      成田空港駐車場サンパーキング<br>
      〒286-0121 千葉県成田市駒井野 134<br>
      ［営業時間］5：00～22：30<br class="is-none--md" />（成田空港最終便到着まで対応）
    </div>
    <div class="l-flex l-flex--item-start l-flex--center--md l-grid--gap1 u-font--white">
      <a href="https://www.sunparking.co.jp/guide/" target="_blank" class="c-button-user__footer--access">駐車場へのアクセス</a>
      <a href="https://www.sunparking.co.jp/support/" class="c-button-user__footer--contact">お問い合わせ</a>
    </div>
  </div>
  <p class="l-footer-user__copy">© 2026 Sunparking Co., Ltd.</p>
  <button id="gototop" class="c-button__button"><img src="{{ asset('images/icon/chevron-up.svg') }}" width="18px" height="10px" alt=""></button>
</footer>

<script>
  const footer = document.getElementById('footer');
  const gototopButton = document.getElementById('gototop');

  window.addEventListener('scroll', function() {
    const footerTop = footer.offsetTop;
    const scrollPosition = window.scrollY + window.innerHeight;
    if (scrollPosition >= footerTop) {
      gototopButton.classList.add('in-footer');
    } else {
      gototopButton.classList.remove('in-footer');
    }
  });

  // ボタンクリックでページトップに戻る
  gototopButton.addEventListener('click', function() {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
</script>