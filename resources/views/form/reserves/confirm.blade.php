<!-- B-1-4  入力確認画面 -->
@extends('layouts.form.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "confirm"])
@include('include.messages.errors')

<div class="p-user-input__inner">
  <form action="{{route('form.reserves.store')}}" method="POST">
    @csrf
    <div class="c-title__table">予約情報</div>
    <table class="l-table-confirm">
      {{-- <tr> --}}
        {{-- <th>予約コード</th>
        <td>{{$reserve->reserve_code}}</td>
        <th>受付コード</th>
        <td>1234567890</td> --}}
        {{-- <th>予約日時</th> --}}
        {{-- <td >{{$reserve->reserve_date->isoFormat('YYYY/M/D(ddd) H:m')}}</td> --}}
        {{-- <th>予約経路</th> --}}
      {{-- </tr> --}}
      <tr>
        <th>入庫日時</th>
        <td>{{$reserve->load_date->isoFormat('YYYY/M/D(ddd)') . ' ' . $reserve->load_time}}</td>
        <th>出庫予定日</th>
        <td>{{$reserve->unload_date_plan->isoFormat('YYYY/M/D(ddd)')}}</td>
        <th>利用日数</th>
        <td>{{$reserve->num_days}}日</td>
      </tr>
    </table>

    <!-- お客様情報 -->
    <div class="c-title__table">お客様情報</div>
    <table class="l-table-confirm">
      <tr>
        <th>氏名</th>
        <td>{{$reserve->name}}</td>
        <th>ふりがな</th>
        <td>{{$reserve->kana}}</td>
      </tr>
      <tr>
        <th>携帯番号</th>
        <td>{{$reserve->tel}}{{--090-1234-5678--}}</td>
        <th>Mail</th>
        <td>{{$reserve->email}}</td>
      </tr>
      <tr>
        <th>人数</th>
        <td>{{$reserve->num_members ?? 1}}名</td>
        <th>郵便番号</th>
        <td>{{$reserve->zip}}{{--111-0000--}}</td>
      </tr>
      <tr>
        <th>備考</th>
        <td></td>
      </tr>
    </table>

    <!-- 車両情報 -->
    <div class="c-title__table">車両情報</div>
    <table class="l-table-confirm">
      <tr>
        <th>メーカー</th>
        <td>{{$carMaker?->name}}</td>
        <th>車種</th>
        <td>{{$car?->name}}</td>
      </tr>
      <tr>
        <th>車番</th>
        <td>{{$reserve->car_number}}</td>
        <th>色</th>
        <td>{{$carColor?->name}}</td>
        {{--  <th>区分</th>
        <td>{{$car->size_label}}</td>  --}}
      </tr>
      <tr>
        <th>備考</th>
        <td>{{$reserve->remarks}}</td>
      </tr>
    </table>

    <!-- 到着予定 -->
    <div class="c-title__table">到着予定
      @if ($reserve->arrival_flg)
        <div class="c-label--lg">到着日とお迎え日が異なる</div>
      @endif
    </div>
    <table class="l-table-confirm">
      <tr>
        <th>到着予定日</th>
        <td>{{$reserve->arrive_date?->isoFormat('YYYY/M/D(ddd)')}}</td>
        <th>到着予定時間</th>
        <td>{{$arrivalFlight?->arrive_time ? \Carbon\Carbon::parse($arrivalFlight->arrive_time)->format('H:i') : ''}}</td>
      </tr>
      <tr>
        <th>航空会社</th>
        <td>{{$arrivalFlight?->airline->name ?? $airline->name}}{{--ANA--}}</td>
        <th>到着便</th>
        <td>{{$arrivalFlight?->flight_no ?? $reserve->flight_no}}{{--NH205--}}</td>
      </tr>
      <tr>
        <th>出発空港</th>
        <td>{{$arrivalFlight?->depAirport->name}}{{--LAX--}}</td>
        <th>到着空港</th>
        <td>{{$arrivalFlight?->arrAirport->name}}{{--NRT--}}</td>
        {{--  <th>到着ターミナル</th>
        <td>{{$arrivalFlight?->terminal_id}}</td>  --}}
      </tr>
    </table>

    <!-- 料金明細 -->
    <div class="c-title__table">料金明細</div>
    <table class="l-table-charge-detail">
      <thead class="l-table-charge-detail__head">
        <tr>
          <th>項目</th>
          <th>小計</th>
          <th>消費税</th>
        </tr>
      </thead>
      <tbody class="l-table-charge-detail__body">
        <tr>
          <th>駐車料金</th>
          <td class="u-font-nowrap">{{number_format($reserve->price)}}円</td>
          <td class="--tax">({{ $reserve->getTaxTypeLabel(\App\Enums\TaxType::TEN_PERCENT->value) }})</td>
        </tr>
        @foreach ($reserve->dealGoodData as $dealGood)
          <tr>
            <th>{{$dealGood['name']}}</th>
            <td class="u-font-nowrap">{{number_format($dealGood['price'])}}円</td>
            <td class="--tax">({{ $dealGood['tax_type_label'] }})</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="l-table-charge-detail--second">
      <div class="l-table-charge-detail--second__head">合計金額</div>
      <div class="u-font--lg u-font--medium">{{number_format($reserve->totalCharge())}} <span>円</span></div>
      <div class="--tax">（税込）</div>
      <div class="l-table-charge-detail--second__head">内消費税</div>
      <div>{{number_format($reserve->total_tax_10)}}円</div>
      <div></div>
    </div>

    <!--  -->
    <div class="c-button-group__form u-mt3">
    <button type="button" id="returnButton" onclick="location.href='{{route('form.reserves.option_select')}}';" class="c-button__pagination--return">前のページに戻る</button>
      <button type="submit" class="c-button__pagination--next">予約を完了する</button>
    </div>
  </form>
</div><!-- ./p-user-input__inner -->


@endsection
@push("scripts")
<!-- Enterキーで「次の入力欄に移動」する（Tabキーの代わり） -->
<script>
document.querySelectorAll('input').forEach(function(input) {
  // input, selectのみEnterで次の入力欄に移動。textareaは除外。
  const inputs = document.querySelectorAll('input, select');
  input.addEventListener('keydown', function(event) {
    // Enterキーが押された時の処理
    if (event.key === 'Enter') {
      // 【追加】日本語の変換中（確定のEnter）なら処理を抜ける
      if (event.isComposing || event.keyCode === 229) {
        return;
      }

      event.preventDefault(); // フォームの誤送信を防ぐ場合

      inputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault(); // 改行やSubmitを防止
            // 次の要素が存在すればフォーカスを移動
            if (inputs[index + 1]) {
              inputs[index + 1].focus();
            }
          }
        });
      });
    }
  });
});
</script>
@endpush
@push('css')
<style>
</style>
@endpush
