<!-- D-2-2 -->
<!-- レジ売上帳 -->
@extends('layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb l-print__none">
        <!-- D-1-0にリンク -->
        <li class="l-breadcrumb__list"><a href="">帳票印刷</a></li>
        <li class="l-breadcrumb__list">レジ売上帳</li>
      </ul>

      <div class="l-container__inner">
        <form action="" method="" class="u-mb4 l-print__none">

          <div class="l-flex--column l-flex--item-start l-grid--gap05 u-mb2">
            レジ番号
            <!-- チェックボックス レジ番号 -->
            <div class="l-flex l-grid--gap2">
              <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="1">レジ01
              </label>
              <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="2">レジ02
              </label>
              <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="3">レジ03
              </label>
              <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="4">レジ04
              </label>
              <label class="l-flex l-grid--gap05">
                <input class="u-mb0" type="radio" name="register" value="5">レジ05
              </label>
            </div>
          </div>
          <div class="l-flex--start l-flex--item-end l-grid--gap2">
            <div class="l-flex l-grid--gap2">
              <!-- 日付  -->
              <div class="l-flex--column l-flex--item-start l-grid--gap025">
                <label for="entry_date" class="l-flex l-grid--gap05">日付</label>
                <input type="date" id="entry_date" name="entry_date" placeholder="例) 2021/01/01">
              </div>
              <!-- 時刻  -->
              <div class="l-flex--column l-flex--item-start l-grid--gap025">
                <label for="entry_date" class="l-flex l-grid--gap05">時刻</label>
                <select name="" id="">
                  <option value="all" selected>全て</option>
                  <option value="00:00">00:00</option>
                  <option value="01:00">01:00</option>
                  <option value="02:00">02:00</option>
                  <option value="03:00">03:00</option>
                  <option value="04:00">04:00</option>
                  <option value="05:00">05:00</option>
                  <option value="06:00">06:00</option>
                  <option value="07:00">07:00</option>
                  <option value="08:00">08:00</option>
                  <option value="09:00">09:00</option>
                  <option value="10:00">10:00</option>
                  <option value="11:00">11:00</option>
                  <option value="12:00">12:00</option>
                  <option value="13:00">13:00</option>
                  <option value="14:00">14:00</option>
                  <option value="15:00">15:00</option>
                  <option value="16:00">16:00</option>
                  <option value="17:00">17:00</option>
                  <option value="18:00">18:00</option>
                  <option value="19:00">19:00</option>
                  <option value="20:00">20:00</option>
                  <option value="21:00">21:00</option>
                  <option value="22:00">22:00</option>
                  <option value="23:00">23:00</option>
                </select>
              </div>
            </div>
            <input type="submit" value="印刷プレビュー" class="c-button c-button--deep-gray hover">
          </div>
          <ul class="u-font--md">
            <li>・入庫時に未収が発生したデータは赤字で表示され、名前の後ろに「後」と表示されます。<br>
              正確な意味での後払いではないのでご注意ください。
            <li>・レジで現金の追加・返金が発生した場合に操作を誤ると現金の計算が正しく出来なくなります（入金の現金の預り・釣りが表示されなくなります）。
            </li>
          </ul>
        </form>

        <ul class="l-flex l-grid--gap1 u-mb1 l-print__none">
          <li class="c-button--green pointer hover" id="a4-vertical">A4縦</li>
          <li class="c-button--yellow pointer hover" id="b4-horizontal">B4ヨコ</li>
          <li class="c-button--yellow pointer hover" id="a3-horizontal">A3ヨコ</li>
          <li class="c-button--yellow pointer hover" id="a4-horizontal">A4ヨコ</li>
          <!-- 点検表へのリンクURL未設定 -->
          <li class="c-button--deep-gray"><a href="" class="link-white">点検表へ</a></li>
        </ul>
        <div class="l-table-print__wrap l-print__wrap">
          <table class="l-table-print">
            <thead>
              <tr>
                <th></th>
                <th>受付ID</th>
                <th>時刻</th>
                <th>氏名</th>
                <th>修</th>
                <th>帰</th>
                <th>事</th>
                <th>日</th>
                <th>代理店</th>
                <th>率</th>
                <th>駐車</th>
                <th>券</th>
                <th>マイル</th>
                <th>WAX</th>
                <th>保険</th>
                <th>他</th>
                <th>合計</th>
                <th>現金</th>
                <th>預り</th>
                <th>釣り</th>
                <th>クレ</th>
                <th>クーポ</th>
                <th>前力</th>
                <th>SBI</th>
                <th>他</th>
                <th>合計</th>
                <th>担当</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>01-2311-01044</td>
                <td>05:11</td>
                <td>茂木茂雄</td>
                <td></td>
                <td></td>
                <td>成</td>
                <td>3</td>
                <td>サン公式LINE</td>
                <td>0</td>
                <td>4,620(4,200)</td>
                <td>LINE2</td>
                <td></td>
                <td>◎洗車機シャンプー880</td>
                <td></td>
                <td></td>
                <td>4,620</td>
                <td>4,620</td>
                <td>5,000</td>
                <td>380</td>
                <td></td>
                <td>akippa 10,560</td>
                <td></td>
                <td></td>
                <td></td>
                <td>4,620</td>
                <td>鈴木</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><!-- l-container__inner -->
    </main>
@endsection
@push("scripts")
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const a4VerticalButton = document.getElementById('a4-vertical');
      const b4HorizontalButton = document.getElementById('b4-horizontal');
      const a3HorizontalButton = document.getElementById('a3-horizontal');
      const a4HorizontalButton = document.getElementById('a4-horizontal');

      a4VerticalButton.addEventListener('click', function() {
        setPrintStyle('A4 portrait');
        addPrintClass('a4-vertical-print');
        window.print();
      });

      b4HorizontalButton.addEventListener('click', function() {
        setPrintStyle('B4 landscape');
        addPrintClass('b4-horizontal-print');
        window.print();
      });

      a3HorizontalButton.addEventListener('click', function() {
        setPrintStyle('A3 landscape');
        addPrintClass('a3-horizontal-print');
        window.print();
      });

      a4HorizontalButton.addEventListener('click', function() {
        setPrintStyle('A4 landscape');
        addPrintClass('a4-horizontal-print');
        window.print();
      });

      function setPrintStyle(size) {
        const style = document.createElement('style');
        style.media = 'print';
        style.innerHTML = '@page { size: ' + size + '; margin: 10mm; }';
        document.head.appendChild(style);
      }

      function addPrintClass(className) {
        document.documentElement.classList.remove('a4-vertical-print', 'b4-horizontal-print', 'a3-horizontal-print', 'a4-horizontal-print');
        document.documentElement.classList.add(className);
      }
    });
  </script>
@endpush
