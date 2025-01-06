<!-- I-4 代理店 料金設定 -->
@extends('layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main l-container__main">
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">設定</li>
        <li class="l-breadcrumb__list">代理店料金設定</li>
        </li>
      </ul>

      @include('include.messages.errors')

      <div class="l-container__inner">
        <h2 class="c-title__lv2 l-flex--sb">代理店検索<span class="close_button c-button__close">閉じる</span></h2>

        <div class="is-active">
          <form action="{{route('manage.master.agency_prices.index')}}" class="u-mb1">
            <div>
              <div class="l-grid--col3 l-grid--cgap1">
                <div>
                  <label for="agency_code" class="u-font--md">代理店コード</label>
                  <input type="text" id="agency_code" name="agency_code" value="{{request('agency_code')}}" class="u-w-full-wide">
                </div>
                <div>
                  <label for="company_name" class="u-font--md">社名</label>
                  <input type="text" id="company_name" name="company_name" value="{{request('company_name')}}" class="u-w-full-wide">
                </div>
                <div>
                  <label for="tel" class="u-font--md">電話番号</label>
                  <input type="text" id="tel" name="tel" value="{{request('tel')}}" class="u-w-full-wide">
                </div>
              </div>
              <div>
                <label for="search_keywords" class="u-font--md">検索用キーワード</label>
                <input type="text" id="search_keywords" name="search_keywords" value="{{request('search_keywords')}}" class="u-w-full-wide u-mb0">
              </div>
              <button type="submit" class="c-button__register w-160 u-mt2 u-horizontal-auto">検索</button>
            </div>
            <div></div>
          </form>

          <h2>検索結果</h2>
          <ul class="l-flex l-flex--wrap l-grid--cgap2 l-grid--rgap1">
            @foreach ($agencies as $agency)
            <li class="pointer link-text">{{$agency->name}}</li>
            @endforeach
            {{--  <li class="pointer link-text">HIS なんば支店</li>
            <li class="pointer link-text">近畿ツーリスト 広島支店</li>  --}}
          </ul>
          <!-- 装飾 -->
          <div class="u-border--bottom u-pt2 u-mb2"></div>
        </div>

        <!-- 中段　料金表一覧 -->
        <div class="u-mb1">
          <h2 class="c-title__lv2 l-flex--sb">登録済み 代理店 適用料金表一覧<span class="close_button c-button__close">閉じる</span></h2>
          <!-- 検索フォーム -->
          <div class="is-active">
            <table class="l-table-list u-mb2">
              <thead>
                <tr class="l-table-list__head l-table-list--scroll__head">
                  <th><div class="c-button-sort">代理店名</div></th>
                  <th class="u-w240"><div class="c-button-sort --desc sort-enable">適用期間</div></th>
                  <th>メモ</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody class="l-table-list__body">
                @foreach ($agencyPrices as $agencyPrice)
                  <tr>
                    <td>{{$agencyPrice->agency->name}}</td>
                    <td>
                      {{$agencyPrice->start_date->format("Y/m/d")}} ～<br />{{$agencyPrice->end_date->format("Y/m/d")}}
                      {{--  2021/01/01～2021/12/31  --}}
                    </td>
                    <td>{{$agencyPrice->memo}}</td>
                    <td>
                      <button type="button" class="c-button__edit button_select" onclick="openEditModal({{$agencyPrice->id}})">編集</button>
                    </td>
                  </tr>
                @endforeach
                {{-- <tr>
                  <td>HIS なんば支店</td>
                  <td>2021/01/01～2021/12/31</td>
                  <td>メモメモメモ</td>
                  <td>
                    <button type="button" class="c-button__edit button_select">編集</button>
                  </td>
                </tr>  --}}
              </tbody>
            </table>
            <button type="button" class="button_select c-button__register u-mb4 u-horizontal-auto" onclick="openCreateModal()">新規登録</button>
          </div>
        </div>
      </div>

    </main><!-- /.l-container__main -->
  </div><!-- /.l-wrap -->

  @include('manage.master.components.agency_price_modal', [
    'mode' => 'new',
    'label' => '新規登録',
    'method' => 'POST',
    'action' => route('manage.master.agency_prices.store'),
    'agencyPrice' => null,
    'agencies' => $agencies,
    ]
  )

  <!-- 「編集」をクリックしたら出てくるmodal -->
  @foreach ($agencyPrices as $agencyPrice)
    @include('manage.master.components.agency_price_modal', [
      'mode' => 'edit',
      'label' => '編集',
      'method' => 'PUT',
      'action' => route('manage.master.agency_prices.update', [$agencyPrice->id]),
      'agencyPrice' => $agencyPrice,
      'agencies' => null,
      ]
    )
  @endforeach

@endsection
@push("scripts")
<!-- ▼閉じるボタン -->
  <script src="{{ asset('js/close_button_toggle.js') }}"></script>

  <!-- テーブルのヘッダ部分ソートスクリプト -->
  <script src="{{ asset('js/tableHeaderSort.js') }}"></script>

  <script>
    let createModal;
    let modalAreaOptions;
    let modalCloseOption;

    function openCreateModal() {
      createModal.classList.add('is-active');
    }
    function openEditModal(agencyPriceId) {
      document.getElementById(`modalAreaOption_edit_${agencyPriceId}`).classList.add('is-active');
    }
    function closeCreateModal() {
      createModal.classList.remove('is-active');
    }
    function closeEditModal(agencyPriceId) {
      document.getElementById(`modalAreaOption_edit_${agencyPriceId}`).classList.remove('is-active');
    }
    function deleteagencyPrice(agencyPriceId) {
      document.getElementById(`delete_${agencyPriceId}_form`).submit();
    }

    window.addEventListener('DOMContentLoaded', function() {
      createModal = document.getElementById('modalAreaOption_new_');
      modalAreaOptions = document.querySelectorAll('.modal_area');
      modalCloseOption = document.querySelectorAll('.modal_optionClose');
    })

    document.querySelectorAll('.l-table-list th .sort-enable').forEach(th => th.onclick = (e) => sortRows(e, '.l-table-list'));
  </script>
@endpush
