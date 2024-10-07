<!-- D-1-1 D-1-2 入出庫一覧ページ -->
@extends('layouts.manage.authenticated')

@section('content')

<main class="l-wrap__main">

  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">一括出庫処理</li>
  </ul>

  @include('include.messages.errors')

  <div class="l-container__inner" style="position: relative;">
    <!-- 出庫一覧リスト -->
    <!-- 基本テキストは左揃え -->
    <div class="contentTwo">
      <div class="l-table-list--scroll__wrapper u-mb3 u-pt3" style="position: relative;">
        <!-- テーブル右上 -->
        <div class="p-inventoryTransactions-bulk-date le-list--scroll__wrappe">
          <!-- 日付を選択 input -->
          <form class="p-inventoryTransactions-bulk-date__simple-select-date">
            <input type="date" name="start" id="start_date" value="{{request('start_date')}}" min="2024-09-10" > ～
            <input type="date" name="end" id="end_date" value="{{request('end_date')}}" min="2024-09-11" >
            <button id="filterBtn" class="c-button--deep-gray u-h-full u-pt05 u-pb05">絞込</button>
          </form>
          <!-- ページャー -->
          {{$deals->links('vendor/pagination/manage-list')}}
        </div>
        <!-- テーブル右上 fin -->
        <table class="l-table-list--scroll --out --blue --sticky-second">
          <thead>
            <tr>
              <th>
                <label class="u-mb0 l-flex--center l-grid--gap025 process_all"><input type="checkbox" name="all" class="u-mb0" />一括<br>処理</label>
              </th>
              <th>清算状況</th>
              <th>ステータス</th>
              <th>出庫</th>
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
          </thead>
          <tbody>
            @foreach ($deals as $deal)
              <tr>
                <td class="text-center">
                  <input type="checkbox" id="sel_row_{{$deal->id}}" name="deal_id[]" class="u-mb0 process" form="bulk-check" value="{{$deal->id}}" />
                </td>
                <td>
                  @if ($deal->payment()->exists())
                    <span class="c-label__blue">清算済み</span>
                  @else
                    <span class="c-label__deep-gray">未清算</span>
                  @endif
                </td>
                <td class="text-center">{{$deal->statusLabel}}</td>
                <td class="u-font--white">
                  <form action="{{route('manage.ledger.unload_all')}}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="deal_id[]" value="{{$deal->id}}" />
                    <button type="submit" data-deal_id="{{$deal->id}}" class="c-label__green unloadBtn">処理</button>
                  </form>
                </td>
                <td>{{$deal->office->name}}</td>
                <td class="text-center">{{$deal->id}}:{{$deal->unload_date_plan?->format('Y/m/d')}}:{{$deal->unload_date?->format('Y/m/d')}}</td> {{-- debug --}}
                <td>{{$deal->name}}</td>
                <td>{{$deal->member?->used_num}}</td>
                <td>{{$deal->memberCar?->number}}</td>
                <td>{{$deal->memberCar?->car->name}}</td>
                <td>{{$deal->memberCar?->carColor->name}}</td>
                <td>{{$deal->arrivalFlight?->airportTerminal->terminal_id}}</td>
                <td>{{$deal->arrivalFlight?->name}}</td>
                <td>{{formatDate($deal->arrivalFlight?->arrive_time, 'H:i')}}</td>
                <td></td>
                <td>{{$deal->arrivalFlight?->depAirport?->name}}</td>
                <td class="text-center">{{$deal->num_members}}</td>
                <td>
                  @php
                    $isFirstDispItem = true;
                  @endphp
                  @foreach ($deal->dealGoods as $dealGood)
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
                <td>{{$deal->reserve_memo}}</td>
                <td></td>
                <td>{!! $deal->carCautions('<br />') !!}</td>
                <td></td>
                <td></td>
                <td>{{$deal->receipt_code}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- /.l-table-list--scroll__wrapper -->
      <form id="bulk-check" action="{{route('manage.ledger.unload_all')}}" method="POST" class="l-table-list--scroll__wrapper">
        @csrf
        @method('PUT')
        <button type="submit" class="c-button__submit u-horizontal-auto">一括出庫処理</button>
      </form>
    </div>
  </div><!-- ./l-container__inner -->
</main>

@endsection
@push("scripts")
  <!-- FullCalendar JavaScript -->
	<script src="//unpkg.com/@popperjs/core@2" defer></script>
	<script src="//unpkg.com/tippy.js@6" defer></script>
	<script src="//cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.js" defer></script>
	{{--  <script src="./js/calendar_inventory.js" defer></script>  --}}

  <!-- // 出庫処理のすべてをチェックする処理 -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const currentUrl = location.pathname;
      const filterBtn = document.getElementById('filterBtn');
      const dealTable = document.getElementById('dealTable');
      const updateStatusForm = document.getElementById('updateStatusForm');
      const dealIdsInput = document.getElementById('deal_ids');
        // 「一括処理」チェックボックスを取得
        const allCheckbox = document.querySelector('input[name="all"]');
        // テーブル内のすべてのチェックボックスを取得
        const checkboxes = document.querySelectorAll('.process');

        // 「一括処理」チェックボックスにイベントリスナーを追加
        allCheckbox.addEventListener('click', function() {
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = allCheckbox.checked;
            });
        });


        filterBtn.addEventListener('click', function(e) {
          e.preventDefault();
          const startDate = document.getElementById('start_date').value;
          const endDate = document.getElementById('end_date').value;
          getToUrl(currentUrl, {
            'start_date' : startDate,
            'end_date' : endDate,
          });
        });
    });
  </script>
@endpush
@push('css')
<style>
  #pagination-section {
  display: flex;
  justify-content: end;
  gap: 3%;
}
.date_period {
  display: flex;
  gap:2%;
}
#filterBtn {
  width: 72px;
  background-color: black;
  color: white;
  border-radius: .5rem;
  height: 38px;
  cursor: pointer;
  text-align: center;
  white-space: nowrap;
  font-weight: 500;
}
#filterBtn:hover {
  opacity: .8;
}
</style>
@endpush

