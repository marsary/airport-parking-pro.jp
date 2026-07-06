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
    <table class="l-table-confirm l-table-confirm--stable l-table-confirm--3pairs">
      <colgroup>
      <col class="l-table-confirm__col-key">
      <col class="l-table-confirm__col-val">
      <col class="l-table-confirm__col-key">
      <col class="l-table-confirm__col-val">
      <col class="l-table-confirm__col-key">
      <col class="l-table-confirm__col-val">
      </colgroup>
      {{-- <tr> --}}
        {{-- <th>予約コード</th>
        <td>{{$reserve->reserve_code}}</td>
        <th>受付コード</th>
        <td>1234567890</td> --}}
        {{-- <th>予約日時</th> --}}
        {{-- <td >{{$reserve->reserve_date->isoFormat('YYYY/M/D(ddd) H:m')}}</td> --}}
        {{-- <th>予約経路</th> --}}
        {{-- <td colspan="3">{{$agency?->name}}{{--公式HP--}}{{-- </td> --}}
      {{-- </tr> --}}
      <tr>
        <th class="l-table-confirm__col-key">入庫日時</th>
        <td class="l-table-confirm__col-val">{{$reserve->load_date->isoFormat('YYYY/M/D(ddd)') . ' ' . $reserve->load_time}}</td>
        <th class="l-table-confirm__col-key">出庫予定日</th>
        <td class="l-table-confirm__col-val">{{$reserve->unload_date_plan->isoFormat('YYYY/M/D(ddd)')}}</td>
        <th class="l-table-confirm__col-key">利用日数</th>
        <td class="l-table-confirm__col-val">{{$reserve->num_days}}日</td>
      </tr>
    </table>

    <!-- 顧客情報 -->
    <div class="c-title__table">顧客情報</div>
    <table class="l-table-confirm l-table-confirm--stable">
      <colgroup>
      <col class="l-table-confirm__col-key">
      <col class="l-table-confirm__col-val">
      <col class="l-table-confirm__col-key">
      <col class="l-table-confirm__col-val">
      </colgroup>
      <tr>
        {{-- <th>顧客コード</th>
        <td>{{$reserve->member?->member_code}}</td> --}}
        <th class="l-table-confirm__col-key">お客様氏名</th>
        <td class="l-table-confirm__col-val">{{$reserve->name}}</td>
        <th class="l-table-confirm__col-key">ふりがな</th>
        <td class="l-table-confirm__col-val">{{$reserve->kana}}</td>
        {{--  <th>利用回数</th>
        <td>
          @if (isset($reserve->member?->used_num))
            {{$reserve->member?->used_num}}回
          @endif
        </td>  --}}
      </tr>
      {{-- <tr>
        @for ($i = 0; $i < 4; $i++)
          @if (isset($reserve->member->tagMembers[$i]))
            <th>{{$reserve->member->tagMembers[$i]->label->name}}</th>
            <td>{{$reserve->member->tagMembers[$i]->tag->name}}</td>
          @else
            <th></th><td></td>
          @endif
        @endfor
      </tr> --}}
      <tr>
        <th class="l-table-confirm__col-key">携帯番号</th>
        <td class="l-table-confirm__col-val">{{$reserve->tel}}{{--090-1234-5678--}}</td>
        <!-- 以下2つは桁数次第ではレイアウトが崩れる分けてもよいかも -->
        <th class="l-table-confirm__col-key">Mail</th>
        <td class="l-table-confirm__value l-table-confirm__value--long">{{$reserve->email}}</td>
        {{-- <th>LINE ID</th>
        <td>{{$reserve->member?->line_id}}</td> --}}
      </tr>
      <tr>
        <th class="l-table-confirm__col-key">人数</th>
        <td class="l-table-confirm__col-val">{{$reserve->num_members ?? 1}}名</td>
        <th class="l-table-confirm__col-key">郵便番号</th>
        <td class="l-table-confirm__col-val">{{$reserve->zip}}{{--111-0000--}}</td>
      </tr>
      <tr style="display:none;">
        <th class="l-table-confirm__col-key">備考</th>
        <td class="l-table-confirm__value l-table-confirm__value--long" colspan="3">{{$reserve->remarks}}</td>
      </tr>
    </table>

    <!-- 車両情報 -->
    <div class="c-title__table">車両情報</div>
    <table class="l-table-confirm l-table-confirm--stable">
      <colgroup>
        <col class="l-table-confirm__col-key">
        <col class="l-table-confirm__col-val">
        <col class="l-table-confirm__col-key">
        <col class="l-table-confirm__col-val">
      </colgroup>
      <tr>
        <th class="l-table-confirm__col-key">メーカー</th>
        <td class="l-table-confirm__col-val">{{$carMaker?->name}}</td>
        <th class="l-table-confirm__col-key">車種</th>
        <td class="l-table-confirm__col-val">{{$car?->name}}</td>
      </tr>
      <tr>
        <th class="l-table-confirm__col-key">車番</th>
        <td class="l-table-confirm__col-val">{{$reserve->car_number}}</td>
        <th class="l-table-confirm__col-key">色</th>
        <td class="l-table-confirm__col-val">{{$carColor?->name}}</td>
        {{--  <th>区分</th>
        <td>{{$car->size_label}}</td>  --}}
        {{-- <th>車両取扱</th>
        <td colspan="3">{{$reserve->carCautions}}</td> --}}
      </tr>
    </table>

    <!-- 到着予定 -->
    <div class="c-title__table">
      到着予定
      @if ($reserve->arrival_flg)
        <!-- 到着日とお迎え日が異なる場合に表示 -->
        <div class="--mark c-label--lg">到着日とお迎え日が異なる</div>
      @endif
    </div>
    <table class="l-table-confirm l-table-confirm--stable">
      <colgroup>
        <col class="l-table-confirm__col-key">
        <col class="l-table-confirm__col-val">
        <col class="l-table-confirm__col-key">
        <col class="l-table-confirm__col-val">
      </colgroup>
      <tr>
        <th class="l-table-confirm__col-key">到着予定日</th>
        <td class="l-table-confirm__col-val">{{$reserve->arrive_date?->isoFormat('YYYY/M/D(ddd)')}}</td>
        <th class="l-table-confirm__col-key">到着予定時間</th>
        <td class="l-table-confirm__col-val">{{$arrivalFlight?->arrive_time ? \Carbon\Carbon::parse($arrivalFlight->arrive_time)->format('H:i') : ''}}</td>
      </tr>
      <tr>
        <th class="l-table-confirm__col-key">航空会社</th>
        <td class="l-table-confirm__col-val">{{$arrivalFlight?->airline->name ?? ($airline->name ?? '')}}{{--ANA--}}</td>
        <th class="l-table-confirm__col-key">到着便</th>
        <td class="l-table-confirm__col-val">{{$arrivalFlight?->flight_no ?? $reserve->flight_no}}{{--NH205--}}</td>
      </tr>
      <tr>
        <th class="l-table-confirm__col-key">出発空港</th>
        <td class="l-table-confirm__col-val">{{$arrivalFlight?->depAirport->name}}{{--LAX--}}</td>
        <th class="l-table-confirm__col-key">到着空港</th>
        <td class="l-table-confirm__col-val">{{$arrivalFlight?->arrAirport->name}}{{--NRT--}}</td>
        {{--  <th>到着ターミナル</th>
        <td>{{$arrivalFlight?->terminal_id}}</td>  --}}
      </tr>
    </table>

    <!-- オプション選択 -->
    <div class="c-title__table">オプション選択</div>
    <table class="l-table-confirm l-table-confirm--stable">
      <colgroup>
        <col class="l-table-confirm__col-key">
        <col class="l-table-confirm__col-val">
        <col class="l-table-confirm__col-key" style="display:none;">
        <col class="l-table-confirm__col-val" style="display:none;">
        <col class="l-table-confirm__col-val">
        <col class="l-table-confirm__col-key">
        <col class="l-table-confirm__col-val">
      </colgroup>
      <tr>
        <th class="l-table-confirm__col-key">旅行保険の加入</th>
        <td class="l-table-confirm__col-val">
          @if ($reserve->insurance)
            はい
          @else
            いいえ
          @endif
        </td>
        <th class="l-table-confirm__col-key" style="display:none;">洗車を希望</th>
        <td class="l-table-confirm__col-val" style="display:none;">
          @if ($reserve->carwash)
            希望する
          @else
            希望しない
          @endif
        </td>
        <th class="l-table-confirm__col-key">メルマガ希望</th>
        <td class="l-table-confirm__col-val">
          @if ($reserve->newsletter)
            希望する
          @else
            希望しない
          @endif
        </td>
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
          <td class="u-font-nowrap">{{number_format($reserve->price + $reserve->tax)}}円</td>
          <td class="--tax">({{ $reserve->getTaxTypeLabel(\App\Enums\TaxType::TEN_PERCENT->value) }})</td>
        </tr>
        @if (!empty($reserve->season_price))
        <tr>
          <th>シーズン料金</th>
          <td class="u-font-nowrap">{{number_format($reserve->season_price + $reserve->season_price_tax)}}円</td>
          <td class="--tax">({{ $reserve->getTaxTypeLabel(\App\Enums\TaxType::TEN_PERCENT->value) }})</td>
        </tr>
        @endif
        @foreach ($reserve->dealGoodData as $dealGood)
          <tr>
            <th>{{$dealGood['name']}}</th>
            <td class="u-font-nowrap">{{number_format($dealGood['total_price'])}}円</td>
            <td class="--tax">({{ $dealGood['tax_type_label'] }})</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="l-table-charge-detail--second">
      {{-- <div>消費税8%</div>
      <div>{{number_format($reserve->total_tax_8)}}円</div> --}}
      {{-- <div></div> --}}
      <div class="l-table-charge-detail--second__head">合計金額</div>
      <div class="u-font--lg u-font--medium l-grid--colspan2">{{number_format($reserve->totalCharge())}} <span>円</span></div>
      <div class="u-font--normal" >（税込）</div>
      <div>内消費税</div>
      <div>{{number_format($reserve->total_tax_10 + $reserve->season_price_tax)}}円</div>
      <div></div>
      <div style="display:none;"></div>
      <div class="u-font--lg u-font--medium" style="display:none;">{{number_format($reserve->total_price)}} <span>円</span></div>
      <div class="--tax" style="display:none;">（税抜）</div>
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
