<!-- B-3-3 取引詳細 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list">
        @if ($deal->transaction_type == \App\Enums\TransactionType::PURCHASE_ONLY->value)
          取引詳細(商品購入のみ)
        @else
          取引詳細
        @endif
      </li>
    </ul>

    @include('include.messages.errors')

    <div class="l-container__inner">

      <div class="l-breadcrumb-step__wrap">
        <div class="l-breadcrumb-step__item-gray {{ $deal->status == \App\Enums\DealStatus::NOT_LOADED->value ? '--current':'' }}">予約済み</div>
        <div class="l-breadcrumb-step__item-gray {{ $deal->status == \App\Enums\DealStatus::LOADED->value ? '--current':'' }}">入庫済</div>
        <div class="l-breadcrumb-step__item-gray {{ $deal->status == \App\Enums\DealStatus::UNLOADED->value ? '--current':'' }}">出庫済</div>
      </div>

      <div class="c-title__table">取引情報</div>
      <div class="l-table-top-parent">
        <div class="l-table-top-list {{ $extraPayment->needPayment ? '':'hidden' }}">
          <div class="l-table-top-item u-bg--red">追加清算あり[{{ number_format($extraPayment->additionalCharge) }}円]</div>
          <div class="l-table-top-item u-bg--yellow">保留: {{ $extraPayment->pendingDays }}日</div>
        </div>
        <table class=" l-table-confirm">
          <tr>
            <th>予約コード</th>
            <td>{{$deal->reserve_code}}</td>
            <th>受付コード</th>
            <td>{{$deal->receipt_code}}</td>
            <th>予約日時</th>
            <td>{{$deal->reserve_date?->isoFormat('YYYY/M/D(ddd) H:mm')}}</td>
            <th>予約経路</th>
            <td>{{$deal->agency?->name}}</td>
          </tr>
          <tr>
            {{--  <th>入庫日時予定</th>
            <td>2024/1/31(水) 10:00</td>  --}}
            <th>入庫日時</th>
            <td>{{$deal->load_date?->isoFormat('YYYY/M/D(ddd)') . ' ' . $deal->load_time}}</td>
            <th>出庫予定日</th>
            <td>{{$deal->unload_date_plan?->isoFormat('YYYY/M/D(ddd)')}}</td>
            <th>利用日数</th>
            <td>{{$deal->num_days}}日</td>
          </tr>
        </table>
      </div>

      <!-- 顧客情報 -->
      <div class="c-title__table">顧客情報</div>
      <table class="l-table-confirm">
        <tr>
          <th>顧客コード</th>
          <td>{{$deal->member?->member_code}}</td>
          <th>お客様氏名</th>
          <td>{{$deal->name}}</td>
          <th>ふりがな</th>
          <td>{{$deal->kana}}</td>
          <th>利用回数</th>
          <td>
            @if (isset($deal->member?->used_num))
              {{$deal->member?->used_num}}回
            @endif
          </td>
        </tr>
        @if (isset($deal->member->tagMembers))
          <tr>
            @for ($i = 0; $i < 4; $i++)
              @if (isset($deal->member->tagMembers[$i]))
                <th>{{$deal->member->tagMembers[$i]->label->name}}</th>
                <td>{{$deal->member->tagMembers[$i]->tag->name}}</td>
              @else
                <th></th><td></td>
              @endif
            @endfor
          </tr>
        @endif
        <tr>
          <th>郵便番号</th>
          <td>{{$deal->zip}}</td>
          <th>電話番号</th>
          <td>{{$deal->tel}}</td>
          <!-- 以下2つは桁数次第ではレイアウトが崩れる分けてもよいかも -->
          <th>Mail</th>
          <td>{{$deal->email}}</td>
          <th>LINE ID</th>
          <td>{{$deal->member?->line_id}}</td>
        </tr>
      </table>

      <!-- 到着予定 -->
      <div class="c-title__table">到着予定</div>
      <table class="l-table-confirm">
        <tr>
          {{--  <td>16:45<span class="c-label--delay">遅延</span></td>  --}}
          <th>到着予定日</th>
          <td>{{$arrivalFlight?->arrive_date?->isoFormat('YYYY/M/D(ddd)')}}</td>
          <th>到着予定時間</th>
          <td>
            {{$arrivalFlight?->arrive_time ? \Carbon\Carbon::parse($arrivalFlight->arrive_time)->format('H:i') : ''}}
            @if ($arrivalFlight?->is_delayed)
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
            @if ($deal->arrival_flg)
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
          <td>{{$deal->memberCar?->car->carMaker->name}}</td>
          <th>車種</th>
          <td>{{$deal->memberCar?->car->name}}</td>
          <th>車番</th>
          <td>{{$deal->memberCar?->number}}</td>
          <th>色</th>
          <td>{{$deal->memberCar?->carColor->name}}</td>
        </tr>
        <tr>
          <th>区分</th>
          <td>{{$deal->memberCar?->car->size_label}}</td>
          <th>人数</th>
          <td>{{$deal->num_members}}名</td>
          <th>車両取扱</th>
          <td colspan="3">{{$deal->carCautions()}}</td>
        </tr>
        <tr>
          <th>備考</th>
          <td colspan="3">{{$deal->remarks}}</td>
        </tr>
      </table>
      @if ($deal->transaction_type != \App\Enums\TransactionType::PURCHASE_ONLY->value)
        <a href="{{route('manage.deals.edit', [$deal->id])}}" class="u-mb3 u-horizontal-auto c-link-no-border c-button__submit--dark-gray">予約内容を変更</a>
      @endif

      <form action="{{route('manage.deals.update_memo', [$deal->id])}}" method="POST">
        @csrf
        @method('PUT')
        <div class="u-p2 u-bg--light-gray l-grid--col3 l-grid--gap2">
          @if (isset($deal->member))
            <div>
              <label class="c-title__table">顧客メモ</label>
              <textarea name="member_memo" id="customer-note" class="u-bg--white u-w-full-wide u-mb05" rows="3">{{$deal->member?->memo}}</textarea>
              <button name="save_member_memo_btn" type="submit" class="c-button--sm--text-color u-horizontal-auto">保存</button>
            </div>
          @endif
          <div>
            <label class="c-title__table">予約メモ</label>
            <textarea name="reserve_memo" id="reservation-note" class="u-bg--white u-w-full-wide u-mb05" rows="3">{{$deal->reserve_memo}}</textarea>
            <button name="save_reserve_memo_btn" type="submit" class="c-button--sm--text-color u-horizontal-auto">保存</button>
          </div>
          <div>
            <label class="c-title__table">受付メモ</label>
            <textarea name="reception_memo" id="reception-note" class="u-bg--white u-w-full-wide u-mb05" rows="3">{{$deal->reception_memo}}</textarea>
            <button name="save_reception_memo_btn" type="submit" class="c-button--sm--text-color u-horizontal-auto">保存</button>
          </div>
        </div>
      </form>

      <!--  -->
      <div class="c-button-group__form u-mt3">
        <a href="{{route('manage.registers.index', ['deal_id' => $deal->id])}}" class="c-button__submit--gray c-link-no-border">追加精算に進む</a>
        <form action="{{route('manage.deals.unload', [$deal->id])}}" method="post">
          @csrf
          @method('PUT')
          <button type="submit" class="c-button__pagination--next c-link-no-border u-w170">出庫済</button>
        </form>
      </div>

    </div><!-- ./p-user-container__inner -->

</main><!-- /.l-wrap__main -->
<!-- サイド固定ボタン -->
<button onclick="window.print(); return false;" class="c-button__right-fixed--gray">
  <img src="{{ asset('images/icon/print.svg') }}" width="30" height="32" />
</button>


@endsection
@push("scripts")
<script>

</script>
@endpush
@push('css')
<style>
</style>
@endpush
