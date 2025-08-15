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

  @include('include.messages.errors')

  <div class="l-container__inner">

    <form action="{{route('manage.reserves.store')}}" method="POST">
      @csrf
      <div class="c-title__table">予約情報</div>
      <table class="l-table-confirm">
        <tr>
          <th>予約コード</th>
          <td>{{$reserve->reserve_code}}</td>
          <th>受付コード</th>
          <td>1234567890</td>
          <th>予約日時</th>
          <td>{{$reserve->reserve_date->isoFormat('YYYY/M/D(ddd) H:m')}}</td>
          <th>予約経路</th>
          <td>{{$agency?->name}}</td>
        </tr>
        <tr>
          <th>入庫日時</th>
          <td>{{$reserve->load_date->isoFormat('YYYY/M/D(ddd)') . ' ' . $reserve->load_time}}</td>
          <th>出庫予定日</th>
          <td>{{$reserve->unload_date_plan->isoFormat('YYYY/M/D(ddd)')}}</td>
          <th>利用日数</th>
          <td>{{$reserve->num_days}}日</td>
        </tr>
      </table>

      <!-- 顧客情報 -->
      <div class="c-title__table">顧客情報</div>
      <table class="l-table-confirm">
        <tr>
          <th>顧客コード</th>
          <td>{{$reserve->member?->member_code}}</td>
          <th>お客様氏名</th>
          <td>{{$reserve->name}}</td>
          <th>ふりがな</th>
          <td>{{$reserve->kana}}</td>
          <th>利用回数</th>
          <td>
            @if (isset($reserve->member?->used_num))
              {{$reserve->member?->used_num}}回
            @endif
          </td>
        </tr>
        <tr>
          @for ($i = 0; $i < 4; $i++)
            @if (isset($reserve->member->tagMembers[$i]))
              <th>{{$reserve->member->tagMembers[$i]->label->name}}</th>
              <td>{{$reserve->member->tagMembers[$i]->tag->name}}</td>
            @else
              <th></th><td></td>
            @endif
          @endfor
          {{--  <th>会員ランク</th>
          <td>シルバー</td>
          <th>ラベル2</th>
          <td>ダミーダミー</td>
          <th>ラベル3</th>
          <td>ダミーダミー</td>
          <th>ラベル4</th>
          <td>ダミーダミー</td>  --}}
        </tr>
        <tr>
          <th>郵便番号</th>
          <td>{{$reserve->zip}}</td>
          <th>電話番号</th>
          <td>{{$reserve->tel}}</td>
          <!-- 以下2つは桁数次第ではレイアウトが崩れる分けてもよいかも -->
          <th>Mail</th>
          <td>{{$reserve->email}}</td>
          <th>LINE ID</th>
          <td>{{$reserve->member?->line_id}}{{--sun123--}}</td>
        </tr>
        <tr>
          <th>領収書のあて名</th>
          <td colspan="3">{{$reserve->receipt_address}}</td>
        </tr>
      </table>

      <!-- 到着予定 -->
      <div class="c-title__table">到着予定</div>
      <table class="l-table-confirm">
        <tr>
          <th>到着予定日</th>
          <td>{{$reserve->arrive_date?->isoFormat('YYYY/M/D(ddd)')}}</td>
          <th>到着予定時間</th>
          <td>{{$arrivalFlight?->arrive_time ? \Carbon\Carbon::parse($arrivalFlight->arrive_time)->format('H:i') : ''}}
            @if (false)
              <span class="c-label--delay">遅延</span>
            @endif
          </td>
          <th>到着便</th>
          <td>{{$arrivalFlight?->flight_no}}</td>
          <th>航空会社</th>
          <td>{{$arrivalFlight?->airline->name}}</td>
        </tr>
        <tr>
          <th>出発空港</th>
          <td>{{$arrivalFlight?->depAirport->name}}</td>
          <th>到着空港</th>
          <td>{{$arrivalFlight?->arrAirport->name}}</td>
          <th>到着ターミナル</th>
          <td>{{$arrivalFlight?->terminal_id}}</td>
          <td colspan="3">
            @if ($reserve->arrival_flg)
              <div class="c-label--lg">到着日とお迎え日が異なる</div>
            @endif
          </td>
        </tr>
      </table>

      <!-- 車両情報 -->
      <div class="c-title__table">車両情報</div>
      <table class="l-table-confirm">
        <tr>
          <th>メーカー</th>
          <td>{{$carMaker->name}}</td>
          <th>車種</th>
          <td>{{$car->name}}</td>
          <th>車番</th>
          <td>{{$reserve->car_number}}</td>
          <th>色</th>
          <td>{{$carColor->name}}</td>
        </tr>
        <tr>
          <th>区分</th>
          <td>{{$car->size_label}}</td>
          <th>人数</th>
          <td>{{$reserve->num_members ?? 1}}名</td>
          <th>車両取扱</th>
          <td colspan="3">{{$reserve->carCautions}}</td>
        </tr>
        <tr>
          <th>備考</th>
          <td>{!! nl2br(e($reserve->reserve_memo)) !!}</td>
        </tr>
      </table>


      <!--  -->
      <div class="c-button-group__form u-mt3">
        <button type="button" id="returnButton" onclick="location.href='{{route('manage.reserves.entry_info')}}';" class="c-button__submit--dark-gray u-h50">修正する</button>
        <button type="submit" name="confirm_btn" value="1" id="confirmButton" class="c-button__submit--green u-h50">予約を完了する</button>
        <button type="submit" name="to_register" value="0" class="c-button__pagination--next">お会計へ</button>
        <img src="{{ asset('images/card_5brand.png') }}" height="16" style="margin-left: 20px;">
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
