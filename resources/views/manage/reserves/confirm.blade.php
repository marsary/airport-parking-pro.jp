<!-- B-2-7 予約管理TOP > 受付入力 > 予約内容確認 -->
@extends('layouts.manage.authenticated')

@section('content')

<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">予約管理TOP</li>
    <li class="l-breadcrumb__list">受付入力</li>
    <li class="l-breadcrumb__list">予約内容確認</li>
  </ul>

  <div class="l-container__inner">

    <form action="/register/register_index.php" method="POST">

      <div class="c-title__table">予約情報</div>
      <table class="l-table-confirm">
        <tr>
          <th>予約コード</th>
          <td>1234567890</td>
          <th>受付コード</th>
          <td>1234567890</td>
          <th>予約日時</th>
          <td>2024/1/15(月)20:12</td>
          <th>予約経路</th>
          <td>公式HP</td>
        </tr>
        <tr>
          <th>入庫日時</th>
          <td>2024/2/1(木) 10:35</td>
          <th>出庫予定日</th>
          <td>2024/2/1(木)</td>
          <th>利用日数</th>
          <td>5日</td>
        </tr>
      </table>

      <!-- 顧客情報 -->
      <div class="c-title__table">顧客情報</div>
      <table class="l-table-confirm">
        <tr>
          <th>顧客コード</th>
          <td>1234567890</td>
          <th>お客様氏名</th>
          <td>サン太郎</td>
          <th>ふりがな</th>
          <td>さんたろう</td>
          <th>利用回数</th>
          <td>8回</td>
        </tr>
        <tr>
          <th>会員ランク</th>
          <td>シルバー</td>
          <th>ラベル2</th>
          <td>ダミーダミー</td>
          <th>ラベル3</th>
          <td>ダミーダミー</td>
          <th>ラベル4</th>
          <td>ダミーダミー</td>
        </tr>
        <tr>
          <th>郵便番号</th>
          <td>111-0000</td>
          <th>電話番号</th>
          <td>090-1234-5678</td>
          <!-- 以下2つは桁数次第ではレイアウトが崩れる分けてもよいかも -->
          <th>Mail</th>
          <td>testaaatestaaaatestaaatest@test.jp</td>
          <th>LINE ID</th>
          <td>sun123</td>
        </tr>
        <tr>
          <th>領収書のあて名</th>
          <td colspan="3">ダミーダミーダミー</td>
        </tr>
      </table>

      <!-- 到着予定 -->
      <div class="c-title__table">到着予定</div>
      <table class="l-table-confirm">
        <tr>
          <th>到着予定日</th>
          <td>2024/2/5(月)</td>
          <th>到着予定時間</th>
          <td>16:45 <span class="c-label--delay">遅延</span> </td>
          <th>到着便</th>
          <td>NH205</td>
          <th>航空会社</th>
          <td>ANA</td>
        </tr>
        <tr>
          <th>出発空港</th>
          <td>LAX</td>
          <th>到着空港</th>
          <td>NRT</td>
          <th>到着ターミナル</th>
          <td>2</td>
          <td colspan="3">
            <div class="c-label--lg">到着日とお迎え日が異なる</div>
          </td>
        </tr>
      </table>

      <!-- 車両情報 -->
      <div class="c-title__table">車両情報</div>
      <table class="l-table-confirm">
        <tr>
          <th>メーカー</th>
          <td>BMW</td>
          <th>車種</th>
          <td>BMW5</td>
          <th>車番</th>
          <td>1234</td>
          <th>色</th>
          <td>黒</td>
        </tr>
        <tr>
          <th>区分</th>
          <td>普通</td>
          <th>人数</th>
          <td>3名</td>
          <th>車両取扱</th>
          <td colspan="3">MT車</td>
        </tr>
        <tr>
          <th>備考</th>
          <td>文字が長い場合があるので独立行にした</td>
        </tr>
      </table>


      <!--  -->
      <div class="c-button-group__form u-mt3">
        <button id="returnButton" class="c-button__submit--dark-gray u-h50">修正する</button>
        <button type="submit" value="1" id="confirmButton" class="c-button__submit--green u-h50">予約を完了する</button>
        <button type="submit" value="0" class="c-button__pagination--next">お会計へ</button>
      </div>
    </form>
  </div><!-- /.l-container__inner -->

</main><!-- /.l-container__wrap -->


@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush
