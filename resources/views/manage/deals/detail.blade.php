<!-- B-3-3 予約詳細 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list">予約詳細</li>
    </ul>
    <div class="l-container__inner">

      <div class="l-breadcrumb-step__wrap">
        <div class="l-breadcrumb-step__item-gray">予約済み</div>
        <div class="l-breadcrumb-step__item-gray">入庫済</div>
        <div class="l-breadcrumb-step__item-gray --current">出庫済</div>
      </div>

      <div class="c-title__table">予約情報</div>
      <div class="l-table-top-parent">
        <div class="l-table-top-list">
          <div class="l-table-top-item u-bg--red">追加清算あり[1,500円]</div>
          <div class="l-table-top-item u-bg--yellow">保留: 3日</div>
        </div>
        <table class=" l-table-confirm">
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
            <th>入庫日時予定</th>
            <td>2024/1/31(水) 10:00</td>
            <th>入庫日時</th>
            <td>2024/2/1(木) 10:35</td>
            <th>出庫予定日</th>
            <td>2024/2/1(木)</td>
            <th>利用日数</th>
            <td>5日</td>
          </tr>
        </table>
      </div>

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
          <td>16:45<span class="c-label--delay">遅延</span></td>
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
          <td colspan="3">文字が長い場合があるので独立行にした</td>
        </tr>
      </table>

      <a href="/admin/reservation_edit.php" class="u-mb3 u-horizontal-auto c-link-no-border c-button__submit--dark-gray">予約内容を変更</a>

      <form action="" method="POST">
        <div class="u-p2 u-bg--light-gray l-grid--col3 l-grid--gap2">
          <div>
            <label class="c-title__table">顧客メモ</label>
            <textarea name="customer-note" id="customer-note" class="u-bg--white u-w-full-wide u-mb05" rows="3"></textarea>
            <button type="summit" class="c-button--sm--text-color u-horizontal-auto">保存</button>
          </div>
          <div>
            <label class="c-title__table">予約メモ</label>
            <textarea name="reservation-note" id="reservation-note" class="u-bg--white u-w-full-wide u-mb05" rows="3"></textarea>
            <button type="summit" class="c-button--sm--text-color u-horizontal-auto">保存</button>
          </div>
          <div>
            <label class="c-title__table">受付メモ</label>
            <textarea name="reception-note" id="reception-note" class="u-bg--white u-w-full-wide u-mb05" rows="3"></textarea>
            <button type="summit" class="c-button--sm--text-color u-horizontal-auto">保存</button>
          </div>
        </div>
      </form>

      <!--  -->
      <div class="c-button-group__form u-mt3">
        <a href="" class="c-button__submit--gray c-link-no-border">追加精算に進む</a>
        <a href="" class="c-button__pagination--next c-link-no-border">出庫済</a>
      </div>

    </div><!-- ./p-user-container__inner -->

</main><!-- /.l-wrap__main -->
<!-- サイド固定ボタン -->
<button onclick="window.print(); return false;" class="c-button__right-fixed--gray"><img src="{{ asset('images/icon/print.svg') }}" width="30" height="32" /></button>


@endsection
@push("scripts")
<script>

</script>
@endpush
@push('css')
<style>
</style>
@endpush
