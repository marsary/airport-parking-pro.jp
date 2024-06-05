<!-- B-1-4  入力確認画面 -->
@extends('layouts.form.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "confirm"])

<div class="p-user-input__inner">
  <form action="{{route('form.reserves.store')}}" method="POST">
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
        <td>{{$reserve->agency_id}}{{--公式HP--}}</td>
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
        @if ($reserve->member)
          @foreach ($reserve->member->tagMembers as $tagMember)
            <th>{{$tagMember->label->name}}</th>
            <td>{{$tagMember->tag->name}}</td>
          @endforeach
        @endif
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
        <td>{{$reserve->zip}}{{--111-0000--}}</td>
        <th>電話番号</th>
        <td>{{$reserve->tel}}{{--090-1234-5678--}}</td>
        <!-- 以下2つは桁数次第ではレイアウトが崩れる分けてもよいかも -->
        <th>Mail</th>
        <td>{{$reserve->email}}</td>
        <th>LINE ID</th>
        <td>{{$reserve->member?->line_id}}{{--sun123--}}</td>
      </tr>
    </table>

    <!-- 到着予定 -->
    <div class="c-title__table">到着予定</div>
    <table class="l-table-confirm">
      <tr>
        <th>到着予定日</th>
        <td>{{$reserve->arrive_date?->isoFormat('YYYY/M/D(ddd)')}}</td>
        <th>到着予定時間</th>
        <td>{{$arrivalFlight?->arrive_time ? \Carbon\Carbon::parse($arrivalFlight->arrive_time)->format('H:i') : ''}}</td>
        <th>到着便</th>
        <td>{{$arrivalFlight?->flight_no}}{{--NH205--}}</td>
        <th>航空会社</th>
        <td>{{$arrivalFlight?->airline->name}}{{--ANA--}}</td>
      </tr>
      <tr>
        <th>出発空港</th>
        <td>{{$arrivalFlight?->depAirport->name}}{{--LAX--}}</td>
        <th>到着空港</th>
        <td>{{$arrivalFlight?->arrAirport->name}}{{--NRT--}}</td>
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
        <td>{{$reserve->remarks}}</td>
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
          <td>{{number_format($reserve->pricePerDay())}}円</td>
          <td>{{$reserve->num_days}}</td>
          <td>{{number_format($reserve->price)}}円</td>
          <td>({{ $reserve->getTaxTypeLabel(\App\Enums\TaxType::TEN_PERCENT->value) }})</td>
        </tr>
        @foreach ($reserve->dealGoodData as $dealGood)
          <tr>
            <th>{{$dealGood['name']}}</th>
            <td>{{number_format($dealGood['price'])}}円</td>
            <td>{{$dealGood['num']}}</td>
            <td>{{number_format($dealGood['total_price'])}}円</td>
            <td>({{ $dealGood['tax_type_label'] }})</td>
          </tr>
        @endforeach
        {{--  <tr>
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
        </tr>  --}}
      </tbody>
    </table>

    <div class="l-table-charge-detail--second">
      <div class="l-table-charge-detail--second__column">
        <div>消費税8%</div>
        <div>{{number_format($reserve->total_tax_8)}}円</div>
        <div colspan="2" class="text-right">合計金額</div>
        <div class="u-font--24 u-font--medium">{{number_format($reserve->total_price)}} <span>円</span>（税抜）</div>
        <div>消費税10%</div>
        <div>{{number_format($reserve->total_tax_10)}}円</div>
        <div class="u-font--24 u-font--medium l-grid--colspan2">{{number_format($reserve->totalCharge())}} <span>円</span>（税込）</div>
      </div>
    </div>

    <!--  -->
    <div class="c-button-group__form u-mt3">
      <a id="returnButton" href="{{route('form.reserves.option_select')}}" class="c-button__pagination--return">前のページに戻る</a>
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
