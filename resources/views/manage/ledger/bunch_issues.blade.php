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
  <div id="pagination-section">
    <div class="date_period">
      <input type="date" name="start" id="start_date" value="{{request('start_date')}}" class="u-w210">
      <span>～</span>
      <input type="date" name="end" id="end_date" value="{{request('end_date')}}" class="u-w210">
      <button id="filterBtn">絞込</button>
    </div>
    {{$deals->links('vendor/pagination/manage-list')}}
  </div>

  <!-- 出庫一覧リスト -->
  <!-- 基本テキストは左揃え -->
  <form action="" class="contentTwo">
    <div class="l-table-list--scroll__wrapper u-mb3">
      <table id="dealTable" class="l-table-list--scroll --out --blue --sticky-second">
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
              <td class="text-center"><input type="checkbox" id="sel_row_{{$deal->id}}" name="sel_row" class="u-mb0 process" value="{{$deal->id}}" /></td>
              <td>
                @if ($deal->payment()->exists())
                  <span class="c-label__blue">清算済み</span>
                @else
                  <span class="c-label__deep-gray">未清算</span>
                @endif
              </td>
              <td class="text-center">{{$deal->statusLabel}}</td>
              <td class="u-font--white"><a href="#" data-deal_id="{{$deal->id}}" onclick="event.preventDefault();" class="c-label__green unloadBtn">処理</a></td>
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
          {{--  <tr>
            <td class="text-center"><input type="checkbox" id="" name="" class="u-mb0 process" /></td>
            <td><span class="c-label__blue">清算済み</span></td>
            <td class="text-center">出庫済</td>
            <td class="u-font--white"><a href="/hoge" class="c-label__green">処理</a></td>
            <td>成</td>
            <td class="text-center">1</td>
            <td>中島 栄一</td>
            <td>15</td>
            <td>8828</td>
            <td>クラウン</td>
            <td>黒</td>
            <td>1</td>
            <td>ZG-52</td>
            <td>07:30</td>
            <td></td>
            <td>BKK</td>
            <td class="text-center">1</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>01231100709</td>
          </tr>  --}}
          {{--  <tr>
            <td class="text-center"><input type="checkbox" id="" name="" class="u-mb0 process" /></td>
            <td class="text-center"><span class="c-label__deep-gray">未清算</span></td>
            <td class="text-center">未出庫</td>
            <td class="u-font--white"><a href="/hoge" class="c-label__green">処理</a></td>
            <td>成</td>
            <td class="text-center">2</td>
            <td>竹林 篤史</td>
            <td class="text-center">1</td>
            <td>2125</td>
            <td>レガシィツーリングワゴン</td>
            <td>二</td>
            <td>1</td>
            <td>VZ-52</td>
            <td>07:30</td>
            <td></td>
            <td>DAD</td>
            <td class="text-center">10</td>
            <td>◎洗車機シャンプー</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>01231100709</td>
          </tr>
          <tr>
            <td class="text-center"><input type="checkbox" id="" name="" class="u-mb0 process" /></td>
            <td class="text-center"><span class="c-label__deep-gray">未清算</span></td>
            <td class="text-center">未出庫</td>
            <td class="u-font--white"><a href="/hoge" class="c-label__green">処理</a></td>
            <td>成</td>
            <td class="text-center">2</td>
            <td>ダニエル・マイケル・アレン</td>
            <td class="text-center">1</td>
            <td>2125</td>
            <td>テリオスキッド</td>
            <td>二</td>
            <td>1</td>
            <td>VZ-52</td>
            <td>07:30</td>
            <td></td>
            <td>DAD</td>
            <td class="text-center">10</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>01231100709</td>
          </tr>  --}}
        </tbody>
      </table>
    </div><!-- /.l-table-list--scroll__wrapper -->
    <button id="unloadAllBtn" type="button" class="c-button__submit u-horizontal-auto">一括出庫処理</button>
  </form>
  <form id="updateStatusForm" action="{{route('manage.ledger.unload_all')}}" method="POST">
    @csrf
    @method('PUT')
    <input id="deal_ids" type="hidden" name="deal_ids" value="">
  </form>
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
      const unloadAllBtn = document.getElementById('unloadAllBtn');
      const unloadBtns = Array.from(document.getElementsByClassName('unloadBtn'));
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


        filterBtn.addEventListener('click', function() {
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

