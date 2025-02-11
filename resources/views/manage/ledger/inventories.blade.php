<!-- D-1-1 D-1-2 入出庫一覧ページ -->
@extends('layouts.manage.authenticated')

@section('content')
  <main class="l-wrap__main">

    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list">入出庫一覧</li>
    </ul>

    <div class="l-container__inner" style="position: relative;">
      <!-- トグルボタン -->
      <div class="p-inventoryTransactions-toggle">
        入出庫済を表示
        <label class="c-button-toggle">
          <form id="toggle_form" action="#" method="GET">
            <input id="disp_loaded_unloaded_check" name="disp_loaded_unloaded" class="c-button-toggle__input" type="checkbox"
             {{old('disp_loaded_unloaded', request('disp_loaded_unloaded')) ? 'checked ':''}} role="switch" value="1"
            />
            <span class="c-button-toggle__slider"></span>
          </form>
        </label>
      </div>
      <ul class="l-table-list--scroll__tab">
        <li id="buttonOne" class="l-table-list--scroll__tab-item is-active" onclick="">入庫</li>
        <li id="buttonTwo" class="l-table-list--scroll__tab-item" onclick="">出庫</li>
      </ul>

      <div class="l-table-list--scroll__wrapper">
        <!-- 入庫一覧リスト -->
        <!-- trにaタグは無理なので data-href でリンク -->
        <table id="loadTable" class="l-table-list--scroll --in contentOne is-active">
          <tr>
            <th>清算状況</th>
            <th>ステータス</th>
            <th>予約ID</th>
            <!-- ソートは「--desc」 と 「--asc」 で切替 -->
            <th class="c-button-sort --desc">顧客ID</th>
            <th>氏名​</th>
            <th>来場日​</th>
            <th>時間</th>
            <th>入庫日</th>
            <th>出庫日</th>
            <th>日</th>
            <th>到着便​</th>
            <th>時間</th>
            <th>出発地​</th>
            <th>車種</th>
            <th>車番​</th>
            <th>色</th>
            <th>顧客</th>
            <th>率</th>
            <th>支</th>
            <th>IE</th>
            <th>利用</th>
            <th>予約引継</th>
          </tr>
          @foreach ($loadDeals as $loadDeal)
            <tr data-href="{{route('manage.deals.show', [$loadDeal->id])}}">
                <td>
                  @if ($loadDeal->payment()->exists())
                    <span class="c-label__blue">清算済み</span>
                  @else
                    <span class="c-label__deep-gray">未清算</span>
                  @endif
                </td>
                <td class="text-center">{{$loadDeal->statusLabel}}</td>
                <td>{{$loadDeal->reserve_code}}</td>
                <td>{{$loadDeal->member_id}}</td>
                <td>{{$loadDeal->name}}</td>
                <td>{{$loadDeal->visit_date_plan?->format('Y-m-d')}}</td>
                <td>{{formatDate($loadDeal->visit_time_plan, 'H:i')}}</td>
                <td>{{$loadDeal->load_date?->format('m-d')}}</td>
                <td>{{$loadDeal->unload_date?->format('m-d')}}</td>
                <td>{{$loadDeal->num_days}}</td>
                <td>{{$loadDeal->arrivalFlight?->flight_no ?? '-'}}</td>
                <td>{{formatDate($loadDeal->arrivalFlight?->arrive_time, 'H:i')}}</td>
                <td>{{$loadDeal->arrivalFlight?->depAirport?->name}}</td>
                <td>{{$loadDeal->memberCar?->car->name}}</td>
                <td>{{$loadDeal->memberCar?->number}}</td>
                <td>{{$loadDeal->memberCar?->carColor->name}}</td>
                <td>{{$loadDeal->member?->memberType?->name}}</td>
                <td>{{$loadDeal->dsc_rate}}</td>
                <td></td>
                <td></td>
                <td>{{$loadDeal->member?->used_num}}</td>
                <td>{{$loadDeal->reserve_memo}}</td>
            </tr>
          @endforeach
        </table>

        <!-- 出庫一覧リスト -->
        <!-- 基本テキストは左揃え -->
        <table id="unloadTable" class="l-table-list--scroll --out --blue contentTwo is-none">
          <tr>
            <th>清算状況</th>
            <th>ステータス</th>
            <th>事</th>
            <th class="c-button-sort --active --desc test">SEQ​</th>
            <th>氏名</th>
            <th>利</th>
            <th>車番</th>
            <th>車種</th>
            <th>色</th>
            <th>夕</th>
            <th>帰国便</th>
            <th>時間</th>
            <th></th>
            <th>出</th>
            <th>人</th>
            <th>洗車</th>
            <th>雨</th>
            <th>IE</th>
            <th>注意事項</th>
            <th>預り物</th>
            <th>取扱</th>
            <th>追/返</th>
            <th>pt</th>
            <th>受付ID</th>
          </tr>
          @foreach ($unloadDeals as $unloadDeal)
            <tr data-href="{{route('manage.deals.show', [$unloadDeal->id])}}">
              <td>
                @if ($unloadDeal->payment()->exists())
                  <span class="c-label__blue">清算済み</span>
                @else
                  <span class="c-label__deep-gray">未清算</span>
                @endif
              </td>
              <td class="text-center">{{$unloadDeal->statusLabel}}</td>
              <td>{{$unloadDeal->office->name}}</td>
              <td class="text-center"></td>
              <td>{{$unloadDeal->name}}</td>
              <td>{{$unloadDeal->member?->used_num}}</td>
              <td>{{$unloadDeal->memberCar?->number}}</td>
              <td>{{$unloadDeal->memberCar?->car->name}}</td>
              <td>{{$unloadDeal->memberCar?->carColor->name}}</td>
              <td>{{$unloadDeal->arrivalFlight?->airportTerminal->terminal_id}}</td>
              <td>{{$unloadDeal->arrivalFlight?->name}}</td>
              <td>{{formatDate($unloadDeal->arrivalFlight?->arrive_time, 'H:i')}}</td>
              <td></td>
              <td>{{$unloadDeal->arrivalFlight?->depAirport?->name}}</td>
              <td class="text-center">{{$unloadDeal->num_members}}</td>
              <td>
                @php
                  $isFirstDispItem = true;
                @endphp
                @foreach ($unloadDeal->dealGoods as $dealGood)
                  {{--  good_category_idが1洗車のもの  --}}
                  @if($dealGood->good->good_category_id == $senshaCategoryId)
                    @if (!$isFirstDispItem)
                      <br />
                    @endif
                    {{$dealGood->good->name}}
                    @php
                      $isFirstDispItem = false;
                    @endphp
                  @endif
                @endforeach
              </td>
              <td></td>
              <td></td>
              <td>{{$unloadDeal->reserve_memo}}</td>
              <td></td>
              <td>{!! $unloadDeal->carCautions('<br />') !!}</td>
              <td></td>
              <td></td>
              <td>{{$unloadDeal->receipt_code}}</td>
            </tr>
          @endforeach
        </table>
      </div><!-- /.l-table-list--scroll__wrapper -->
    </div>
  </main><!-- /.l-container__main -->
@endsection
@push("scripts")
  <!-- // 入庫と出庫の表示を切り替えるJS -->
  <script src="{{ asset('js/toggle_display.js') }}" defer></script>
  <script src="{{ asset('js/pages/manage/ledger/inventories.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const trs = document.querySelectorAll('tr[data-href]');
      trs.forEach((tr, index) => {
          tr.addEventListener('click', function(e) {
            if (!e.target.closest('a')) {
              window.location = tr.getAttribute('data-href');
            }
          });
      });
    });
  </script>
@endpush
