<!-- B-1-1 受付入力 -->
@extends('layouts.member.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "entry"])
@include('include.messages.errors')
<div class="p-user-input__inner--sm">
  <!-- 受付入力 -->
  <form action="{{route('reserves.entry_car')}}" method="POST">
    @csrf
    <!-- 詳細情報入力 -->
    <div class="p-user-input-auto-output__wrap l-flex--item-end">
      <!-- 入力エリア -->
      <div class="l-grid--col2-auto l-grid--cgap2">
        <div>
          <label for="car_maker_id">メーカー</label>
          <!-- 車メーカーのselect -->
          <div class="c-form-select-color">
            <select name="car_maker_id" id="car_maker_id">
              @foreach ($carMakers as $carMaker)
                <option value="{{ $carMaker->id }}"
                  {{old('car_maker_id', $reserve->car_maker_id)==$carMaker->id ? 'selected':''}}>
                  {{$carMaker->name }}
                </option>
              @endforeach

              {{--  <option value="トヨタ">トヨタ</option>
              <option value="日産">日産</option>
              <option value="フォルクスワーゲン">フォルクスワーゲン</option>
              <option value="アウディ">メルセデス・ベンツ</option>  --}}
            </select>
          </div>
        </div>
        <div>
          <label for="car_id">車種</label>
          <div class="c-form-select-color">
            <select id="car_id" name="car_id">
              @if (!empty(old('car_id', $reserve->car_id)))
                <option value="選択してください" disabled>選択してください</option>
                @foreach ($cars as $car)
                  <option value="{{ $car->id }}"
                    {{old('car_id', $reserve->car_id)==$car->id ? 'selected':''}}>
                    {{$car->name }}
                  </option>
                @endforeach
              @else
                <option value="" disabled></option>
              @endif
            </select>
          </div>
        </div>
        <div>
          <label for="car_color_id">色</label>
          <div class="c-form-select-color">
            <select id="car_color_id" name="car_color_id">
              <option value="選択してください" disabled>選択してください</option>
              @foreach ($carColors as $carColor)
                <option value="{{ $carColor->id }}"
                  {{old('car_color_id', $reserve->car_color_id)==$carColor->id ? 'selected':''}}>
                  {{$carColor->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label for="car_number">ナンバー（※4桁の数字）</label>
          <input type="text" id="car_number" name="car_number" maxlength="4" minlength="4" class="u-w-full-wide" value="{{old('car_number', $reserve->car_number)}}">
        </div>
        <div>
          <label for="car_color_id">到着便航空会社</label>
          <div class="c-form-select-color">
            <select id="airline_id" name="airline_id">
              <option value="選択してください" disabled>選択してください</option>
              @foreach ($airlines as $airline)
                <option value="{{ $airline->id }}"
                  {{old('airline_id', $reserve->airline_id)==$airline->id ? 'selected':''}}>
                  {{$airline->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div>
          <label for="flight_no">到着便名（例：200,300）</label>
          <input type="text" id="flight_no" name="flight_no" class="u-w-full-wide" value="{{old('flight_no', $reserve->flight_no)}}">
        </div>
        <div>
          <label for="arrive_date">到着日</label>
          <input type="hidden" id="unload_date_plan" value="{{old('unload_date_plan', $reserve->unload_date_plan)}}">
          <input type="date" id="arrive_date" name="arrive_date" class="u-w-full-wide u-mb025" value="{{old('arrive_date', $reserve->arrive_date ?  $reserve->arrive_date->format('Y-m-d'): $reserve->unload_date_plan?->format('Y-m-d'))}}">
          <p class="text-center arrival_flg hidden">到着日がお迎え日と異なる</p>
        </div>
        <div>
          <label for="num_members">ご利用人数</label>
          <input type="text" id="num_members" name="num_members" value="{{old('num_members', $reserve->num_members)}}">
        </div>
      </div><!-- 入力エリア -->

      <!-- 自動出力 -->
      <div class="p-user-input-auto-output__right u-mb1">
        <dl class="l-grid--col2 u-mb3">
          <dt>航空会社名</dt>
          <dd class="text-right" id="airline_name"></dd>
          <!-- 福岡空港 成田空港 18:20 -->
          <dt>出発空港</dt>
          <dd class="text-right" id="dep_airport_name"></dd>
          <dt>到着空港</dt>
          <dd class="text-right" id="arr_airport_name"></dd>
          <dt>到着予定時間</dt>
          <dd class="text-right" id="arrive_time"></dd>
        </dl>
        <div class="arrival_flg hidden">到着日がお迎え日と異なる</div>
      </div>
    </div>

    <!--  -->
    <div class="c-button-group__form u-mt3">
      <button type="button" id="returnButton" onclick="location.href='{{route('reserves.entry_info')}}';" class="c-button__pagination--return">前のページに戻る</button>
      <button type="submit" class="c-button__pagination--next">次へ進む</button>
    </div>
  </form>
</div><!-- ./p-user-input__inner -->


@endsection
@push("scripts")
<script src="{{ asset('js/pages/member/entry_car.js') }}"></script>
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush
