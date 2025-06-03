<!-- D-2-9 レジ清算集計 -->
@extends('layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb l-print__none">
        <!-- D-1-0にリンク -->
        <li class="l-breadcrumb__list"><a href="">帳票印刷</a></li>
        <li class="l-breadcrumb__list">レジ清算集計</li>
      </ul>

      <div class="l-container__inner">
        <form action="" class="l-print__none u-mb4">
          <div class="l-flex--start l-flex--item-end l-grid--gap2">
            <!-- 日付  -->
            <div class="l-flex--column l-flex--item-start l-grid--gap025">
              <div class="l-flex l-grid--gap05">日付</div>
              <div>
                <input type="date" id="entry_date_start" name="entry_date_start" placeholder="例) 2021/01/01">
                ～
                <input type="date" id="entry_date_fin" name="entry_date_fin" placeholder="例) 2021/01/01">
              </div>
            </div>
            <input type="submit" value="印刷プレビュー" class="c-button c-button--deep-gray hover">
          </div>
        </form>

        <ul class="l-flex l-grid--gap1 u-mb1 l-print__none">
          <li class="c-button--green pointer hover">
            <button id="printButton" class="link-white" onclick="window.print()">印刷</button>
          </li>
        </ul>

        <!-- 統括表（店舗用） -->
        <h2 class="c-title-print__lv2">統括表（店舗用）</h2>
        <h3 class="c-title-print__lv3">取扱台数</h3>
        <table class="l-table-print">
          <thead>
            <tr>
              <th>入庫</th>
              <th>出庫</th>
              <th>保留</th>
              <th>在庫</th>
              <th>長期</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>72</td>
              <td>79</td>
              <td>13</td>
              <td>374</td>
              <td>3</td>
            </tr>
          </tbody>
        </table>

        <h3 class="c-title-print__lv3">入庫詳細</h3>
        <table class="l-table-print">
          <thead>
            <tr>
              <th>成田</th>
              <th>成田IE</th>
              <th>プレ</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>63</td>
              <td>1</td>
              <td>8</td>
            </tr>
          </tbody>
        </table>

        <h3 class="c-title-print__lv3">出庫詳細</h3>
        <table class="l-table-print">
          <thead>
            <tr>
              <th>成田</th>
              <th>成田IE</th>
              <th>プレ</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>69</td>
              <td>3</td>
              <td>7</td>
            </tr>
          </tbody>
        </table>

        <h3 class="c-title-print__lv3">保留明細</h3>
        <table class="l-table-print">
          <thead>
            <tr>
              <th>成田</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>13</td>
            </tr>
          </tbody>
        </table>

        <h3 class="c-title-print__lv3">在庫明細</h3>
        <table class="l-table-print">
          <thead>
            <tr>
              <th>成田</th>
              <th>プレ</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>331</td>
              <td>28</td>
            </tr>
          </tbody>
        </table>

        <h3 class="c-title-print__lv3">一日利用</h3>
        <table class="l-table-print">
          <thead>
            <tr>
              <th>現金その他</th>
              <th>クレ＋前カ＋SBI</th>
              <th>チケット</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>12</td>
              <td>0</td>
              <td>0</td>
            </tr>
          </tbody>
        </table>

        <!-- レジ清算集計 -->
        <h2 class="c-title-print__lv2">レジ清算集計</h2>
        <div>
          <h3 class="c-title-print__lv3">レジ取扱額</h3>
          <table class="l-table-print">
            <tr>
              <td>現金</td>
              <td>34件</td>
              <td>199,820円</td>
            </tr>
            <tr>
              <td>クレ＋前カ＋SBI</td>
              <td>37件</td>
              <td>260,550円</td>
            </tr>
            <tr>
              <td>クーポン</td>
              <td>5件</td>
              <td>16,500円</td>
            </tr>
            <tr>
              <td>チケット</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>振込</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>商品券</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>売掛</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>その他</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>ポイント</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>調整</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>手数料</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>合計</td>
              <td>76件</td>
              <td>476,870円</td>
            </tr>
            <tr>
              <td>後払（入庫）</td>
              <td>2件</td>
              <td>20,220円</td>
            </tr>
            <tr>
              <td>後払（出庫時回収）</td>
              <td>1件</td>
              <td>2,720円</td>
            </tr>
            <tr>
              <td>計+後+回</td>
              <td></td>
              <!-- 空 -->
              <td>484,370円</td>
            </tr>
          </table>

          <!-- レジ取扱明細（単品） -->
          <h3 class="c-title-print__lv3">レジ取扱明細（単品）</h3>
          <table class="l-table-print">
            <tr>
              <td>駐車料金</td>
              <td>34件</td>
              <td>199,820円</td>
            </tr>
            <tr>
              <td>洗車</td>
              <td>37件</td>
              <td>260,550円</td>
            </tr>
            <tr>
              <td>保険</td>
              <td>5件</td>
              <td>16,500円</td>
            </tr>
            <tr>
              <td>旅行用品</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>月極</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>アクア</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>その他</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>合計</td>
              <td>76件</td>
              <td>476,870円</td>
            </tr>
          </table>

          <!-- レジ取扱明細（成田） -->
          <h3 class="c-title-print__lv3">レジ取扱明細（成田）</h3>
          <table class="l-table-print">
            <tr>
              <td>駐車料金</td>
              <td>34件</td>
              <td>199,820円</td>
            </tr>
            <tr>
              <td>洗車</td>
              <td>37件</td>
              <td>260,550円</td>
            </tr>
            <tr>
              <td>保険</td>
              <td>5件</td>
              <td>16,500円</td>
            </tr>
            <tr>
              <td>旅行用品</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>月極</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>アクア</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>その他</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>合計</td>
              <td>76件</td>
              <td>476,870円</td>
            </tr>
          </table>

          <!-- レジ取扱（プレ） -->
          <h3 class="c-title-print__lv3">レジ取扱明細（プレ）</h3>
          <table class="l-table-print">
            <tr>
              <td>駐車料金</td>
              <td>34件</td>
              <td>199,820円</td>
            </tr>
            <tr>
              <td>洗車</td>
              <td>37件</td>
              <td>260,550円</td>
            </tr>
            <tr>
              <td>保険</td>
              <td>5件</td>
              <td>16,500円</td>
            </tr>
            <tr>
              <td>旅行用品</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>月極</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <!-- カテゴリーでまとめる -->
            <tr>
              <td>カテゴリー1</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>その他</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>合計</td>
              <td>76件</td>
              <td>476,870円</td>
            </tr>
          </table>

          <!-- 現金払い -->
          <h3 class="c-title-print__lv3">現金払い</h3>
          <table class="l-table-print">
            <tr>
              <td>成田店</td>
              <td>30件</td>
              <td>179,330円</td>
            </tr>
            <tr>
              <td>プレミア</td>
              <td>3件</td>
              <td>16,640円</td>
            </tr>
            <tr>
              <td>単品</td>
              <td>1件</td>
              <td>3,850円</td>
            </tr>
            <tr>
              <td>合計</td>
              <td>34件</td>
              <td>199,829円</td>
            </tr>
          </table>

          <!-- クレジット＋前カード＋事前決済SBI扱い -->
          <h3 class="c-title-print__lv3">クレジット＋前カード＋事前決済SBI扱い</h3>
          <table class="l-table-print">
            <tr>
              <td>成田店</td>
              <td>32件</td>
              <td>229,830円</td>
            </tr>
            <tr>
              <td>プレミア</td>
              <td>5件</td>
              <td>30,720円</td>
            </tr>
            <tr>
              <td>単品</td>
              <td>0円</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>合計</td>
              <td>37件</td>
              <td>260,550円</td>
            </tr>
          </table>

          <!-- 当日実績があった項目だけが表示される（0件-0円は表示されない） -->
          <h3 class="c-title-print__lv3">割引券内訳</h3>
          <table class="l-table-print">
            <tr>
              <td>Mカード1日券</td>
              <td>2件</td>
            </tr>
          </table>

          <!-- クレジット明細 -->
          <h3 class="c-title-print__lv3">クレジット明細</h3>
          <table class="l-table-print">
            <tr>
              <td>DC</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>JALカード</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <tr>
              <td>楽天</td>
              <td>0件</td>
              <td>0円</td>
            </tr>
            <!-- ...各種カードは省略 -->
          </table>

          <!-- 事前決済クレジット支払 -->
          <h3 class="c-title-print__lv3">事前決済クレジット支払</h3>
          <table class="l-table-print">
            <tr>
              <td>事前決済SBI VISA</td>
              <td>8件</td>
              <td>45,740円</td>
            </tr>
            <tr>
              <td>事前決済SBI JCB</td>
              <td>3件</td>
              <td>21,440円</td>
            </tr>
          </table>
        </div>

        <!-- レジ点検表 -->
        <h2 class="c-title-print__lv2">レジ点検表</h2>
        <div class="checked">
          <!-- 売上 成田 -->
          <h3 class="c-title-print__lv3">売上 成田</h3>
          <table class="l-table-print">
            <thead>
              <tr>
                <th>id</th>
                <th>売上項目</th>
                <th>処理回数</th>
                <th>数量</th>
                <th>金額</th>
                <th>税抜金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>479</td>
                <td>駐車料金（前払い）</td>
                <td>67</td>
                <td>75</td>
                <td>428,810</td>
                <td>389,614</td>
              </tr>
              <tr>
                <td>1</td>
                <td>洗車撥水コート</td>
                <td>644</td>
                <td>2023</td>
                <td>17,940</td>
                <td>6,940</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <!-- 最終行合計 -->
              <tr>
                <td>合計</td>
                <td></td>
                <td></td>
                <td></td>
                <td>41,552</td>
                <td></td>
              </tr>
            </tbody>
          </table>

          <!-- 売上 プレ -->
          <h3 class="c-title-print__lv3">売上 プレ</h3>
          <table class="l-table-print">
            <thead>
              <tr>
                <th>id</th>
                <th>売上項目</th>
                <th>処理回数</th>
                <th>数量</th>
                <th>金額</th>
                <th>税抜金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>479</td>
                <td>駐車料金（前払い）</td>
                <td>67</td>
                <td>75</td>
                <td>428,810</td>
                <td>389,614</td>
              </tr>
              <tr>
                <td>1</td>
                <td>洗車撥水コート</td>
                <td>644</td>
                <td>2023</td>
                <td>17,940</td>
                <td>6,940</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <!-- 最終行合計 -->
              <tr>
                <td>合計</td>
                <td></td>
                <td></td>
                <td></td>
                <td>41,552</td>
                <td></td>
              </tr>
            </tbody>
          </table>

          <!-- 売上 商品のみ -->
          <h3 class="c-title-print__lv3">売上 商品のみ</h3>
          <table class="l-table-print">
            <thead>
              <tr>
                <th>id</th>
                <th>売上項目</th>
                <th>処理回数</th>
                <th>数量</th>
                <th>金額</th>
                <th>税抜金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>479</td>
                <td>駐車料金（前払い）</td>
                <td>67</td>
                <td>75</td>
                <td>428,810</td>
                <td>389,614</td>
              </tr>
              <tr>
                <td>1</td>
                <td>洗車撥水コート</td>
                <td>644</td>
                <td>2023</td>
                <td>17,940</td>
                <td>6,940</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <!-- 最終行合計 -->
              <tr>
                <td>合計</td>
                <td></td>
                <td></td>
                <td></td>
                <td>41,552</td>
                <td></td>
              </tr>
            </tbody>
          </table>

          <!-- 売上合計 -->
          <h3 class="c-title-print__lv3">売上合計</h3>
          <table class="l-table-print">
            <thead>
              <tr>
                <th>id</th>
                <th>売上項目</th>
                <th>処理回数</th>
                <th>数量</th>
                <th>金額</th>
                <th>税抜金額</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>479</td>
                <td>駐車料金（前払い）</td>
                <td>67</td>
                <td>75</td>
                <td>428,810</td>
                <td>389,614</td>
              </tr>
              <tr>
                <td>1</td>
                <td>洗車撥水コート</td>
                <td>644</td>
                <td>2023</td>
                <td>17,940</td>
                <td>6,940</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <tr>
                <td>11</td>
                <td>1日利用（チケット以外）</td>
                <td>1</td>
                <td>1</td>
                <td>7,700</td>
                <td>41,552</td>
              </tr>
              <!-- 最終行合計 -->
              <tr>
                <td>合計</td>
                <td></td>
                <td></td>
                <td></td>
                <td>41,552</td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><!-- l-container__inner -->
    </main>

@endsection