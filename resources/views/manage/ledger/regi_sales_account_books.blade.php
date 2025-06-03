<!-- D-2-3 -->
<!-- レジ点検表 -->
@extends('layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb l-print__none">
        <!-- D-1-0にリンク -->
        <li class="l-breadcrumb__list"><a href="">帳票印刷</a></li>
        <li class="l-breadcrumb__list">レジ点検表</li>
      </ul>

      <div class="l-container__inner">
        <form action="" class="u-mb4 l-print__none">
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
            <li>・入金系の金額がマイナスの場合、件数もマイナスされます。</li>
            <li>・レジで現金の追加・返金が発生した場合に操作を誤ると現金の計算が正しく出来なくなります。</li>
          </ul>
        </form>

        <ul class="l-flex l-grid--gap1 u-mb1 l-print__none">
          <li class="c-button--green pointer hover">
            <button id="printButton" class="link-white" onclick="window.print()">印刷</button>
          </li>
          <!-- 点検表へのリンクURL未設定 -->
          <li class="c-button--deep-gray"><a href="" class="link-white">売上帳へ</a></li>
        </ul>
        <div class="l-table-print__wrap l-print__wrap">
          <div>【売上 成田】</div>
          <table class="l-table-print u-mb2">
            <thead>
              <tr>
                <th>ID</th>
                <th>売上項目</th>
                <th>処理回数</th>
                <th>数量</th>
                <th>金額</th>
                <th>税抜金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>駐車料金（前払い）</td>
                <td>6</td>
                <td>6</td>
                <td>30,600</td>
                <td>27,800</td>
              </tr>
            </tbody>
          </table>
          <div>【売上 商品のみ】</div>
          <table class="l-table-print u-mb2">
            <thead>
              <tr>
                <th>ID</th>
                <th>売上項目</th>
                <th>処理回数</th>
                <th>数量</th>
                <th>金額</th>
                <th>税抜金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>駐車料金（前払い）</td>
                <td>6</td>
                <td>6</td>
                <td>30,600</td>
                <td>27,800</td>
              </tr>
            </tbody>
          </table>
          <div>【売上 合計】</div>
          <table class="l-table-print u-mb2">
            <thead>
              <tr>
                <th>ID</th>
                <th>売上項目</th>
                <th>処理回数</th>
                <th>数量</th>
                <th>金額</th>
                <th>税抜金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>駐車料金（前払い）</td>
                <td>6</td>
                <td>6</td>
                <td>30,600</td>
                <td>27,800</td>
              </tr>
            </tbody>
          </table>
          <div>【現金】</div>
          <table class="l-table-print">
            <thead>
              <tr>
                <th></th>
                <th></th>
                <th>処理回数</th>
                <th>金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td>現金</td>
                <td>4</td>
                <td>24,330</td>
              </tr>
              <tr>
                <td></td>
                <td>入金</td>
                <td>4</td>
                <td>24,330</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><!-- l-container__inner -->
    </main>
@endsection