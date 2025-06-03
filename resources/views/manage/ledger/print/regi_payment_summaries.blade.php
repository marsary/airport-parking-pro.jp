<!-- D-2-2レジ点検表・D-2-3レジ売上帳 D-2-5 D-2-9 -->

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>レジ清算集計</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
  </head>
  <body>
    <!-- 統括表（店舗用） -->
    <h2>統括表（店舗用）</h2>
    <h3>取扱台数</h3>
    <table>
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
    <h3>入庫詳細</h3>
    <table>
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

    <h3>出庫詳細</h3>
    <table>
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

    <h3>保留明細</h3>
    <table>
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

    <h3>在庫明細</h3>
    <table>
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

    <h3>一日利用</h3>
    <table>
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
    <h2>レジ清算集計</h2>
    <div class="regi">
      <h3>レジ取扱額</h3>
      <table>
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
      <h3>レジ取扱明細（単品）</h3>
      <table>
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
      <h3>レジ取扱明細（成田）</h3>
      <table>
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
      <h3>レジ取扱明細（プレ）</h3>
      <table>
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
      <h3>現金払い</h3>
      <table>
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
      <h3>クレジット＋前カード＋事前決済SBI扱い</h3>
      <table>
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
      <h3>割引券内訳</h3>
      <table>
        <tr>
          <td>Mカード1日券</td>
          <td>2件</td>
        </tr>
      </table>

      <!-- クレジット明細 -->
      <h3>クレジット明細</h3>
      <table>
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
      <h3>事前決済クレジット支払</h3>
      <table>
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

  </body>
</html>
<style>
  body {
    max-width: 1040px;
    margin: 0 auto;
    padding: 20px;
    font-size: 12px;
  }
  h2 {
    margin-top: 2rem;
    margin-bottom: 0.5rem;
  }
  h2:first-child {
    margin-top: 0;
  }
  h3 {
    margin-bottom: 0.25rem;
  }
  table {
    border-collapse: collapse;
    /* width: 100%; */
    margin-bottom: 0.5rem;
  }
  th,
  td {
    border: 1px solid black;
    padding: 2px 4px;
    text-align: left;
    width: 200px;
  }
  th {
    background-color: #f2f2f2;
  }

  .checked th:first-child,
  .checked td:first-child {
    width: 100px;
  }
  .checked th:nth-child(2),
  .checked td:nth-child(2) {
    width: 500px;
  }
</style>
</head>
