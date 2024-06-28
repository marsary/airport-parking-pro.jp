<!-- B-1-3 オプション選択 -->
@extends('layouts.form.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "option"])
<div class="p-user-input__inner--sm">
  <form action="{{route('form.reserves.option_select')}}" method="POST">
    @csrf
    <div class="p-user-input-auto-output__wrap u-mb3 u-pb3 u-border--bottom-green">
      <!-- select ボタン -->
      <div class="p-input-user-option__select--input">
        {{--  <div class="c-button__select button_select">洗車</div>  --}}
        <!-- オプションをクリックしたら出てくるmodal -->
        @foreach ($goodCategories as $goodCategory)
          <div class="c-button__select button_select" onclick="openOptionModal({{$goodCategory->id}})">{{$goodCategory->name}}</div>
          @include('include.option.option', [
            'modalId' => $goodCategory->id,
            'goods' => $goodCategory->goods,
            'goodCategory' => $goodCategory,
            ]
          )
        @endforeach

        {{--  <div class="c-button__select button_select">メンテナンス</div>
        <div class="c-button__select button_select">保険</div>
        <div class="c-button__select button_select">回数券</div>
        <div class="c-button__select button_select">物販</div>
        <div class="c-button__select button_select">その他</div>  --}}
      </div>

      <!-- オプション選択項目自動出力 -->
      <div class="p-user-input-auto-output__right u-pl1" id="checked-option-list">
        @if ($reserve->good_ids)
          @foreach ($reserve->good_ids as $good_id)
            @php
              $good = $goodsMap[$good_id];
            @endphp
            <div class="c-button__remove item-container remove_good">
              <img src="{{ asset('images/icon/removeButton.svg') }}" value="{{$good->id}}" width="16" height="16" class="button_remove">
              {{$good->name}} ¥{{number_format($good->price)}}
            </div>
          @endforeach
        @endif
        {{--  <div class="c-button__remove item-container"><img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">手洗いWAX洗車 ¥2,500</div>
        <div class="c-button__remove item-container"><img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">iPhone充電ケーブル ¥1,200</div>  --}}
      </div>
      <input type="hidden" id="good_ids" name="good_ids" value="{{old('good_ids', implode(',', $reserve->good_ids))}}">
    </div>

    <div class="p-user-input-auto-output__wrap u-mb4">
      <div class="l-flex--start l-flex--item-end l-grid--gap05 hidden">
        <div>
          <label for="coupon_code">割引クーポン</label>
          <!-- クーポン -->
          <input type="text" id="coupon_code" name="coupon_code" class="u-mb0" value="{{old('coupon_code', $reserve->coupon_code)}}">
        </div>
        <button type="button" class="c-button__apply--green" id="coupon_code_btn">適用</button>
      </div>
      <!-- オプション選択項目自動出力 -->
      <div class="p-user-input-auto-output__right u-pl1" id="selected-coupon-info">
        @if ($reserve->coupon_code)
        @php
          $coupon = \Illuminate\Support\Arr::first($couponsMap, function($coupon) use($reserve) {
           return $coupon->code == $reserve->coupon_code;
          });
        @endphp
          <div class="c-button__remove remove_coupon item-container">
            <img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="coupon_remove">
            {{$coupon->name}}
          </div>
        @endif
      </div>
      <input type="hidden" id="coupon_ids" name="coupon_ids" value="{{old('coupon_ids', implode(',', $reserve->coupon_ids))}}">
    </div>

    <!-- pager -->
    <div class="c-button-group__form u-mt3">
    <button type="button" id="returnButton" onclick="location.href='{{route('form.reserves.entry_car')}}';" class="c-button__pagination--return">前のページに戻る</button>
      <button type="submit" class="c-button__pagination--next">内容確認へ進む</button>
    </div>
  </form>
</div>



@endsection
@push("scripts")
{{--  <script src="../js/modalOption.js"></script>  --}}
{{--  <script src="{{ asset('js/removeButton.js') }}"></script>  --}}
<script>
  const goodsMap = @js($goodsMap);
  let goodIds = @js($reserve->good_ids);
  let couponIds = @js($reserve->coupon_ids);
  const couponsMap = @js($couponsMap);
  let checkedOptionList = null;
  let couponInfoElem = null;
  let goodIdsElem = null;
  let couponIdsElem = null;
  let couponCodeElem = null;
  function openOptionModal(modalId) {
    const modalAreaOption = document.getElementById('modalAreaOption' + modalId);
    modalAreaOption.classList.add('is-active');
  }
  function closeOptionModal(modalId) {
    const modalAreaOption = document.getElementById('modalAreaOption' + modalId);
    modalAreaOption.classList.remove('is-active');
  }


  function updateOptionList()
  {
    removeAllChildNodes(checkedOptionList)
    goodIds.forEach((goodId) => {
      const good = goodsMap[goodId];
      const div = document.createElement('div')
      const img = document.createElement('img')
      const span = document.createElement('span')
      div.classList.add("button__remove","item-container", "remove_good")
      // div.value = good.id
      img.src = "{{ asset('images/icon/removeButton.svg') }}"
      img.width = 16
      img.height = 16
      img.classList.add("button_remove")
      img.value = goodId;
      img.addEventListener('click', function() {
        removeOption(img);
      });

      span.textContent = good?.name + formatCurrency(good?.price, ' ¥');
      div.appendChild(img)
      div.appendChild(span)
      checkedOptionList.appendChild(div)
    })

    goodIdsElem.value = goodIds.join(',')
  }

  function updateCouponList() {
    removeAllChildNodes(couponInfoElem)

    couponIds.forEach((couponId) => {
      const coupon = couponsMap[couponId];
      const div = document.createElement('div')
      const img = document.createElement('img')
      const span = document.createElement('span')
      div.classList.add("c-button__remove", "remove_coupon","item-container")
      img.src = "{{ asset('images/icon/removeButton.svg') }}"
      img.width = 16
      img.height = 16
      img.classList.add("coupon_remove")
      img.value = couponId
      img.addEventListener('click', function() {
        removeCoupon(img);
      });
      span.textContent = coupon.name;
      div.appendChild(img)
      div.appendChild(span)
      couponInfoElem.appendChild(div)
    });
    couponIdsElem.value = couponIds.join(',')

  }

  function addRemoveList(list, addingList, removingList = [])
  {
    list = Array.from(new Set([...list, ...addingList]));
    return list.filter(x => !removingList.includes(x));
  }

  function addOptions(modalId) {
    const modalAreaOption = document.getElementById('modalAreaOption' + modalId);
    const checkBoxList = modalAreaOption.querySelectorAll('input[type="checkbox"]');

    let addingIds = [];
    let removingIds = [];
    // オプション選択項目を更新する。
    checkBoxList.forEach((checkbox) => {
      const goodId = checkbox.value;
      if(checkbox.checked) {
        addingIds.push(goodId);
      } else {
        removingIds.push(goodId);
      }
    });

    goodIds = addRemoveList(goodIds, addingIds, removingIds);
    updateOptionList()
  }

  function addCoupon() {
    const couponCode = couponCodeElem.value
    if(couponCode == '') return;

    const couponId = Object.keys(couponsMap).find((couponId) => couponsMap[couponId]?.code == couponCode);

    if(couponId) {
      couponIds = addRemoveList(couponIds, [couponId]);
      updateCouponList()

    } else {
      alert("このクーポンコードは登録されていません！");
      couponCodeElem.value = ''
    }
  }

  function removeOption(btnElem) {
    const removingId = btnElem.value
    goodIds = addRemoveList(goodIds, [], [removingId]);
    updateOptionList()

    const parent = btnElem.closest('.item-container');
    if (parent) {
      parent.remove();
    }
    document.getElementById('modal_good_ids_' + removingId).checked = false;
  }

  function removeCoupon(btnElem) {
    const removingId = btnElem.value
    couponIds = addRemoveList(couponIds, [], [removingId]);
    updateCouponList()

    couponCodeElem.value = '';
    const parent = btnElem.closest('.item-container');
    if (parent) {
      parent.remove();
    }
  }

  window.addEventListener('DOMContentLoaded', function() {
    checkedOptionList = document.getElementById('checked-option-list');
    goodIdsElem = document.getElementById('good_ids');
    couponIdsElem = document.getElementById('coupon_ids');
    couponCodeElem = document.getElementById('coupon_code');
    const couponCodeBtnElem = document.getElementById('coupon_code_btn');
    couponInfoElem = document.getElementById('selected-coupon-info');
    const removeCouponBtnElems = Array.from(document.getElementsByClassName('coupon_remove'));
    const removeGoodBtnElems = Array.from(document.getElementsByClassName('button_remove'));

    couponCodeBtnElem.addEventListener('click', function() {
      addCoupon();
    });

    removeCouponBtnElems.forEach((btnElem) => btnElem.addEventListener('click', function() {
      removeCoupon(btnElem);
    }))

    removeGoodBtnElems.forEach((btnElem) => btnElem.addEventListener('click', function() {
      removeOption(btnElem);
    }))

    // 初期表示
    updateOptionList()
    updateCouponList()
  })
</script>
@endpush
@push('css')
<style>
</style>
@endpush
