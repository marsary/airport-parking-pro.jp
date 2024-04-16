<!-- B-1-4  入力確認画面 -->
@extends('layouts.member.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "confirm"])

<div class="p-user-input__inner">
  <form action="reservation_complete.php" method="POST">

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
    </table>

    <!-- 到着予定 -->
    <div class="c-title__table">到着予定</div>
    <table class="l-table-confirm">
      <tr>
        <th>到着予定日</th>
        <td>2024/2/5(月)</td>
        <th>到着予定時間</th>
        <td>16：45</td>
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
        <td>1234567890</td>
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

    <!-- 料金明細 -->
    <div class="c-title__table">料金明細</div>
    <table class="l-table-charge-detail">
      <thead class="l-table-charge-detail__head">
        <tr>
          <th>項目</th>
          <th>単価</th>
          <th>数量</th>
          <th>小計</th>
          <th>消費税</th>
        </tr>
      </thead>
      <tbody class="l-table-charge-detail__body">
        <tr>
          <th>駐車料金</th>
          <td>1,000円</td>
          <td>1</td>
          <td>5,000円</td>
          <td>(税別10%)</td>
        </tr>
        <tr>
          <th>​WAX洗車（オプション料金）</th>
          <td>2,500円</td>
          <td>2</td>
          <td>2,500円</td>
          <td>(税別10%)</td>
        </tr>
        <tr>
          <th>​海外旅行保険（オプション料金）​</th>
          <td>3,800円</td>
          <td>3</td>
          <td>3,800円</td>
          <td>(対象外)</td>
        </tr>
      </tbody>
    </table>

    <div class="l-table-charge-detail--second">
      <div class="l-table-charge-detail--second__column">
        <div>消費税8%</div>
        <div>0円</div>
        <div colspan="2" class="text-right">合計金額</div>
        <div class="u-font--24 u-font--medium">11,300 <span>円</span>（税抜）</div>
        <div>消費税10%</div>
        <div>750円</div>
        <div class="u-font--24 u-font--medium l-grid--colspan2">12,500 <span>円</span>（税込）</div>
      </div>
    </div>

    <!--  -->
    <div class="c-button-group__form u-mt3">
      <button id="returnButton" class="c-button__pagination--return">前のページに戻る</button>
      <button type="submit" class="c-button__pagination--next">お会計へ</button>
    </div>
  </form>
</div><!-- ./p-user-input__inner -->


@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush
