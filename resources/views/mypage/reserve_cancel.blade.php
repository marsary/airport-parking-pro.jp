@extends('layouts.mypage.authenticated')
@section('content')
  <div class="p-user-input__inner">
    <!-- パンくず -->
    <ul class="p-user-breadcrumb">
      <li class="p-user-breadcrumb__list"><a href="/user/dashboard.php">マイページ</a></li>
      <li class="p-user-breadcrumb__list">予約キャンセル確認</li>
    </ul>

    <div class="c-form__admin--title u-mb1">予約キャンセル</div>
    <p>
      下記予約をキャンセルいたします。お間違いがなければキャンセルボタンを押してください。
    </p>
    <!-- 予約情報 -->
    <div class="c-title__user">予約情報</div>
    <table class="l-user-table-confirm">
      <tr>
        <th>取引コード</th>
        <td>1234567890</td>
        <th>予約日時</th>
        <td>2024/1/15(月)20:12</td>
      </tr>
      <tr>
        <th>入庫日時</th>
        <td>2024/2/1(木) 10:35</td>
        <th>出庫予定日</th>
        <td>2024/2/1(木)</td>
        <th>利用日数</th>
        <td>5日</td>
        <th>人数</th>
        <td>3名</td>
      </tr>
    </table>

    <!-- 顧客情報 -->
    <div class="c-title__user">顧客情報</div>
    <table class="l-user-table-confirm">
      <tr>
        <th>顧客コード</th>
        <td>1234567890</td>
        <th>お客様氏名</th>
        <td>サン太郎</td>
        <th>ふりがな</th>
        <td>さんたろう</td>
        <th>利用回数</th>
        <td>8回</td>
        <th>会員ランク</th>
        <td>シルバー</td>
        <th>ラベル2</th>
        <td>ダミーダミー</td>
        <th>ラベル3</th>
        <td>ダミーダミー</td>
        <th>ラベル4</th>
        <td>ダミーダミー</td>
        <th>郵便番号</th>
        <td>111-0000</td>
        <th>電話番号</th>
        <td>090-1234-5678</td>
        <th>Mail</th>
        <td>testaaatestaaaatestaaatest@test.jp</td>
        <th>領収書の宛名</th>
        <td>ダミーテキストダミーテキストダミーテキスト株式会社</td>
      </tr>
    </table>

    <div>備考</div>
    <textarea name="" id="" rows="4" class="u-w-full-wide u-max-350" disabled>ダミーテキストダミーテキストダミーテキスト</textarea>

    <!-- 到着予定 -->
    <div class="c-title__table">到着予定</div>
    <table class="l-user-table-confirm">
      <tr>
        <th>到着予定日</th>
        <td>2024/2/5(月)</td>
        <th>到着予定時間</th>
        <td>16:45</td>
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
        <td class="--label"><!-- この項目のみspan->2 -->
          <div class="c-label--lg">到着日とお迎え日が異なる</div>
        </td>
      </tr>
    </table>

    <!-- 車両情報 -->
    <div class="c-title__table">車両情報</div>
    <table class="l-user-table-confirm">
      <tr>
        <th>車両コード</th>
        <td>1234567890</td>
        <th>メーカー</th>
        <td>BMW</td>
        <th>車種</th>
        <td>BMW5</td>
        <th>ナンバー</th>
        <td>1234</td>
        <th>色</th>
        <td>黒</td>
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
          <td class="u-font-nowrap">1,000円</td>
          <td>1</td>
          <td class="u-font-nowrap">5,000円</td>
          <td class="--tax">(税別10%)</td>
        </tr>
        <tr>
          <th>​WAX洗車（オプション料金）</th>
          <td class="u-font-nowrap">2,500円</td>
          <td>2</td>
          <td class="u-font-nowrap">2,500円</td>
          <td class="--tax">(税別10%)</td>
        </tr>
        <tr>
          <th>​海外旅行保険（オプション料金）​</th>
          <td class="u-font-nowrap">3,800円</td>
          <td>3</td>
          <td class="u-font-nowrap">3,800円</td>
          <td class="--tax">(対象外)</td>
        </tr>
      </tbody>
    </table>

    <div class="l-table-charge-detail--second">
      <div>消費税8%</div>
      <div>0円</div>
      <div></div>
      <div>消費税10%</div>
      <div>750円</div>
      <div></div>
      <div colspan="2" class="text-right">合計金額</div>
      <div class="u-font--lg u-font--medium">11,300<span class="u-font--18">円</span></div>
      <div class="u-font--normal">（税抜）</div>
      <div class="u-font--lg u-font--medium l-grid--colspan2">12,500<span class="u-font--18">円</span></div>
      <div class="u-font--normal">（税込）</div>
    </div>
    <!-- 料金明細 fin -->

    <div class="l-flex--column l-grid--rgap1 l-flex--center u-mt2">
      <button class="c-button__submit">予約キャンセル</button><!-- アラート表示させるという指示を受けています -->
      <a href="/user/n-5.php" class="c-button__submit">マイページに戻る</a>
    </div>

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