<!-- B-3-1 予約管理TOP > 検索 > 検索結果 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">

  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
  <li class="l-breadcrumb__list">予約管理TOP</li>
  <li class="l-breadcrumb__list">検索</li>
  <li class="l-breadcrumb__list">検索結果（{{$deals->count()}}件）</li>
  </ul>

  <div class="l-container__inner">

  {{$deals->links('vendor/pagination/manage-list')}}
  <!-- ページャー -->
  {{--  <div class="c-pager">
    <ul class="c-pager__list">
    <li class="c-pager__item--first"><a href="#" class="c-pager__link"><img src="{{ asset('images/icon/pager-last.svg') }}" width="15.5" height="18" /></a></li>
    <li class="c-pager__item--prev"><a href="#" class="c-pager__link"><img src="{{ asset('images/icon/pager-next.svg') }}" width="12" height="18" /></a></li>
    <li class="c-pager__item"><a href="#" class="c-pager__link">1</a></li>
    <li class="c-pager__item"><a href="#" class="c-pager__link">2</a></li>
    <li class="c-pager__item">3</li>
    <li class="c-pager__item"><a href="#" class="c-pager__link">4</a></li>
    <li class="c-pager__item"><a href="#" class="c-pager__link">5</a></li>
    <li class="c-pager__item"><a href="#" class="c-pager__link">6</a></li>
    <li class="c-pager__item">...</li>
    <li class="c-pager__item--next"><a href="#" class="c-pager__link"><img src="{{ asset('images/icon/pager-next.svg') }}" width="12" height="18" /></a></li>
    <li class="c-pager__item--last"><a href="#" class="c-pager__link"><img src="{{ asset('images/icon/pager-last.svg') }}" width="15.5" height="18" /></a></li>
    </ul>

    <input type="text" class="c-pager__input-pageNumber">/150
    <div class="c-form-select-wrap">
      <select name="limit" id="limit">
        <option value="25">25件</option>
        <option value="50">50件</option>
        <option value="75">75件</option>
        <option value="100">100件</option>
        <option value="150">150件</option>
      </select>
    </div>
  </div>  --}}

  <div class="l-table-l-table-list--scroll__wrappe u-mb3">
    <table id="search_result_table" class="l-table-list--scroll --blue" style="display: flex; width: 1120px; overflow-x: scroll;">
      <tr>
        <th>
          <div class="l-flex--center l-grid--cgap05">
              <input type="checkbox" id="check_all" name="check_all" value="1" class="u-mb0">
              <label for="check_all">すべて<br>選択</label>
          </div>
        </th>
        <th class="c-button-sort --active --desc test">予約コード</th>
        <th>予約日時</th>
        <th>予約経路</th>
        <th>受付コード</th>
        <th>入庫日時</th>
        <th>出庫予定日</th>
        <th>利用日数</th>
        <th>顧客コード</th>
        <th>お客様氏名</th>
        <th>ふりがな</th>
        <th>利用回数</th>
        <th>詳細</th>
      </tr>
      @foreach ($deals as $deal)
        <tr>
          <td>
            <input type="checkbox" name="sel_row" value="{{$deal->id}}">
          </td>
          <td>{{$deal->reserve_code}}</td>
          <td>{{$deal->reserve_date?->format('Y/m/d H:i')}}</td>
          <td>{{$deal->agency?->name}}</td>
          <td>{{$deal->receipt_code}}</td>
          <td>{{$deal->loadDateTime()}}</td>
          <td>{{$deal->unload_date_plan?->format("Y/m/d")}}</td>
          <td>{{$deal->num_days}}</td>
          <td>{{$deal->member?->member_code}}</td>
          <td>{{$deal->name}}</td>
          <td>{{$deal->kana}}</td>
          <td>{{$deal->member?->used_num}}</td>
          <td><a href="{{route('manage.deals.show', [$deal->id])}}">表示</a></td>
        </tr>

      @endforeach
      {{--  <tr>
        <td><input type="checkbox" name="select" value="1"></td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
        <td>テキスト</td>
      </tr>  --}}
    </table>
  </div><!-- /.l-table-list__wrapper -->

  <div class="c-button-group__form">
    <a href="" onclick="event.preventDefault(); getToUrl('{{route('manage.deals.search')}}'); " class="c-button__pagination--back c-link-no-border">戻る</a>
    <button id="csv_export_btn" class="c-button__submit--yellow u-w-auto c-link-no-border">選択したデータをダウンロード​</button>
  </div>

  </div><!-- /.l-container__inner -->
</main>


@endsection
@push("scripts")
<script>

  window.addEventListener('DOMContentLoaded', function() {
    const searchResultTable = document.getElementById('search_result_table');
    const checkAllBox = document.getElementById('check_all');
    const csvExportBtn = document.getElementById('csv_export_btn');

    checkAllBox.addEventListener('click', function() {
      let shouldCheck = true;
      if(!checkAllBox.checked) {
        shouldCheck = false;
      }
      checkAllCheckboxes(searchResultTable, "sel_row", shouldCheck);
    })

    csvExportBtn.addEventListener('click', () => exportCsv())

    function exportCsv() {
      const dealIds = getTableCheckedRowValues(searchResultTable, "sel_row");

      if (dealIds.length > 0) {
          const url = '{{route("manage.deals.search_export")}}';

          getToUrl(url, {'deal_ids' : dealIds});
      }
    }
  });


</script>
@endpush
@push('css')
<style>
</style>
@endpush

