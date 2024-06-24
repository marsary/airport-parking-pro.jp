<!-- B-2-0 受付入力1（受付予約・スタッフが入力） -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main l-container__main">
  <div class="l-container__main">
    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list">受付入力1</li>
    </ul>

    @include('include.messages.errors')

    <div class="l-container__inner">

      <form action="{{route('manage.reserves.entry_date')}}" method="POST">
        @csrf
        <div class="l-grid--col2-auto l-grid--gap2 u-mb4">
          <div>
            <!-- 代理店コード -->
            <label for="agency_code" class="u-d-none">代理店コード</label>
            <input type="text" id="agency_code" class="u-w-full-wide" name="agency_code" value="{{old('agency_code', $reserve->agency_code)}}" />
          </div>
          <div>
            <!-- 割引クーポン　セレクト -->
            <div class="l-grid--col2 l-grid--gap1 l-flex--item-end">
              {{--  <div>
                <label for="coupon_id" class="u-d-none">割引クーポン</label>
                <div class="c-form-select-color">
                  <select id="coupon_id" name="coupon_id" class="u-w-full-wide">
                    <option value=""></option>
                    @foreach ($coupons as $coupon)
                      <option value="{{ $coupon->id }}"
                        {{old('coupon_id')==$coupon->id ? 'selected':''}}>
                        {{$coupon->name }}
                      </option>
                    @endforeach
                    <option value="0">割引クーポン</option>
                    <option value="1">クーポンコード1</option>
                    <option value="2">クーポンコード2</option>
                    <option value="3">クーポンコード3</option>
                  </select>
                </div>
              </div>
              <button id="apply_coupon_btn" type="button" class="c-button__apply u-mb1">適用</button>  --}}
            </div>
          </div>
        </div>


        <!-- カレンダー選択 -->
        <div class="u-mb6">
          <p class="text-center u-mb2 u-font--medium">入庫日を指定してください</p>
          <div class="l-grid--col2 l-grid--gap2">
            <div>
              <div class="c-title__calendar" id="left_calendar1_title"></div>
            </div>
            <div>
              <div class="c-title__calendar" id="right_calendar1_title"></div>
            </div>
          </div>
          <div class="calendar_section">
            <div class="prev_button">
              <button type="button" id="cal1_prev">◀</button>
            </div>
            <div id='calendar1'></div>
            <div class="next_button">
              <button type="button" id="cal1_next">▶</button>
            </div>
          </div>
        </div>

        <div class="u-mb6">
          <p class="text-center u-mb2 u-font--medium">入庫予定時間を指定してください</p>
          <div class="c-title__calendar u-mb1" id="load_timetable_title">0000年0月</div>
          <div class="l-grid--col3-auto l-grid--gap05 l-grid--start" id="load_time_section">
            <dl class="c-calendar__wrap">
              <dt class="c-calendar__heading">午前</dt>
              <dd>
                <ul class="c-calendar-available-time__wrap">
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="0">0:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="0"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="1">1:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="1"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="2">2:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="2"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="3">3:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="3"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="4">4:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="4"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="5">5:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="5"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="6">6:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="6"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="7">7:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="7"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="8">8:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="8"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="9">9:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="9"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="10">10:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="10"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="11">11:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="11"></div>
                  </li>
                </ul>
              </dd>
            </dl>
            <dl class="c-calendar__wrap">
              <dt class="c-calendar__heading">午後</dt>
              <dd>
                <ul class="c-calendar-available-time__wrap">
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="12">12:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="12"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="13">13:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="13"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="14">14:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="14"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="15">15:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="15"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="16">16:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="16"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="17">17:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="17"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="18">18:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="18"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="19">19:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="19"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="20">20:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="20"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="21">21:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="21"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="22">22:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="22"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="hour_label_cell" data-hour="23">23:00～</div>
                    <div class="c-calendar__available-icon hour_vacancy" data-hour="23"></div>
                  </li>
                </ul>
              </dd>
            </dl>

            <!-- 時間 19:00から15分刻みの理由は不明　leftにpadding入れている理由も不明 -->
            <dl class="c-calendar__wrap u-pl4">
              <dt class="c-calendar__heading">時間</dt>
              <dd>
                <ul class="c-calendar-available-time__wrap">
                  <li class="c-calendar-available-time">
                    <div class="quarter_hour_label_cell" data-min="00" data-time="19:00">19:00～</div>
                    <div class="c-calendar__available-icon quarter_hour_vacancy" data-min="00"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="quarter_hour_label_cell" data-min="15" data-time="19:15">19:15～</div>
                    <div class="c-calendar__available-icon quarter_hour_vacancy" data-min="15"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="quarter_hour_label_cell" data-min="30" data-time="19:30">19:30～</div>
                    <div class="c-calendar__available-icon quarter_hour_vacancy" data-min="30"></div>
                  </li>
                  <li class="c-calendar-available-time">
                    <div class="quarter_hour_label_cell" data-min="45" data-time="19:45">19:45～</div>
                    <div class="c-calendar__available-icon quarter_hour_vacancy" data-min="45"></div>
                  </li>
                </ul>
              </dd>
            </dl>

          </div>
        </div>

        <div class="u-mb6">
          <p class="text-center u-mb2 u-font--medium">出庫日を指定してください</p>
          <div class="l-grid--col2 l-grid--gap2">
            <div>
              <div class="c-title__calendar" id="left_calendar2_title"></div>
            </div>
            <div>
              <div class="c-title__calendar" id="right_calendar2_title"></div>
            </div>
          </div>
          <div class="calendar_section">
            <div class="prev_button">
                <button type="button" id="cal2_prev">◀</button>
              </div>
              <div id='calendar2'></div>
              <div class="next_button">
                <button type="button" id="cal2_next">▶</button>
              </div>
          </div>
        </div>

        <!-- 駐車料金合計 -->
        <div class="u-border--all u-border--radius p-input-user-total-parking-charges">
          <p class="u-font--24 text-center">駐車料金合計</p>
          <div class="p-input-user-total-parking-charges__inner">
            <div class="p-input-user-total-parking-charges__head">
              <div>入庫日</div>
              <div>:</div>
              <div id="disp_load_date"></div>
              <div>出庫日</div>
              <div>:</div>
              <div id="disp_unload_date_plan"></div>
              <div>利用日数</div>
              <div>:</div>
              <div id="disp_num_days"></div>
            </div>

            <input type="hidden" name="load_date" value="{{old('load_date', $reserve->load_date)}}">
            <input type="hidden" name="load_time" value="{{old('load_time', $reserve->load_time)}}">
            <input type="hidden" name="unload_date_plan" value="{{old('unload_date_plan', $reserve->unload_date_plan)}}">
            <input type="hidden" name="unload_time_plan" value="{{old('unload_time_plan', $reserve->unload_time_plan)}}">
            <input type="hidden" name="num_days" value="{{old('num_days', $reserve->num_days)}}">
            <input type="hidden" id="coupon_ids" name="coupon_ids" value="{{old('coupon_ids', implode(',', $reserve->coupon_ids ?? []))}}">

            <button type="button" id="open_button" class="c-label__light-deep-gray--lg is-block u-horizontal-auto u-mb1">内訳を表示</button>

            <div class="is-none p-input-user-total-parking-charges__detail" id="toggle_element">
              <div class="p-input-user-total-parking-charges__detail-title">
                <div>駐車料金明細（税込）</div>
                <div type="button" class="p-input-user-total-parking-charges__detail-close-button" id="close_button"><img src="{{ asset('images/icon/closeButton.svg') }}" width="15" height="15" /></div>
              </div>
              <div id="price_rows" class="p-input-user-total-parking-charges__detail-list">
                {{--  <div>10/10(水)</div>
                <div>¥1,000-</div>
                <div>10/11(木)</div>
                <div>¥1,000-</div>
                <div>10/12(金)</div>
                <div>¥1,500-</div>
                <div>10/13(土)</div>
                <div>¥2,000-</div>
                <div>10/14(日)</div>
                <div>¥2,000-</div>
                <div>10/15(月)</div>
                <div>¥300-</div>  --}}
              </div>
              <div class="p-input-user-total-parking-charges__detail-total">
                <div id="num_days"></div>
                <div id="sub_total"></div>
              </div>
            </div>

            <!-- 料金明細 -->
            <div class="u-mt3 u-pt3 u-border--top p-input-user-total-parking-charges__detail-total" id="detail_total">
              {{--  <div class="disp_coupon">クーポン</div>
              <div class="disp_coupon">-1,000円</div>  --}}
              <div id="tax_label">消費税(10%)</div>
              <div id="tax"></div>
              <div>駐車料金合計</div>
              <div id="total"></div>
            </div>

          </div><!-- /.p-input-user-total-parking-charges__inner -->

          <button type="submit" class="c-button__submit u-horizontal-auto">予約に進む</button>
        </div><!-- /.p-input-user-total-parking-charges -->

      </form>

    </div><!-- /.l-container__inner -->
  </div>
