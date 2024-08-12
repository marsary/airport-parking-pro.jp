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
            <input name="disp_loaded_unloaded" class="c-button-toggle__input" type="checkbox"
             {{old('disp_loaded_unloaded', request('disp_loaded_unloaded')) ? 'checked ':''}} role="switch" value="1"
             onchange="document.getElementById('toggle_form').submit();"
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
        <table class="l-table-list--scroll --in contentOne is-active">
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
                <td>{{$loadDeal->statusLabel}}</td>
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
                <td>{{$loadDeal->member->memberType?->name}}</td>
                <td>{{$loadDeal->dsc_rate}}</td>
                <td></td>
                <td></td>
                <td>{{$loadDeal->member->used_num}}</td>
                <td>{{$loadDeal->reserve_memo}}</td>
            </tr>
          @endforeach
          {{--  <tr data-href="/hoge/">
            <td><span class="c-label__green">未清算</span></td>
            <td>入庫処理</td>
            <td>011545110</td>
            <td>1580204</td>
            <td>島田 光明</td>
            <td>2023-02-24</td>
            <td>00:00</td>
            <td></td>
            <td>02-26</td>
            <td>13</td>
            <td>GK-102</td>
            <td>11:30</td>
            <td>CTD</td>
            <td>ムーヴキャンパス</td>
            <td>1016</td>
            <td>青</td>
            <td>メンバー</td>
            <td>1000/td>
            <td></td>
            <td></td>
            <td>8</td>
            <td></td>
          </tr>
          <tr data-href="/">
            <td><span class="c-label__deep-gray">清算済</span></td>
            <td>出庫済み</td>
            <td>011545147</td>
            <td>1580217</td>
            <td>吉井 博</td>
            <td>2023-02-22</td>
            <td>05:30</td>
            <td> </td>
            <td>02-27</td>
            <td>12</td>
            <td>GL-742</td>
            <td>20:14</td>
            <td>KIX</td>
            <td>ボルボ</td>
            <td>77</td>
            <td>茶</td>
            <td>新規</td>
            <td>0</td>
            <td></td>
            <td></td>
            <td>1</td>
            <td></td>
          </tr>  --}}
        </table>

        <!-- 出庫一覧リスト -->
        <!-- 基本テキストは左揃え -->
        <table class="l-table-list--scroll --out --blue contentTwo is-none">
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
          <tr data-href="/hoge/">
            <td><span class="c-label__blue">清算済み</span></td>
            <td class="text-center">出庫済</td>
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
          </tr>
          <tr data-href="/hoge/">
            <td class="text-center"><span class="c-label__deep-gray">未清算</span></td>
            <td class="text-center">未出庫</td>
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
          <tr data-href="/hoge/">
            <td class="text-center"><span class="c-label__deep-gray">未清算</span></td>
            <td class="text-center">未出庫</td>
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
          </tr>

        </table>
      </div><!-- /.l-table-list--scroll__wrapper -->
    </div>
  </main><!-- /.l-container__main -->
@endsection
@push("scripts")
  <!-- // 入庫と出庫の表示を切り替えるJS -->
  <script src="{{ asset('js/toggle_display.js') }}" defer></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const trs = document.querySelectorAll('tr[data-href]');
      trs.forEach((tr, index) => {
        //if (index >= 1) { // 2つ目以降の<tr>要素に対して処理を行う
          tr.addEventListener('click', function(e) {
            if (!e.target.closest('a')) {
              window.location = tr.getAttribute('data-href');
            }
          });
        //}
      });
    });
  </script>
@endpush
