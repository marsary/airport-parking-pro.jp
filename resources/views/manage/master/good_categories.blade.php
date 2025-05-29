<!-- G-7 商品カテゴリ設定 -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">商品カテゴリー設定</li>
        </li>
      </ul>

      @include('include.messages.errors')

      <div class="l-container__inner">
        <h2 class="c-title__lv2 l-flex--sb u-w-full-wide">新規登録 および 検索<span class="close_button c-button__close">閉じる</span></h2>
        <form method="GET" action="{{route('manage.master.good_categories.index')}}" class="u-mb4 u-font--md is-active">
          <div class="l-grid--col2-auto l-grid--gap2">
            <div>
              <label for="name">商品カテゴリ名</label>
              <input type="text" id="name" name="name" value="{{request('name')}}" class="c-form-input--w100 u-mb0 u-h38">
            </div>
            <div class="u-font--normal">
              <label for="division" class="u-font--md">区分</label>
              <!--  「出庫までに作業が必要」「出庫までに作業が不要」　radioボタン  -->
              <div class="c-button-optionSelect l-grid--col3 l-grid--gap1">
                <div>
                  <input type="radio" name="type" value="1" id="type1" {{request('type') == 1 ? "checked":""}}>
                  <label for="type1" class="u-h38 u-mb0">出庫までに作業が必要</label>
                </div>
                <div>
                  <input type="radio" name="type" value="2" id="type2" {{request('type') == 2 ? "checked":""}}>
                  <label for="type2" class="u-h38 u-mb0">出庫までに作業が不必要</label>
                </div>
                <button class="c-button__register --gray u-mt0 u-mb0">検索</button>
              </div>
            </div>
          </div>

          <button type="button" class="
          c-button__register is-block u-horizontal-auto u-mt2 u-font--normal button_select" onclick="openCreateModal()">新規登録</button>
        </form>

        <h2 class="c-title__lv2">登録済みカテゴリー一覧</h2>
        <table class="l-table-list">
          <thead class="l-table-list__head l-table-list--category_setting__head">
            <tr>
              <th><div class="c-button-sort sort-enable --asc">カテゴリー名</div></th>
              <th><div class="c-button-sort">区分</div></th>
              <th>メモ</th>
              <th> </th>
            </tr>
          </thead>
          <tbody class="l-table-list__body">
            @foreach ($goodCategories as $goodCategory)
              <tr>
                <td>{{$goodCategory->name}}</td>
                <td>{{\App\Enums\GoodCategoryType::tryFrom($goodCategory->type)?->label()}}</td>
                <td>{{$goodCategory->memo}}</td>
                <td>
                  <button class="c-button__edit button_select" onclick="openEditModal({{$goodCategory->id}})">編集</button>
                </td>
              </tr>
            @endforeach
            {{--  <tr>
              <td>洗車</td>
              <td>出庫までに作業が必要</td>
              <td>MEMOメモめも</td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>  --}}
          </tbody>
        </table>
      </div>
    </main>

  </div>
  @include('manage.master.components.good_category_modal', [
    'mode' => 'new',
    'label' => '新規登録',
    'method' => 'POST',
    'action' => route('manage.master.good_categories.store'),
    'goodCategory' => null,
    ]
  )

  <!-- 「編集」をクリックしたら出てくるmodal -->
  @foreach ($goodCategories as $goodCategory)
    @include('manage.master.components.good_category_modal', [
      'mode' => 'edit',
      'label' => '編集',
      'method' => 'PUT',
      'action' => route('manage.master.good_categories.update', [$goodCategory->id]),
      'goodCategory' => $goodCategory,
      ]
    )
  @endforeach

@endsection
@push("scripts")
  <script src="{{ asset('js/tableHeaderSort.js') }}"></script>
  <!-- 閉じるボタン -->
  <script src="{{ asset('js/close_button_toggle.js') }}"></script>
  <script>
    let createModal;
    let modalAreaOptions;
    let modalCloseOption;

    function openCreateModal() {
      createModal.classList.add('is-active');
    }
    function openEditModal(goodCategoryId) {
      document.getElementById(`modalAreaOption_edit_${goodCategoryId}`).classList.add('is-active');
    }
    function closeCreateModal() {
      createModal.classList.remove('is-active');
    }
    function closeEditModal(goodCategoryId) {
      document.getElementById(`modalAreaOption_edit_${goodCategoryId}`).classList.remove('is-active');
    }
    function deleteGoodCategory(goodCategoryId) {
      document.getElementById(`delete_${goodCategoryId}_form`).submit();
    }

    window.addEventListener('DOMContentLoaded', function() {
      createModal = document.getElementById('modalAreaOption_new_');
      modalAreaOptions = document.querySelectorAll('.modal_area');
      modalCloseOption = document.querySelectorAll('.modal_optionClose');
    })

    document.querySelectorAll('.l-table-list th .sort-enable').forEach(th => th.onclick = (e) => sortRows(e, '.l-table-list'));
  </script>
@endpush