</main>


@endsection
@push("scripts")
<script src="{{ asset('js/close_button_toggle.js') }}"></script>
<script src="{{ asset('js/index.global.min.js') }}"></script>
<script src="{{ asset('js/pages/manage/entry_date.js') }}"></script>
<script>
</script>
@endpush
@push('css')
<style>
  .calendar_section {
    position: relative;
    display: flex;
    align-items: center;
  }
  .prev_button {
    position: absolute;
    left: -30px;
  }
  .next_button {
    position: absolute;
    right: -30px;
  }
  .fc-multimonth-title {
    display: none;
  }
  div.fc-multimonth-month {
    padding: 8px 10px 8px !important;
  }
  table.fc-multimonth-daygrid-table {
    border-spacing: 4px;
    border-collapse: separate;
  }
  table.fc-multimonth-header-table  {
    border-spacing: 4px;
    border-collapse: separate;
  }
  table.fc-multimonth-header-table tr  {
    height: 25px;
  }
  .fc-day-disabled {
    border: none !important;
    background-color: #fff !important;
  }
  .fc-daygrid-day {
    background-color: #eee;
    text-align: center;
  }
  .fc-day.fc-day-today.fc-daygrid-day:not(.day_full), .fc-day.fc-day-future.fc-daygrid-day:not(.day_full) {
    cursor: pointer;
  }
  .fc-daygrid-day-events {
    margin: 5px;
  }
  .fc-col-header-cell.fc-day {
    background-color: black;
    color:#fff;
  }
  .fc-col-header-cell.fc-day.fc-day-sat {
    background-color: #1f6aaa;
  }
  .fc-col-header-cell.fc-day.fc-day-sun {
    background-color: #d82528;
  }
  a.fc-col-header-cell-cushion {
    padding: 10px !important;
  }
  a.fc-daygrid-day-number {
    display: block;
    width:100%;
  }
  .fc .fc-daygrid-day-top {
    margin-top: 5px;
  }
  .hour_label_cell, .quarter_hour_label_cell {
    cursor: pointer;
  }

  .day_selected, .hour_selected, .quater_hour_selected {
    border-color: rgb(167, 207, 249) !important;
    background-color: rgb(167, 207, 249) !important;
  }
</style>
@endpush
