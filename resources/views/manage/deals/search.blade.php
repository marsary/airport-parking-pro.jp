<!-- B-3-1 予約管理TOP > 検索 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">

  <!-- パンくず -->
  <ul class="l-breadcrumb l-wrap__breadcrumb">
    <li class="l-breadcrumb__list">予約管理TOP</li>
    <li class="l-breadcrumb__list">検索</li>
  </ul>

  @include('include.messages.errors')

  <div class="l-container__inner">
    <form action="{{route('manage.deals.index')}}" id="form1" method="GET" class="c-button-fixed__parent u-pb6">
      @csrf
      <ul class="l-flex--end l-grid--cgap1">
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="reserved" name="reserved" class="u-mb0" {{old('reserved', request('reserved')) ? 'checked ':''}} value="1">
          <label for="reserved">予約中</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="load_today" name="load_today" class="u-mb0" {{old('load_today') ? 'checked ':''}} value="1">
          <label for="load_today">本日入庫予定</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="loaded" name="loaded" class="u-mb0" {{old('loaded') ? 'checked ':''}} value="1">
          <label for="loaded">入庫中</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="unload_plan_today" name="unload_plan_today" class="u-mb0" {{old('unload_plan_today') ? 'checked ':''}} value="1">
          <label for="unload_plan_today">本日出庫予定</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="pending" name="pending" class="u-mb0" {{old('pending') ? 'checked ':''}} value="1">
          <label for="pending">保留</label>
        </li>
        <li class="l-flex l-grid--cgap025">
          <input type="checkbox" id="unloaded" name="unloaded" class="u-mb0" {{old('unloaded') ? 'checked ':''}} value="1">
          <label for="unloaded">出庫済</label>
        </li>
      </ul>

      <div class="c-title__table">予約情報</div>

      <table class="l-table-input">
        <tr>
          <th><label for="reserve_code">予約コード</label></th>
          <td><input type="text" id="reserve_code" name="reserve_code" placeholder="1234567890" value="{{old('reserve_code')}}" /></td>
          <!-- 受付コード -->
          <th><label for="receipt_code">受付コード</label></th>
          <td><input type="text" id="receipt_code" name="receipt_code" placeholder="1234567890" value="{{old('receipt_code')}}" /></td>
          <!-- 予約日時 -->
          <th><label for="reserve_date">予約日時</label></th>
          <td><input type="text" id="reserve_date" name="reserve_date" placeholder="2024/1/15(月)20:12" value="{{old('reserve_date')}}" /></td>
          <th>予約経路</th>
          <td>
            <div class="c-form-select-wrap">
              <select name="agency_id">
                <!-- デフォルト 選択不可 -->
                <option value="" selected disabled>未選択</option>
                @foreach ($agencies as $agency)
                  <option value="{{$agency->id}}" {{($agency->id == old('agency_id') ) ? 'selected':''}}>{{$agency->name}}</option>
                @endforeach
                {{--  <option value="公式HP">公式HP</option>
                <option value="公式HP">代理店</option>  --}}
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <!-- 入庫日 -->
          <th><label for="storage_date">入庫日</label></th>
          <td colspan="3">
            <input type="date" id="load_date_start" class="u-w-auto" name="load_date_start" value="{{old('load_date_start')}}" />～
            <input type="date" id="load_date_end" class="u-w-auto" name="load_date_end" value="{{old('load_date_end')}}" />
            <script>
                const load_date_start = document.getElementById("load_date_start");
                const load_date_end = document.getElementById("load_date_end");

                load_date_start.addEventListener("change", () => {
                    load_date_end.min = load_date_start.value;
                });
            </script>
          </td>
          <!-- 入庫時間 -->
          <th><label for="load_time">入庫時間</label></th>
          <td colspan="3">
            <input type="time" id="load_time_start" class="u-w-auto" name="load_time_start" placeholder="16:45" value="{{old('load_time_start')}}" />～
            <input type="time" id="load_time_end" class="u-w-auto" name="load_time_end" placeholder="16:45" value="{{old('load_time_end')}}" />
        </tr>
        <!-- 予定日 -->
        <tr>
          <th><label for="unload_date_plan">出庫予定日</label></th>
          <td colspan="3">
            <input type="date" id="unload_date_plan_start" class="u-w-auto" name="unload_date_plan_start" value="{{old('unload_date_plan_start')}}" />～
            <input type="date" id="unload_date_plan_end" class="u-w-auto" name="unload_date_plan_end" value="{{old('unload_date_plan_end')}}" />
            <script>
                const unload_date_plan_start = document.getElementById("unload_date_plan_start");
                const unload_date_plan_end = document.getElementById("unload_date_plan_end");

                unload_date_plan_start.addEventListener("change", () => {
                    unload_date_plan_end.min = unload_date_plan_start.value;
                });
            </script>
          </td>
          <th><label for="unload_date">出庫日</label></th>
          <td colspan="3">
            <input type="date" id="unload_date_start" class="u-w-auto" name="unload_date_start" value="{{old('unload_date_start')}}" />～
            <input type="date" id="unload_date_end" class="u-w-auto" name="unload_date_end" value="{{old('unload_date_end')}}" />
            <script>
                const unload_date_start = document.getElementById("unload_date_start");
                const unload_date_end = document.getElementById("unload_date_end");

                unload_date_start.addEventListener("change", () => {
                    unload_date_end.min = unload_date_start.value;
                });
            </script>
          </td>
        </tr>
        <tr>
          <!-- 利用日数 -->
          <th><label for="num_days">利用日数</label></th>
          <td colspan="6">
            <input type="text" id="num_days_start" name="num_days_start" placeholder="3" class="u-w50" value="{{old('num_days_start')}}" />日間～
            <input type="text" id="num_days_end" name="num_days_end" placeholder="6" class="u-w50" value="{{old('num_days_end')}}">日間
          </td>
        </tr>
      </table>

      <!-- 顧客情報 -->
      <div class="c-title__table">顧客情報</div>
      <table class="l-table-input">
        <tr>
          <!-- 顧客コード -->
          <th><label for="member_code">顧客コード</label></th>
          <td><input type="text" id="member_code" name="member_code" placeholder="1234567890" value="{{old('member_code')}}" /></td>
          <!-- お客様氏名 -->
          <th><label for="name">お客様氏名</label></th>
          <td><input type="text" id="name" name="name" placeholder="サン太郎" value="{{old('name')}}" /></td>
          <!-- ふりがな -->
          <th><label for="kana">ふりがな</label></th>
          <td><input type="text" id="kana" name="kana" placeholder="さんたろう" value="{{old('kana')}}" /></td>
          <!-- 利用回数 -->
          <th><label for="used_num">利用回数</label></th>
          <td><input type="text" id="used_num" name="used_num" placeholder="8回" value="{{old('used_num')}}" /></td>
        </tr>
        <tr>
          @for ($i = 0; $i < 4; $i++)
            @if (isset($labels[$i]))
              @include('manage.deals.commons.label_tag_select', ['label' => $labels[$i]])
            @else
              <th></th><td></td>
            @endif
          @endfor
          {{--  <!-- ラベル1 -->
          <th><label for="label1">会員ランク</label></th>
          <td>
            <div class="c-form-select-wrap">
              <select name="label1" id="label1">
                <option value="" selected>未選択</option>
                <option value="">ダミーダミー</option>
              </select>
            </div>
          </td>  --}}
          {{--  <td><input type="text" id="member_rank" name="member_rank" placeholder="シルバー" /></td>  --}}
          {{--  <!-- ラベル2 -->
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
          </td>  --}}
        </tr>
        <tr>
          <!-- 郵便番号 -->
          <th><label for="zip">郵便番号</label></th>
          <td><input type="text" id="zip" name="zip" placeholder="111-0000" value="{{old('zip')}}" /></td>
          <!-- 電話番号 -->
          <th><label for="tel">電話番号</label></th>
          <td><input type="text" id="tel" name="tel" placeholder="090-1234-5678" value="{{old('tel')}}" /></td>
          <!-- 以下2つは桁数次第ではレイアウトが崩れる分けてもよいかも -->
          <!-- Mail -->
          <th><label for="email">Mail</label></th>
          <td><input type="email" id="email" name="email" placeholder="example@aaa.com" value="{{old('email')}}" /></td>
          <!-- LINE ID -->
          <th><label for="line_id">LINE ID</label></th>
          <td><input type="text" id="line_id" name="line_id" placeholder="sun123" value="{{old('line_id')}}" /></td>
        </tr>
        <tr>
          <!-- 領収書の宛名 -->
          <th><label for="receipt_address">領収書の宛名</label></th>
          <td colspan="3"><input type="text" id="receipt_address" name="receipt_address" placeholder="サン太郎" value="{{old('receipt_address')}}" /></td>
        </tr>
      </table>

      <!-- 到着予定 -->
      <div class="c-title__table">到着予定</div>
      <table class="l-table-input">
        <tr>
          <!-- 到着予定日 -->
          <th>到着予定日</th>
          <td colspan="3">
            <input type="date" id="arrive_date_start" class="u-w-auto" name="arrive_date_start" placeholder="2024/2/5(月)" value="{{old('arrive_date_start')}}" />～
            <input type="date" id="arrive_date_end" class="u-w-auto" name="arrive_date_end" placeholder="2024/2/5(月)" value="{{old('arrive_date_end')}}" />
            <script>
                const arrive_date_start = document.getElementById("arrive_date_start");
                const arrive_date_end = document.getElementById("arrive_date_end");

                arrive_date_start.addEventListener("change", () => {
                    arrive_date_end.min = arrive_date_start.value;
                });
            </script>
          </td>
          <!-- 到着予定時間 -->
          <th>到着予定時間</th>
          <td colspan="3">
            <input type="time" id="arrive_time_start" class="u-w-auto" name="arrive_time_start" placeholder="16：45" value="{{old('arrive_time_start')}}" />～
            <input type="time" id="arrive_time_end" class="u-w-auto" name="arrive_time_end" placeholder="16：45" value="{{old('arrive_time_end')}}" />
          </td>
        </tr>
        <tr>
          <!-- 到着便 -->
          <th>到着便</th>
          <td><input type="text" id="arrival_flight_name" name="arrival_flight_name" placeholder="NH205" value="{{old('arrival_flight_name')}}" /></td>
          <!-- 航空会社 -->
          <th>航空会社</th>
          <td>
            <div class="c-form-select-wrap">
              <select name="airline_id" id="airline_id">
                <option value="" selected>未選択</option>
                @foreach ($airlines as $airline)
                  <option value="{{$airline->id}}" {{($airline->id == old('airline_id') ) ? 'selected':''}}>{{$airline->name}}</option>
                @endforeach
                {{--  <option value="">ANA</option>  --}}
              </select>
            </div>
          </td>
          <!-- 出発空港 -->
          <th>出発空港</th>
          <td>
            <div class="c-form-select-wrap">
              <select name="dep_airport_id" id="dep_airport_id">
                <option value="" selected>未選択</option>
                @foreach ($airports as $airport)
                  <option value="{{$airport->id}}" {{($airport->id == old('dep_airport_id') ) ? 'selected':''}}>{{$airport->name}}</option>
                @endforeach
                {{--  <option value="">LAX</option>  --}}
              </select>
            </div>
          </td>
          <!-- 到着空港 -->
          <th>到着空港</th>
          <td>
            <div class="c-form-select-wrap">
              <select name="arr_airport_id" id="arr_airport_id">
                <option value="" selected>未選択</option>
                @foreach ($airports as $airport)
                  <option value="{{$airport->id}}" {{($airport->id == old('arr_airport_id') ) ? 'selected':''}}>{{$airport->name}}</option>
                @endforeach
                {{--  <option value="">NRT</option>  --}}
              </select>
            </div>
          </td>
        </tr>
        <tr>
          <!-- 到着ターミナル -->
          <th>到着ターミナル</th>
          <td><input type="text" id="terminal_id" name="terminal_id" placeholder="2" value="{{old('terminal_id')}}" /></td>
          <td colspan="6">
            <!-- 到着日とお迎え日が異なる　チェックボックス -->
            <input type="checkbox" name="arrival_flg" id="arrival_flg" value="1" {{old('arrival_flg') ? 'checked ':''}}>
            <label for="arrival_flg">到着日とお迎え日が異なる</label>
          </td>
        </tr>
      </table>

      <!-- 車両情報 -->
      <div class="c-title__table">車両情報</div>
      <table class="l-table-input">
        <tr>
          <!-- 車両コード -->
          <th><label for="car_maker_id">メーカー</label></th>
          <!-- セレクト -->
          <td>
            <div class="c-form-select-wrap">
              <select name="car_maker_id" id="car_maker_id">
                <option value="" selected>未選択</option>
                @foreach ($carMakers as $carMaker)
                  <option value="{{$carMaker->id}}" {{($carMaker->id == old('car_maker_id') ) ? 'selected':''}}>{{$carMaker->name}}</option>
                @endforeach
                {{--  <option value="">ダミーダミー</option>  --}}
              </select>
            </div>
          </td>
          <!-- 車種 -->
          <th><label for="car_id">車種</label></th>
          <!-- セレクト -->
          <td>
            <div class="c-form-select-wrap">
              <select name="car_id" id="car_id">
                <option value="" selected>未選択</option>
                @foreach ($cars as $car)
                  <option value="{{$car->id}}" {{($car->id == old('car_id') ) ? 'selected':''}}>{{$car->name}}</option>
                @endforeach
                {{--  <option value="">ダミーダミー</option>
                <option value="">BMW5</option>
                <option value="">なーーーーがーーーーい車種名</option>  --}}
              </select>
            </div>
          </td>
          <!-- 車番 -->
          <th><label for="number">車番</label></th>
          <td><input type="text" id="number" name="number" placeholder="1234" value="{{old('number')}}" /></td>
          <!-- 色 -->
          <th><label for="car_color_id">色</label></th>
          <td>
            <div class="c-form-select-wrap">
              <select name="car_color_id" id="car_color_id">
                <option value="" selected>未選択</option>
                @foreach ($carColors as $carColor)
                  <option value="{{$carColor->id}}" {{($carColor->id == old('car_color_id') ) ? 'selected':''}}>{{$carColor->name}}</option>
                @endforeach
              </select>
            </div>
            {{--  <input type="text" id="color" name="color" placeholder="黒" />  --}}
          </td>
        </tr>
        <tr>
          <!-- 区分 -->
          <th>区分</th>
          <!-- インプット -->
          <td>
            <div class="c-form-select-wrap">
              <select name="size_type" id="size_type">
                <option value="" selected>未選択</option>
                @foreach(\App\Enums\CarSize::cases() as $carSize)
                  <option value="{{$carSize->value}}" {{old('size_type')==$carSize->value ? 'selected':''}}>
                    {{$carSize->label()}}
                  </option>
                @endforeach
              </select>
            </div>
            {{--  <input type="text" id="classification" name="classification" placeholder="普通" />  --}}
          </td>
          <!-- 人数 -->
          <th>人数</th>
          <td>
            <input type="text" id="num_members" name="num_members" placeholder="3" class="u-w80" value="{{old('num_members')}}" />名
          </td>

          <!-- 車両取扱 -->
          <th>
            <label for="car_caution_id">車両取扱</label>
          </th>
          <td colspan="3">
            <div class="c-form-select-wrap">
              <select name="car_caution_id" id="car_caution_id">
                <option value="" selected>未選択</option>
                @foreach ($carCautions as $carCaution)
                  <option value="{{$carCaution->id}}" {{($carCaution->id == old('car_caution_id') ) ? 'selected':''}}>{{$carCaution->name}}</option>
                @endforeach
                {{--  <option value="">ダミーダミー</option>  --}}
              </select>
            </div>
          </td>
        </tr>
      </table>
    </form>

  </div>
  <div class="l-container__button-fixed">
    <div class="c-button-group__form">
      <button type="submit" class="c-button__submit" form="form1">検索</button>
    </div>

  </div>
</main><!-- /.l-container__main -->


@endsection
@push("scripts")
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/ja.js') }}"></script>
<script src="{{ asset('js/pages/manage/search.js') }}"></script>
<script>
</script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
<style>
  .select2-selection__arrow {
    display: none;
  }
  .select2-container--default .select2-selection--single {
    border: none;
  }
  .select2-container--default, .select2-container--default .select2-selection--single, .select2-selection__rendered {
    height: 40px;
    line-height: 40px !important;
  }
  .select2-container .select2-selection--single .select2-selection__rendered {
    padding-left: 18px !important;
  }
  .select2-container--default .select2-selection--single {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }

</style>
@endpush
