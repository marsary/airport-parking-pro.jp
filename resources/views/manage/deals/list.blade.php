<!-- B-3-1 予約管理TOP > 検索 > 検索結果 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">

  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
  <li class="l-breadcrumb__list">予約管理TOP</li>
  <li class="l-breadcrumb__list">検索</li>
  <li class="l-breadcrumb__list">検索結果（250件）</li>
  </ul>

  <div class="l-container__inner">

  <!-- ページャー -->
  <div class="c-pager">
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
      <select name="" id="">
        <option value="">25件</option>
        <option value="">50件</option>
        <option value="">75件</option>
        <option value="">100件</option>
        <option value="">150件</option>
      </select>
    </div>
  </div>

  <div class="l-table-list__wrapper u-mb3">
    <table class="l-table-list--scroll --blue">
      <tr>
        <th>
        <div class="l-flex--center l-grid--cgap05">
            <input type="checkbox" id="all" name="select" value="1" class="u-mb0">
            <label for="all">すべて<br>選択</label>
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
      <tr>
        <td>
          <input type="checkbox" name="select" value="1">
        </td>
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
        <td><a href="/admin/reservation_detail.php">表示</a></td>
      </tr>
      <tr>
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
      </tr>
      <tr>
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
      </tr>
      <tr>
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
      </tr>
      <tr>
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
      </tr>
    </table>
  </div><!-- /.l-table-list__wrapper -->

  <div class="c-button-group__form">
    <a href="" class="c-button__pagination--back c-link-no-border">戻る</a>
    <a href="" class="c-button__submit--yellow u-w-auto c-link-no-border">選択したデータをダウンロード​</a>
  </div>

  </div><!-- /.l-container__inner -->
</main>


@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush

