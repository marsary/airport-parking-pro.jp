<!-- B-3-1 予約管理TOP > 検索 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">

  <!-- パンくず -->
  <ul class="l-breadcrumb l-wrap__breadcrumb">
    <li class="l-breadcrumb__list">予約管理TOP</li>
    <li class="l-breadcrumb__list">検索</li>
  </ul>

  <div class="l-container__inner">
    <form action="/admin/reservation_search_result.php" id="form1" method="POST" class="c-button-fixed__parent u-pb6">

      <ul class="l-flex--end l-grid--cgap1">
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="reservation" name="reservation" class="u-mb0"><label for="reservation">予約中</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="today_arrival" name="today_arrival" class="u-mb0"><label for="today_arrival">本日入庫予定</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="in_storage" name="in_storage" class="u-mb0"><label for="in_storage">入庫中</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="today_departure" name="today_departure" class="u-mb0"><label for="today_departure">本日出庫予定</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="pending" name="pending" class="u-mb0"><label for="pending">保留</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="departure" name="departure" class="u-mb0"><label for="departure">出庫済</label>
        </li>
      </ul>

      <div class="c-title__table">予約情報</div>

      <table class="l-table-input">
        <tr>
          <th><label for="reservation_code">予約コード</label></th>
          <td><input type="text" id="reservation_code" name="reservation_code" placeholder="1234567890" /></td>
          <!-- 受付コード -->
          <th><label for="reception_code">受付コード</label></th>
          <td><input type="text" id="reception_code" name="reception_code" placeholder="1234567890" /></td>
          <!-- 予約日時 -->
          <th><label for="reservation_date">予約日時</label></th>
          <td><input type="text" id="reservation_date" name="reservation_date" placeholder="2024/1/15(月)20:12" /></td>
          <th>予約経路</th>
          <td>
            <div class="c-form-select-wrap">
              <select>
                <!-- デフォルト 選択不可 -->
                <option value="" selected disabled>未選択</option>
                <option value="公式HP">公式HP</option>
                <option value="公式HP">代理店</option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <!-- 入庫日 -->
          <th><label for="storage_date">入庫日</label></th>
          <td colspan="3">
            <input type="date" id="storage_date_forward" class="u-w-auto" name="storage_date" />～
            <input type="date" id="storage_date_backward" class="u-w-auto" name="storage_date" />
          </td>
          <!-- 入庫時間 -->
          <th><label for="storage_time">入庫時間</label></th>
          <td colspan="3">
            <input type="time" id="storage_time_forward" class="u-w-auto" name="storage_time" placeholder="16:45" />～
            <input type="time" id="storage_time_backward" class="u-w-auto" name="storage_time" placeholder="16:45" />
        </tr>
        <!-- 予定日 -->
        <tr>
          <th><label for="departure_scheduled_date">出庫予定日</label></th>
          <td colspan="3">
            <input type="date" id="departure_scheduled_date_forward" class="u-w-auto" name="departure_scheduled_date" />～
            <input type="date" id="departure_scheduled_date_backward" class="u-w-auto" name="departure_scheduled_date" />
          </td>
          <th><label for="departure_date">出庫日</label></th>
          <td colspan="3">
            <input type="date" id="departure_date_forward" class="u-w-auto" name="departure_date" />～
            <input type="date" id="departure_date_backward" class="u-w-auto" name="departure_date" />
          </td>
        </tr>
        <tr>
          <!-- 利用日数 -->
          <th><label for="use_days">利用日数</label></th>
          <td colspan="6">
            <input type="text" id="use_days_forward" name="use_days" placeholder="3" class="u-w50" />日間～
            <input type="text" id="use_days_backward" name="use_days" placeholder="6" class="u-w50">日間
          </td>
        </tr>
      </table>

      <!-- 顧客情報 -->
      <div class="c-title__table">顧客情報</div>
      <table class="l-table-input">
        <tr>
          <!-- 顧客コード -->
          <th><label for="customer_code">顧客コード</label></th>
          <td><input type="text" id="customer_code" name="customer_code" placeholder="1234567890" /></td>
          <!-- お客様氏名 -->
          <th><label for="customer_name">お客様氏名</label></th>
          <td><input type="text" id="customer_name" name="customer_name" placeholder="サン太郎" /></td>
          <!-- ふりがな -->
          <th><label for="furigana">ふりがな</label></th>
          <td><input type="text" id="furigana" name="furigana" placeholder="さんたろう" /></td>
          <!-- 利用回数 -->
          <th><label for="use_count">利用回数</label></th>
          <td><input type="text" id="use_count" name="use_count" placeholder="8回" /></td>
        </tr>
        <tr>
          <!-- 会員ランク -->
          <th><label for="member_rank">会員ランク</label></th>
          <td><input type="text" id="member_rank" name="member_rank" placeholder="シルバー" /></td>
          <!-- ラベル2 -->
          <th><label for="label2">ラベル2</label></th>
          <td>
            <div class="c-form-select-wrap">
              <select name="label2" id="label2">
                <option value="" selected>未選択</option>
                <option value="">ダミーダミー</option>
              </select>
            </div>
          </td>
          <!-- ラベル3 -->
          <th><label for="label3">ラベル3</label></th>
          <td>
            <div class="c-form-select-wrap">
              <select name="label3" id="label3">
                <option value="" selected>未選択</option>
                <option value="">ダミーダミー</option>
              </select>
            </div>
          </td>
          <!-- ラベル4 -->
          <th><label for="label4">ラベル4</label></th>
          <td>
            <div class="c-form-select-wrap">
              <select name="label4" id="label4">
                <option value="" selected>未選択</option>
                <option value="">ダミーダミー</option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <!-- 郵便番号 -->
          <th><label for="postal_code">郵便番号</label></th>
          <td><input type="text" id="postal_code" name="postal_code" placeholder="111-0000" /></td>
          <!-- 電話番号 -->
          <th><label for="tel">電話番号</label></th>
          <td><input type="text" id="tel" name="tel" placeholder="090-1234-5678" /></td>
          <!-- 以下2つは桁数次第ではレイアウトが崩れる分けてもよいかも -->
          <!-- Mail -->
          <th><label for="mail">Mail</label></th>
          <td><input type="text" id="mail" name="mail" placeholder="example@aaa.com" /></td>
          <!-- LINE ID -->
          <th><label for="line_id">LINE ID</label></th>
          <td><input type="text" id="line_id" name="line_id" placeholder="sun123" /></td>
        </tr>
        <tr>
          <!-- 領収書の宛名 -->
          <th><label for="receipt_name">領収書の宛名</label></th>
          <td colspan="3"><input type="text" id="receipt_name" name="receipt_name" placeholder="サン太郎" /></td>
        </tr>
      </table>

      <!-- 到着予定 -->
      <div class="c-title__table">到着予定</div>
      <table class="l-table-input">
        <tr>
          <!-- 到着予定日 -->
          <th>到着予定日</th>
          <td colspan="3">
            <input type="text" id="arrival_date" class="u-w-auto" name="arrival_date_forward" placeholder="2024/2/5(月)" />～
            <input type="text" id="arrival_date" class="u-w-auto" name="arrival_date_backward" placeholder="2024/2/5(月)" />
          </td>
          <!-- 到着予定時間 -->
          <th>到着予定時間</th>
          <td colspan="3">
            <input type="text" id="arrival_time" class="u-w-auto" name="arrival_time_forward" placeholder="16：45" />～
            <input type="text" id="arrival_time" class="u-w-auto" name="arrival_time_backward" placeholder="16：45" />
          </td>
        </tr>
        <tr>
          <!-- 到着便 -->
          <th>到着便</th>
          <td><input type="text" id="arrival_flight" name="arrival_flight" placeholder="NH205" /></td>
          <!-- 航空会社 -->
          <th>航空会社</th>
          <td>
            <div class="c-form-select-wrap">
              <select name="airline" id="airline">
                <option value="" selected>未選択</option>
                <option value="">ANA</option>
              </select>
            </div>
          </td>
          <!-- 出発空港 -->
          <th>出発空港</th>
          <td>
            <div class="c-form-select-wrap">
              <select name="departure_airport" id="departure_airport">
                <option value="" selected>未選択</option>
                <option value="">LAX</option>
              </select>
            </div>
          </td>
          <!-- 到着空港 -->
          <th>到着空港</th>
          <td>
            <div class="c-form-select-wrap">
              <select name="arrival_airport" id="arrival_airport">
                <option value="" selected>未選択</option>
                <option value="">NRT</option>
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <!-- 到着ターミナル -->
          <th>到着ターミナル</th>
          <td><input type="text" id="arrival_terminal" name="arrival_terminal" placeholder="2" /></td>
          <td colspan="6">
            <!-- 到着日とお迎え日が異なる　チェックボックス -->
            <input type="checkbox" name="arrival_date_different" id="arrival_date_different" value="1"><label for="arrival_date_different">到着日とお迎え日が異なる</label>
          </td>
        </tr>
      </table>

      <!-- 到着予定 -->
      <div class="c-title__table">車両情報</div>
      <table class="l-table-input">
        <tr>
          <!-- 車両コード -->
          <th><label for="car_code">メーカー</label></th>
          <!-- セレクト -->
          <td>
            <div class="c-form-select-wrap">
              <select name="car_code" id="car_code">
                <option value="" selected>未選択</option>
                <option value="">ダミーダミー</option>
              </select>
            </div>
          </td>
          <!-- 車種 -->
          <th><label for="car_type">車種</label></th>
          <!-- セレクト -->
          <td>
            <div class="c-form-select-wrap">
              <select name="car_type" id="car_type">
                <option value="" selected>未選択</option>
                <option value="">ダミーダミー</option>
                <option value="">BMW5</option>
                <option value="">なーーーーがーーーーい車種名</option>
              </select>
            </div>
          </td>
          <!-- 車番 -->
          <th><label for="car_number">車番</label></th>
          <td><input type="text" id="car_number" name="car_number" placeholder="1234" /></td>
          <!-- 色 -->
          <th><label for="color">色</label></th>
          <td><input type="text" id="color" name="color" placeholder="黒" /></td>
        </tr>
        <tr>
          <!-- 区分 -->
          <th>区分</th>
          <!-- インプット -->
          <td><input type="text" id="classification" name="classification" placeholder="普通" /></td>
          <!-- 人数 -->
          <th>人数</th>
          <td>
            <input type="text" id="number_of_people" name="number_of_people" placeholder="3" class="u-w80" />名
          </td>

          <!-- 車両取扱 -->
          <th>
            <label for="car_handling">車両取扱</label>
          </th>
          <td colspan="3">
            <div class="c-form-select-wrap">
              <select name="car_handling" id="car_handling">
                <option value="" selected>未選択</option>
                <option value="">ダミーダミー</option>
              </select>
            </div>
          </td>
        </tr>
      </table>
    </form>

  </div>
  <div class="l-container__button-fixed">
    <div class="c-button-group__form">
      <button type="button" class="c-button__clear">すべてをクリア</button>
      <button type="submit" class="c-button__submit" form="form1">検索</button>
    </div>

  </div>
</main><!-- /.l-container__main -->


@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush
