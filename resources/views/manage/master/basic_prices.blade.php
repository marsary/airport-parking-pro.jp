@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">設定</li>
        <li class="l-breadcrumb__list">基本料金設定</li>
        </li>
      </ul>

      @include('include.messages.errors')

      <div class="l-container__inner">
        <div class="u-mb1">
          <h2 class="c-title__lv2 l-flex--sb">登録済み 適用料金表一覧<span class="close_button c-button__close">閉じる</span></h2>
          <!-- 検索フォーム -->
          <div class="is-active u-mb2">
            <table class="l-table-list">
              <thead>
                <tr class="l-table-list__head l-table-list--scroll__head">
                  <th class="u-w240"><div class="c-button-sort">適用期間</div></th>
                  <th>メモ</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody class="l-table-list__body">
                @foreach ($prices as $price)
                  <tr>
                    <td>
                      {{$price->start_date->format("Y/m/d")}} ～<br />{{$price->end_date->format("Y/m/d")}}
                      {{--  2021/01/01～2021/12/31  --}}
                    </td>
                    <td>{{$price->memo}}</td>
                    <td>
                      <button type="button" class="c-button__edit button_select" onclick="openEditModal({{$price->id}})">編集</button>
                    </td>
                  </tr>
                @endforeach
                {{--  <tr>
                  <td>2021/01/01～2021/12/31</td>
                  <td>メモメモメモ</td>
                  <td>
                    <button type="button" class="c-button__edit button_select">編集</button>
                  </td>
                </tr>
                <tr>
                  <td>2021/01/01～2021/12/31</td>
                  <td>メモメモメモ</td>
                  <td>
                    <button type="button" class="c-button__edit button_select">編集</button>
                  </td>
                </tr>
                <tr>
                  <td>2021/01/01～2021/12/31</td>
                  <td>メモメモメモ</td>
                  <td>
                    <button type="button" class="c-button__edit button_select">編集</button>
                  </td>
                </tr>  --}}
              </tbody>
            </table>
          </div>
        </div>

        <button type="button" class="button_select c-button__register u-mb4 u-horizontal-auto" onclick="openCreateModal()">新規登録</button>

        <form action="{{route('manage.master.prices.carsize_rate')}}" method="POST" class="l-grid__right-submitButton u-mb4">
          @csrf
          <!-- 入力フォーム -->
          <div class="c-form__input">
            <!-- 下段 -->
            <div class="l-grid__basicPricing--bottom">
              <h2 class="c-title__lv2 l-flex--sb u-mb05">車両サイズによる割引・割増設定<span class="close_button c-button__close">閉じる</span></h2>
              <p class="note text-right u-mb1 u-font--sm">※ダイナミックプライシング料金・代理店料金にも適用されます。</p>
              <div class="l-grid--col2 is-active l-grid__basicPricing--bottom__item">
                <div>
                  <!-- 二輪 -->
                  <label class="l-flex--start l-grid--center l-grid--gap1 u-mb2">二輪
                    <div class="l-flex--start l-grid--gap05">
                      ×<input type="text" name="moter_cycle" class="u-mb0" value="{{old('moter_cycle', \Illuminate\Support\Arr::get($carSizeMap, 'moter_cycle') )}}">倍
                    </div>
                  </label>
                  <!-- 大型 -->
                  <label class="l-flex--start l-grid--center l-grid--gap1">大型
                    <div class="l-flex--start l-grid--gap05">
                      ×<input type="text" name="large" class="u-mb0" value="{{old('large', \Illuminate\Support\Arr::get($carSizeMap, 'large') )}}">倍
                    </div>
                </div>
                <div>
                  <!-- マイクロバス -->
                  <label class="l-flex--end l-grid--center l-grid--gap1 u-mb2">マイクロバス
                    <div class="l-flex--start l-grid--gap05">
                      ×<input type="text" id="micro_bus" name="micro_bus" class="u-mb0" value="{{old('micro_bus', \Illuminate\Support\Arr::get($carSizeMap, 'micro_bus') )}}">倍
                    </div>
                  </label>
                  <!-- キャンピングカー -->
                  <label class="l-flex--end l-grid--center l-grid--gap1">キャンピングカー
                    <div class="l-flex--start l-grid--gap05">
                      ×<input type="text" id="camping_car" name="camping_car" class="u-mb0" value="{{old('camping_car', \Illuminate\Support\Arr::get($carSizeMap, 'camping_car') )}}">倍
                    </div>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <!-- button -->
          <div class="l-grid__right-submitButton--button c-button__csv--upload">
            <button type="submit" class="c-button__register u-mt0">登録</button>
          </div>
        </form>

      </div>
    </main><!-- /.l-container__main -->
  </div><!-- /.l-wrap -->


  <!-- modal -->
  @include('manage.master.components.price_modal', [
    'mode' => 'new',
    'label' => '新規登録',
    'method' => 'POST',
    'action' => route('manage.master.prices.store'),
    'price' => null,
    ]
  )

  <!-- 「編集」をクリックしたら出てくるmodal -->
  @foreach ($prices as $price)
    @include('manage.master.components.price_modal', [
      'mode' => 'edit',
      'label' => '編集',
      'method' => 'PUT',
      'action' => route('manage.master.prices.update', [$price->id]),
      'price' => $price,
      ]
    )
  @endforeach

@endsection
@push("scripts")
<style>
  .carsize-price-rate-inputs{
    column-gap: 20%;
  }
</style>
@endpush
@push("scripts")
  <!-- 閉じる・開く切替 -->
  <script src="{{ asset('js/close_button_toggle.js') }}"></script>
  <!-- モーダル -->
  {{--  <script src="{{ asset('js/modalOption.js') }}"></script>  --}}

  <script>
    let createModal;
    let modalAreaOptions;
    let modalCloseOption;

    function openCreateModal() {
      createModal.classList.add('is-active');
    }
    function openEditModal(priceId) {
      document.getElementById(`modalAreaOption_edit_${priceId}`).classList.add('is-active');
    }
    function closeCreateModal() {
      createModal.classList.remove('is-active');
    }
    function closeEditModal(priceId) {
      document.getElementById(`modalAreaOption_edit_${priceId}`).classList.remove('is-active');
    }
    function deletePrice(priceId) {
      document.getElementById(`delete_${priceId}_form`).submit();
    }

    window.addEventListener('DOMContentLoaded', function() {
      createModal = document.getElementById('modalAreaOption_new_');
      modalAreaOptions = document.querySelectorAll('.modal_area');
      modalCloseOption = document.querySelectorAll('.modal_optionClose');
    })

  </script>
@endpush
