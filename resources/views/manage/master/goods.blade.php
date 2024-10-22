<!-- G-8 商品設定 -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">商品設定</li>
      </ul>

      @include('include.messages.errors')

      <div class="l-container__inner">
        <h2 class="c-title__lv2 l-flex--sb u-w-full-wide">新規登録 および 検索<span class="close_button c-button__close">閉じる</span></h2>
        <form method="GET" action="{{route('manage.master.goods.index')}}" class="u-mb3 is-active">
            <div class="l-grid--col2-1fr_auto l-grid--end l-grid--gap1 u-mb2">
              <div class="l-grid--col2 l-grid--gap1">
                <div>
                  <!-- 商品名 -->
                  <label for="name" class="u-font--md">商品名</label>
                  <input type="text" name="name" id="name" value="{{request('name')}}" class="u-w-full-wide u-mb0">
                </div>
                <div>
                  <!-- カテゴリーselect -->
                  <label for="category" class="u-font--md">カテゴリー</label>
                  <div class="c-form-select-color u-mb0">
                    <select name="good_category_id" id="good_category_id">
                      <option value="" selected>選択してください</option>
                      @foreach ($goodCategories as $goodCategory)
                        <option value="{{ $goodCategory->id }}"
                          {{request('good_category_id')==$goodCategory->id ? 'selected':''}}>
                          {{$goodCategory->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <button class="c-button__register --gray u-mb0">検索</button>
          </div>

          <div class="l-flex l-flex--center l-grid--gap1">
            <button type="button" class="c-button__register button_select" onclick="openCreateModal()">新規登録</button>
          </div>
        </form>

        <!-- 登録済み商品一覧 -->
        <h2 class=" c-title__lv2">登録済み商品一覧</h2>
        <table class="l-table-list">
          <thead class="l-table-list__head l-table-list__head--sort l-table-list--scroll-vertical__head">
            <tr>
              <th><div class="c-button-sort sort-enable --asc">商品名</div></th>
              <th><div class="c-button-sort">略称</div></th>
              <th><div class="c-button-sort">商品カテゴリ</div></th>
              <th><div class="c-button-sort">税区分</div></th>
              <th><div class="c-button-sort">金額（税抜）</div></th>
              <th>メモ</th>
              <th></th>
            </tr>
          </thead>

          <tbody class="l-table-list__body">
            @foreach ($goods as $good)
              <tr>
                <td>{{$good->name}}</td>
                <td>{{$good->abbreviation}}</td>
                <td>{{$good->goodCategory->name}}</td>
                <td class="text-right">{{\App\Enums\TaxType::tryFrom($good->tax_type)?->label()}}</td>
                <td class="text-right">{{$good->price}}円</td>
                <td class="c-balloon-memo">
                  <button type="button" class="button_open c-button__memo">メモ</button>
                  <!-- 吹き出し -->
                  <div class="c-balloon-memo__box">
                    <div class="c-balloon-memo__text">{{$good->memo}}
                      <div class="button_close c-balloon-memo__close">閉じる</div>
                    </div>
                  </div>
                </td>
                <td><button class="c-button__edit button_select" onclick="openEditModal({{$good->id}})">編集</button></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div><!-- ./l-container__inner -->
    </main><!-- /.l-container__main -->
  </div><!-- /.l-container__wrap -->
  @include('manage.master.components.good_modal', [
    'mode' => 'new',
    'label' => '新規登録',
    'method' => 'POST',
    'action' => route('manage.master.goods.store'),
    'good' => null,
    ]
  )

  <!-- 「編集」をクリックしたら出てくるmodal -->
  @foreach ($goods as $good)
    @include('manage.master.components.good_modal', [
      'mode' => 'edit',
      'label' => '編集',
      'method' => 'PUT',
      'action' => route('manage.master.goods.update', [$good->id]),
      'good' => $good,
      ]
    )
  @endforeach

@endsection
@push("scripts")
  <script src="{{ asset('js/commons/tax.js') }}"></script>
  <!-- モーダル -->
  {{--  <script src="{{ asset('js/modalOption.js') }}"></script>  --}}
  <!-- テーブルヘッダー用ソートのスクリプト -->
  <script src="{{ asset('js/tableHeaderSort.js') }}"></script>
  <!-- 閉じるボタン -->
  <script src="{{ asset('js/close_button_toggle.js') }}"></script>
  <!-- メモボタン/button_openを押したらbutton_closeにis-blockを付与 -->
  <script>
    let createModal;
    let modalAreaOptions;
    let modalCloseOption;

    function openCreateModal() {
      createModal.classList.add('is-active');
    }
    function openEditModal(goodId) {
      document.getElementById(`modalAreaOption_edit_${goodId}`).classList.add('is-active');
    }
    function closeCreateModal() {
      createModal.classList.remove('is-active');
    }
    function closeEditModal(goodId) {
      document.getElementById(`modalAreaOption_edit_${goodId}`).classList.remove('is-active');
    }
    function deleteGood(goodId) {
      document.getElementById(`delete_${goodId}_form`).submit();
    }

    // 税区分と金額（税込）が設定された場合に、税込金額を表示します。
    function dispTotal(goodId = null) {
      goodId = goodId ?? '';
      const totalElem = document.getElementById(`total_${goodId}`);
      const priceElem = document.getElementById(`price_${goodId}`);
      const taxTypeElem = document.getElementById(`tax_type_${goodId}`);

      if(priceElem.value != '' && taxTypeElem.value != '') {
        const price = Number(priceElem.value);
        totalElem.textContent = formatCurrency(price + calcTax(taxTypeElem.value, price), '', '円');
      }
    }


    document.addEventListener('DOMContentLoaded', function() {
      createModal = document.getElementById('modalAreaOption_new_');
      modalAreaOptions = document.querySelectorAll('.modal_area');
      modalCloseOption = document.querySelectorAll('.modal_optionClose');

      const buttonOpenList = document.querySelectorAll('.button_open');
      const buttonCloseList = document.querySelectorAll('.button_close');
      const balloonBoxList = document.querySelectorAll('.c-balloon-memo__box');

      buttonOpenList.forEach(function(buttonOpen, index) {
        buttonOpen.addEventListener('click', function() {
          // 対応する吹き出しの表示
          balloonBoxList[index].classList.add('is-inline-block');
        });
      });

      buttonCloseList.forEach(function(buttonClose, index) {
        buttonClose.addEventListener('click', function() {
          // 吹き出しを閉じる
          balloonBoxList[index].classList.remove('is-inline-block');
        });
      });
    });

    document.querySelectorAll('.l-table-list th .sort-enable').forEach(th => th.onclick = (e) => sortRows(e, '.l-table-list'));
  </script>

@endpush
