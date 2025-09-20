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

            <form action="{{$action}}" method="POST">
                @method($method)
                @csrf
                <div class="p-reserve__wrap">
                    <!-- 代理店コード -->
                    <label for="agency_code" class="u-d-none">代理店コード</label>
                    <input type="text" id="agency_code" class="u-w-full-wide" name="agency_code" value="{{old('agency_code', $reserve->agency_code)}}" />
                </div>

                <!-- カレンダー選択 -->
                <div class="p-reserve__wrap">
                    <p class="text-center u-mb2 u-font--medium">入庫日を指定してください</p>
                    <div>
                        <div class="c-title__calendar" id="left_calendar1_title"></div>
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

                <div class="p-reserve__wrap">
                    <p class="text-center u-mb2 u-font--medium">出庫日を指定してください</p>
                    <div>
                        <div class="c-title__calendar" id="left_calendar2_title"></div>
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

                <div class="p-reserve__wrap">
                    <p class="text-center u-mb2 u-font--medium">入庫予定時間を指定してください</p>
                    <div class="c-title__calendar u-mb1" id="load_timetable_title">0000年0月</div>
                    {{-- <div class="u-mb2 l-grid--col2 l-grid--gap1 p-reserve-selectedTime" id="load_time_section"> --}}
                        {{-- <dl>
                            <dt class="c-calendar__heading">午前</dt>
                            <dd>
                                <ul class="c-calendar-available-time__wrap">
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="0">0:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="0"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="1">1:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="1"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="2">2:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="2"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="3">3:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="3"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="4">4:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="4"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="5">5:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="5"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="6">6:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="6"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="7">7:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="7"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="8">8:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="8"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="9">9:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="9"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="10">10:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="10"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="11">11:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="11"></div>
                                    </li>
                                </ul>
                            </dd>
                        </dl>
                        <dl>
                            <dt class="c-calendar__heading">午後</dt>
                            <dd>
                                <ul class="c-calendar-available-time__wrap">
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="12">12:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="12"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="13">13:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="13"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="14">14:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="14"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="15">15:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="15"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="16">16:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="16"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="17">17:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="17"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="18">18:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="18"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="19">19:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="19"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="20">20:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="20"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="21">21:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="21"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="22">22:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="22"></div>
                                    </li>
                                    <li class="c-calendar-available-time">
                                        <div class="p-reserve-selectedTime__bg hour_label_cell" data-hour="23">23:00～</div>
                                        <div class="p-reserve-selectedTime__bg c-calendar__available-icon hour_vacancy" data-hour="23"></div>
                                    </li>
                                </ul>
                            </dd>
                        </dl> --}}
                          <!--
    変更ここから 2025/6/23
  -->
  <!-- 
    1時間おきの時間選択削除
  -->
  <dl class="p-reserve-selectedTime--detail">
    <dt class="c-calendar__heading">時間</dt>
    <dd>
      <ul class="p-reserve-selectedTime--detail__wrap">
        <!-- ここから -->
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">0:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon">
            <img src="../../images/svg/calendar_available.svg">
          </div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">0:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon">
            <img src="../../images/svg/calendar_some-available.svg"><!-- 残数小画像（▲） -->
          </div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">0:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon">
            <img src="../../images/svg/calendar_none.svg"><!-- 予約不可画像 -->
          </div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">0:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon">
            <img src="../../images/svg/calendar_available.svg">
          </div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">1:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">1:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">1:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">1:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">2:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">2:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">2:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">2:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">3:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">3:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">3:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">3:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">4:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">4:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">4:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">4:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">5:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">5:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">5:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">5:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">6:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">6:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">6:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">6:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">7:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">7:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">7:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">7:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">8:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">8:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">8:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">8:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">9:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">9:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">9:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">9:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">10:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">10:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">10:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">10:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">11:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">11:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">11:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">11:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">12:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">12:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">12:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">12:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">13:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">13:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">13:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">13:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">14:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">14:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">14:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">14:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">15:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">15:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">15:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">15:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">16:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">16:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">16:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">16:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">17:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">17:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">17:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">17:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">18:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">18:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">18:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">18:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">19:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">19:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">19:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">19:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">20:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">20:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">20:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">20:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">21:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">21:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">21:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">21:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">22:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">22:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">22:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">22:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">23:00～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">23:15～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">23:30～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
        <li class="c-calendar-available-time__detail">
          <div class="p-reserve-selectedTime__bg">23:45～</div>
          <div class="p-reserve-selectedTime__bg c-calendar__available-icon"><img src="../../images/svg/calendar_available.svg"></div>
        </li>
      </ul>
    </dd>
  </dl>
  <!-- 変更ここまで 2025/6/23 -->

                    {{-- </div>

                    <dl class="p-reserve-selectedTime--detail" id="quarter_hour_section">
                        <dt class="c-calendar__heading">時間</dt>
                        <dd>
                            <ul class="p-reserve-selectedTime--detail__wrap">
                                <li class="c-calendar-available-time">
                                    <div class="p-reserve-selectedTime__bg quarter_hour_label_cell" data-min="00" data-time="19:00">19:00～</div>
                                    <div class="p-reserve-selectedTime__bg c-calendar__available-icon quarter_hour_vacancy" data-min="00"></div>
                                </li>
                                <li class="c-calendar-available-time">
                                    <div class="p-reserve-selectedTime__bg quarter_hour_label_cell" data-min="15" data-time="19:15">19:15～</div>
                                    <div class="p-reserve-selectedTime__bg c-calendar__available-icon quarter_hour_vacancy" data-min="15"></div>
                                </li>
                                <li class="c-calendar-available-time">
                                    <div class="p-reserve-selectedTime__bg quarter_hour_label_cell" data-min="30" data-time="19:30">19:30～</div>
                                    <div class="p-reserve-selectedTime__bg c-calendar__available-icon quarter_hour_vacancy" data-min="30"></div>
                                </li>
                                <li class="c-calendar-available-time">
                                    <div class="p-reserve-selectedTime__bg quarter_hour_label_cell" data-min="45" data-time="19:45">19:45～</div>
                                    <div class="p-reserve-selectedTime__bg c-calendar__available-icon quarter_hour_vacancy" data-min="45"></div>
                                </li>
                            </ul>
                        </dd>
                    </dl> --}}
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
                                <div>駐車料金明細（税抜）</div>
                                <div type="button" class="p-input-user-total-parking-charges__detail-close-button" id="close_button"><img src="{{ asset('images/icon/closeButton.svg') }}" width="15" height="15" /></div>
                            </div>
                            <div id="price_rows" class="p-input-user-total-parking-charges__detail-list">
                                {{-- <div>10/10(水)</div>
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
                            {{-- <div class="disp_coupon">クーポン</div>
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
<script src="{{ asset('js/commons/entry_date.js') }}"></script>
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

  {{--  単数月用  --}}

  .fc .fc-scrollgrid {
    border-left-width: 0px;
  }

  table.fc-col-header {
    padding-top: 8px;
    border-spacing: 4px;
    border-collapse: separate;
  }

  table.fc-col-header tr {
    height: 25px;
  }

  table.fc-scrollgrid-sync-table {
    border-spacing: 4px;
    border-collapse: separate;
  }

  {{--  複数月用  --}}
  {{--  .fc-multimonth-title {
    display: none;
  }  --}}

  {{--  div.fc-multimonth-month {
    padding: 8px 10px 8px !important;
  }  --}}

  {{--  table.fc-multimonth-daygrid-table {
    border-spacing: 4px;
    border-collapse: separate;
  }  --}}

  {{--  table.fc-multimonth-header-table {
    border-spacing: 4px;
    border-collapse: separate;
  }

  table.fc-multimonth-header-table tr {
    height: 25px;
  }  --}}

    .fc-day-disabled {
        border: none !important;
        background-color: #fff !important;
    }

    .fc-daygrid-day {
        background-color: #eee;
        text-align: center;
        height: 90px !important;
    }

    .fc-day.fc-day-today.fc-daygrid-day:not(.day_full),
    .fc-day.fc-day-future.fc-daygrid-day:not(.day_full) {
        cursor: pointer;
    }

    .fc-daygrid-day-events {
        margin: 5px;
    }

    .fc-col-header-cell.fc-day {
        background-color: black;
        color: #fff;
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

    .fc-daygrid-day-events::after,
    .fc-daygrid-day-events::before,
    .fc-daygrid-event-harness::after,
    .fc-daygrid-event-harness::before {
        content: none;
    }

    a.fc-daygrid-day-number {
        display: block;
        width: 100%;
    }

    .fc .fc-daygrid-day-top {
        margin-top: 3px;
    }

    .hour_label_cell,
    .quarter_hour_label_cell {
        cursor: pointer;
    }

    .day_selected,
    .hour_selected,
    .quater_hour_selected {
        border-color: rgb(167, 207, 249) !important;
        background-color: rgb(167, 207, 249) !important;
    }
    .fc-h-event .fc-event-title {
        display: inline-block;
        left: 0px;
        max-width: 100%;
        overflow: hidden;
        right: 0px;
        vertical-align: top;
        /* ここを追加 */
        font-weight: bold;
        color: #24a12c;
    }
</style>
@endpush
