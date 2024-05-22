  <!-- footer -->
  <footer id="footer" class="footer">

    <div class="footer__wrap">
      <ul class="footer-nav">
        <li class="footer-nav__item"><a href="/ag/">TOP</a></li>
        <li class="footer-nav__item"><a href="/ag/company.php?o_id=<?=$_SESSION['office_id']?>">サンパーキングについて</a></li>
        <li class="footer-nav__item"><a href="/ag/security.php?o_id=<?=$_SESSION['office_id']?>">当サイトのセキュリティについて</a></li>
        <li class="footer-nav__item"><a href="/ag/rule.php?o_id=<?=$_SESSION['office_id']?>">免責約款</a></li>
        <li class="footer-nav__item"><a href="/ag/inquiry.php?o_id=<?=$_SESSION['office_id']?>">お問い合わせ</a></li>
        <li class="footer-nav__item"><a href="/form/rsv1.php?o_id=<?=$_SESSION['office_id']?>">予約フォーム</a></li>
        <li class="footer-nav__item"><a href="/ag/cookie.php?o_id=<?=$_SESSION['office_id']?>">Cookieの設定</a></li>
        <li class="footer-nav__item"><a href="/ag/sitepolicy.php?o_id=<?=$_SESSION['office_id']?>">サイトポリシー・プライバシーポリシー</a></li>
        <li class="footer-nav__item"><a href="/ag/notation.php?o_id=<?=$_SESSION['office_id']?>">特定商取引法に基づく表記</a></li>
      </ul>

      <p class="copyright">Copyright © Sunport Co., Ltd. All rights reserved.</p>
    </div>

    <!-- Go To Top Button -->
    <button id="scroll-to-top-btn">PAGE<br />TOP</button>

  </footer>

  <!-- 固定ボタン -->
  <ul class="button-fixed">
    <li class="button-fixed__item --orange"><a href="/ag/price.php?o_id=<?=$_SESSION['office_id']?>" target="_blank" rel="noreferrer noopener">料金確認</a></li>
    <li class="button-fixed__item --purple"><a href="/ag/inquiry.php?o_id=<?=$_SESSION['office_id']?>" target="_blank" rel="noreferrer noopener">お問い合わせ</a></li>
  </ul>

  <!-- Go To Top Button -->
  <script>
    //ボタン
    const scroll_to_top_btn = document.querySelector('#scroll-to-top-btn');

    //クリックイベントを追加
    scroll_to_top_btn.addEventListener('click', scroll_to_top);

    function scroll_to_top() {
      window.scroll({
        top: 0,
        behavior: 'smooth'
      });
    };
  </script>

  <!-- コンテンツに内容がない時、footerを下部固定 -->
  <script>
    new function() {

      var footerId = "footer";
      //メイン
      function footerFixed() {
        //ドキュメントの高さ
        var dh = document.getElementsByTagName("body")[0].clientHeight;
        //フッターのtopからの位置
        document.getElementById(footerId).style.top = "0px";
        var ft = document.getElementById(footerId).offsetTop;
        //フッターの高さ
        var fh = document.getElementById(footerId).offsetHeight;
        //ウィンドウの高さ
        if (window.innerHeight) {
          var wh = window.innerHeight;
        } else if (document.documentElement && document.documentElement.clientHeight != 0) {
          var wh = document.documentElement.clientHeight;
        }
        if (ft + fh < wh) {
          document.getElementById(footerId).style.position = "relative";
          document.getElementById(footerId).style.top = (wh - fh - ft - 1) + "px";
        }
      }

      //文字サイズ
      function checkFontSize(func) {

        //判定要素の追加	
        var e = document.createElement("div");
        var s = document.createTextNode("S");
        e.appendChild(s);
        e.style.visibility = "hidden"
        e.style.position = "absolute"
        e.style.top = "0"
        document.body.appendChild(e);
        var defHeight = e.offsetHeight;

        //判定関数
        function checkBoxSize() {
          if (defHeight != e.offsetHeight) {
            func();
            defHeight = e.offsetHeight;
          }
        }
        setInterval(checkBoxSize, 1000)
      }

      //イベントリスナー
      function addEvent(elm, listener, fn) {
        try {
          elm.addEventListener(listener, fn, false);
        } catch (e) {
          elm.attachEvent("on" + listener, fn);
        }
      }

      addEvent(window, "load", footerFixed);
      addEvent(window, "load", function() {
        checkFontSize(footerFixed);
      });
      addEvent(window, "resize", footerFixed);

    }
  </script>