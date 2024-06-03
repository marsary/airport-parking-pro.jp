<!-- F-0 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">レジTOP</li>
  </ul>

  @include('include.messages.errors')

  <div class="l-container__inner">
    <div class="l-grid--col2 l-grid--gap2">
      <div>
        <ul class="l-grid--col2 l-grid--gap1 u-pb2 u-mb2 u-border--bottom">
          <li class="c-button__select--h90">
            <a id="to_deals_show" href="#" class="p-index__link">ご予約情報</a>
          </li>
          <li class="c-button__select--h90">
            <a id="to_members_show" href="#" class="p-index__link">顧客情報</a>

          </li>
        </ul>

        <!-- select ボタン -->
        <div class="p-input-user-option__select--input">
          @foreach ($goodCategories as $goodCategory)
            <div class="c-button__select button_select" onclick="openOptionModal({{$goodCategory->id}})">{{$goodCategory->name}}</div>
            @include('include.option.option', [
              'modalId' => $goodCategory->id,
              'goods' => $goodCategory->goods,
              'goodCategory' => $goodCategory,
              ]
            )
          @endforeach

          {{--  <div class="c-button__select button_select">洗車</div>
          <div class="c-button__select button_select">メンテナンス</div>
          <div class="c-button__select button_select">保険</div>
          <div class="c-button__select button_select">回数券</div>
          <div class="c-button__select button_select">物販</div>
          <div class="c-button__select button_select">その他</div>  --}}
        </div>
      </div>

      <!-- rigth -->
      <div class="form-container">
        <form action="" method="POST">
          <div id="optionItem" class="form-item p-register__optionWrap">
            <div class="p-register__optionItem">
              <div class="">駐車料金（税抜）</div>
              <div class="p-register__optionPrice">0,000<span class="u-font-yen">円</span></div>
            </div>

            <!-- ここのJSは</body>直前にあり -->
            <div class="p-register__optionItem">
              <div>オプション1オプション1オプション1オプション1オプション1オプション1</div>
              <div class="p-register__optionItem--right">
                <div>
                  <button type="button" class="button c-button-quantity" data-operation="up">＋</button>
                  <button type="button" class="button c-button-quantity" data-operation="down">－</button>
                </div>
                <div><span class="count">1</span>点</div>
                <div class="p-register__optionPrice optionPrice">4,000<span class="u-font-yen">円</span></div>
              </div>
            </div>
            <div class="p-register__optionItem">
              <div>オプション2</div>
              <div class="p-register__optionItem--right">
                <div>
                  <button type="button" class="button c-button-quantity" data-operation="up">＋</button>
                  <button type="button" class="button c-button-quantity" data-operation="down">－</button>
                </div>
                <div><span class="count">1</span>点</div>
                <div class="p-register__optionPrice optionPrice">1,500<span class="u-font-yen">円</span></div>
              </div>
            </div>
          </div>

          <div class="form-item l-grid--col2 u-border--all u-p1">
            <div>小計 8%対象額（税抜）</div>
            <div id="reduced_subTotal" class="p-register__optionPrice text-right">0<span class="u-font-yen">円</span></div>

            <div>軽減税率</div>
            <div id="reduced_tax" class="p-register__optionPrice text-right">0<span class="u-font-yen">円</span></div>

            <div>小計 10%対象額（税抜）</div>
            <div id="subTotal" class="p-register__optionPrice text-right">0<span class="u-font-yen">円</span></div>

            <div>消費税 10%</div>
            <div id="tax" class="p-register__optionPrice text-right">0<span class="u-font-yen">円</span></div>

            <label>消費税対象外</label>
            <div id="tax_exempt" class="p-register__optionPrice text-right">0<span class="u-font-yen">円</span></div>
          </div>
          <div class="form-item p-register__total">
            <label class="u-font--18">お支払い合計</label>
            <span id="total" class="text-right u-font--46 u-font--medium">0<span class="u-font-yen">円</span></span>
          </div>

          <div class="l-flex--end l-grid--cgap1">
            <p class="u-font--14">これより先、変更はできません。</p>
            <button type="button" id="modal_open" class="c-button__submit">決済画面へ</button>
          </div>

          <!-- 8% -->
          <input type="hidden" name="reduced_subtotal" id="reducedSubTotalInput" value="0" />
          <input type="hidden" name="reduced_tax" id="reducedTaxInput" value="0" />
          <!-- 10% -->
          <input type="hidden" name="subtotal" id="subtotalInput" value="0" />
          <input type="hidden" name="tax" id="taxInput" value="0" />
          <!-- 対象外 -->
          <input type="hidden" name="tax_exempt" id="taxExemptInput" value="0" />
          <!-- 合計 -->
          <input type="hidden" name="total" id="totalInput" value="0" />
          <input type="hidden" id="originalTotalChange" value="0" />
          <input type="hidden" id="originalTotalPay" value="0" />
          <input type="hidden" id="optionInfosSaved" value="0" />
          <input type="hidden" id="categoryPaymentDetailMap" value="">
        </form>
      </div>
    </div>
  </div><!-- /.l-container__inner -->

  <!-- オプションをクリックしたら出てくるmodal -->
  {{--  @include('include.option.option')  --}}


  <!-- 決済画面 -->
  <!--　モーダル -->
  <div id="modalArea" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner">
      <div class="l-modal__head">決済画面</div>
      <div id="modal_close" class="l-modal__close">×</div>

      <form id="payment_submit_form" action="{{route('manage.registers.store', ['deal_id' => $dealId])}}" method="POST">
        @csrf
        <div class="l-modal__content p-register">
          <p class="text-center u-mb1">やまだたろう 様</p>
          <div class="p-register__settlement">

            <!-- 電卓 left side-->
            <div class="p-register__calculator p-register__settlement--left kufi">
              <div class="p-register__calculator--head">
                <!-- +ボタン -->
                <div class="c-button-optionSelect">
                  <input type="radio" id="plus" name="symbol" value="plus" checked />
                  <label for="plus" class="c-button__calculator">+</label>
                </div>
                <!-- -ボタン -->
                <div class="c-button-optionSelect">
                  <input type="radio" id="minus" name="symbol" value="minus" />
                  <label for="minus" class="c-button__calculator">-</label>
                </div>
                <!-- clear -->
                <div data-key="C" class="numpad_key">C</div>
              </div>
              <div class="p-register__calculator--center">
                <div data-key="7" class="numpad_key">7</div>
                <div data-key="8" class="numpad_key">8</div>
                <div data-key="9" class="numpad_key">9</div>
                <div data-key="4" class="numpad_key">4</div>
                <div data-key="5" class="numpad_key">5</div>
                <div data-key="6" class="numpad_key">6</div>
                <div data-key="1" class="numpad_key">1</div>
                <div data-key="2" class="numpad_key">2</div>
                <div data-key="3" class="numpad_key">3</div>
                <div data-key="0" class="numpad_key">0</div>
                <div data-key="00" class="numpad_key">00</div>
                <div data-key="Del" class="l-flex--center numpad_key"><img src="{{ asset('images/icon/deleteButton.svg') }}" width="25" height="18" alt="deleteボタン"></div>
              </div>
              <div id="enterButton" class="p-register__calculator--footer">ENTER</div>
            </div><!-- ./p-register__calculator -->

            <!-- right -->
            <div class=" p-register__settlement--right">

              <!-- クーポンコード キャンペーン 伝票値引き option-->
              <div class="l-grid--col3-auto l-grid--gap1 hidden">
                <label for="coupon" class="c-button__apply--auto c-button__apply--green u-mb1">割引クーポン</label>
                <div class="c-form-select-wrap">
                  <select id="coupon" name="coupon" class="">
                    <option value="0" selected>割引クーポンを選択して下さい</option>
                    @foreach ($coupons as $coupon)
                      <option value="{{ $coupon->id }}">
                        {{$coupon->name }}
                      </option>
                    @endforeach
                    {{--  <option value="1">クーポンコード1</option>
                    <option value="2">クーポンコード2</option>
                    <option value="3">クーポンコード3</option>  --}}
                  </select>
                </div>
                <input type="hidden" id="couponData" value="{{json_encode(getKeyMapCollection($coupons))}}">
                <button type="button" class="apply_button c-button__apply--green --disabled u-mb1" disabled>適用</button>
              </div>
              <div class="p-register__adjustment c-button-optionSelect-light l-grid--col4 l-grid--gap05">
                <div>
                  <input type="checkbox" id="discount" name="discount" value="値引き" class="adjustItem entryType" /><label for="discount" class="">値引き</label>
                </div>
                <div>
                  <input type="checkbox" id="adjustment" name="adjustment" value="調整" class="adjustItem entryType" /><label for="adjustment" class="">調整</label>
                </div>
              </div>
              <!-- 支払方法　チェックボックス -->
              <div class="p-register__paymentMethod">
                <div class="c-button-optionSelect">
                  <input type="checkbox" id="paymentMethod_cash" name="paymentMethod" value="現金" checked class=" entryType" />
                  <label for="paymentMethod_cash" class="u-border--none c-button--light-gray">現金</label>
                </div>
                <div class="c-form-select-wrap">
                  <select name="paymentMethod" id="paymentMethod_credit" class="entryType">
                    <option value="" selected>クレジット</option>
                    @foreach (\Illuminate\Support\Arr::get($paymentMethodCategoryMap, 'credit', []) as $paymentMethod)
                      <option value="{{ $paymentMethod->id }}">
                        {{$paymentMethod->name }}
                      </option>
                    @endforeach
                    {{--  <option value="クレジット">VISA</option>
                    <option value="クレジット">JCB</option>  --}}
                  </select>
                </div>
                <div class="c-form-select-wrap">
                  <select name="paymentMethod" id="paymentMethod_emoney" class="entryType">
                    <option value="" selected>電子マネー</option>
                    @foreach (\Illuminate\Support\Arr::get($paymentMethodCategoryMap, 'electronicMoney', []) as $paymentMethod)
                      <option value="{{ $paymentMethod->id }}">
                        {{$paymentMethod->name }}
                      </option>
                    @endforeach
                    {{--  <option value="電子マネー">楽天Edy</option>
                    <option value="電子マネー">iD</option>  --}}
                  </select>
                </div>
                <div class="c-form-select-wrap">
                  <select name="paymentMethod" id="paymentMethod_qrcode" class="entryType">
                    <option value="" selected>QRコード</option>
                    @foreach (\Illuminate\Support\Arr::get($paymentMethodCategoryMap, 'qrCode', []) as $paymentMethod)
                      <option value="{{ $paymentMethod->id }}">
                        {{$paymentMethod->name }}
                      </option>
                    @endforeach
                    {{--  <option value="QRコード">PayPay</option>
                    <option value="QRコード">LINE Pay</option>  --}}
                  </select>
                </div>
                <div class="c-button-optionSelect">
                  <input type="checkbox" id="paymentMethod_certificate" name="paymentMethod" value="商品券" class="entryType" />
                  <label for="paymentMethod_certificate" class="u-border--none c-button--light-gray">商品券</label>
                </div>
                <div class="c-form-select-wrap">
                  <!-- 旅行支援 -->
                  <select name="paymentMethod" id="paymentMethod_travel" class="entryType">
                    <option value="" selected>旅行支援</option>
                    @foreach (\Illuminate\Support\Arr::get($paymentMethodCategoryMap, 'travelAssistance', []) as $paymentMethod)
                      <option value="{{ $paymentMethod->id }}">
                        {{$paymentMethod->name }}
                      </option>
                    @endforeach
                    {{--  <option value="旅行支援">Go To トラベル</option>
                    <option value="旅行支援">Go To Eat</option>  --}}
                  </select>
                </div>
                <div class="c-form-select-wrap">
                  <!-- バウチャー -->
                  <select name="paymentMethod" id="paymentMethod_voucher" class="entryType">
                    <option value="" selected>バウチャー</option>
                    @foreach (\Illuminate\Support\Arr::get($paymentMethodCategoryMap, 'voucher', []) as $paymentMethod)
                      <option value="{{ $paymentMethod->id }}">
                        {{$paymentMethod->name }}
                      </option>
                    @endforeach
                    {{--  <option value="バウチャー">飲食券</option>  --}}
                  </select>
                </div>
                <div class="c-form-select-wrap">
                  <!-- バウチャー -->
                  <select name="paymentMethod" id="paymentMethod_other" class="entryType">
                    <option value="" selected>その他</option>
                    @foreach (\Illuminate\Support\Arr::get($paymentMethodCategoryMap, 'others', []) as $paymentMethod)
                      <option value="{{ $paymentMethod->id }}">
                        {{$paymentMethod->name }}
                      </option>
                    @endforeach
                    {{--  <option value="その他">その他1</option>
                    <option value="その他">その他2</option>  --}}
                  </select>
                </div>
              </div>

              <!-- 金額自動出力 -->
              <div class="p-register-checkout">
                <div class="p-register-checkout__subtotal">
                  <div id="register_subtotals" class="p-register-checkout__head">
                    <div class="p-register-checkout__item">
                      <div>小計</div>
                      <div class="p-register-checkout__price">0,000<span class="u-font-yen">円</span></div>
                    </div>
                    <div class="p-register-checkout__item item-container">
                      <div class="c-button__remove"><img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">値引き</div>
                      <div class="p-register-checkout__price">-0,000<span class="u-font-yen">円</span></div>
                    </div>
                    <div class="p-register-checkout__item item-container">
                      <div>消費税</div>
                      <div class="p-register-checkout__price">0,000<span class="u-font-yen">円</span></div>
                    </div>
                  </div>
                  <div class="p-register-checkout__total-payment">
                    お支払い合計（税込）
                    <div id="register_total_amount" class="p-register-checkout__price--big">10,0000<span class="u-font-yen">円</span></div>
                  </div>
                </div>
                <div class="p-register-checkout__amount-received">
                  <div class="u-pl1">お預かり</div>
                  <div id="register_received_items" class="p-register-checkout__head">
                    <div class="item-container p-register-checkout__item">
                      <div class="c-button__remove ">
                        <img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">現金
                      </div>
                      <div class="p-register-checkout__price">0,000<span class="u-font-yen">円</span></div>
                    </div>

                    <!-- <div>JCB</div>
                    <div class="p-register-checkout__price">0,000<span class="u-font-yen">円</span></div> -->
                  </div>
                  <div class="p-register-checkout__total-change">
                    お釣り
                    <div id="register_total_change" class="p-register-checkout__price--big">10,0000<span class="u-font-yen">円</span></div>
                  </div>
                </div>
              </div>
            </div><!-- ./p-register__settlement--right -->
          </div>
        </div>

        <div class="l-modal__footer p-register__settlement--foot">
          <!-- disablesの時のクラス付与[--disabled2] -->
          <button id="paymentSubmitButton" type="submit" class="is-block c-button__submit --disabled2 u-horizontal-auto" disabled>決済する</button>
        </div>
      </form>

    </div>
  </div><!-- /.l-modal -->
</main>


@endsection
@push("scripts")
<script src="{{ asset('js/commons/tax.js') }}"></script>
<!-- モーダル -->
{{--  <script src="{{ asset('js/modalOption.js') }}"></script>  --}}
<!-- 決済画面をモーダルで表示するスクリプト-->
<script src="{{ asset('js/removeButton.js') }}"></script>
{{--  <script src="{{ asset('js/modal.js') }}"></script>  --}}
<script src="{{ asset('js/pages/manage/register.js') }}"></script>

<script>
  const goodsMap = @js($goodsMap);
  const dealId = @js($dealId);
  let goodIds = [];
  let deal = null;
  let dealGoods = {};
  let optionItemSection = null;

  let reducedSubTotalDisp;
  let reducedTaxDisp;
  let subTotalDisp;
  let taxDisp;
  let taxExemptDisp;
  let totalDisp;
  let reducedSubTotalInput;
  let reducedTaxInput;
  let subTotalInput;
  let taxInput;
  let taxExemptInput;
  let totalInput;

  function openOptionModal(modalId) {
    const modalAreaOption = document.getElementById('modalAreaOption' + modalId);
    modalAreaOption.classList.add('is-active');
  }
  function closeOptionModal(modalId) {
    const modalAreaOption = document.getElementById('modalAreaOption' + modalId);
    modalAreaOption.classList.remove('is-active');
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
      const goodId = parseInt(checkbox.value);
      if(checkbox.checked) {
        addingIds.push(goodId);
      } else {
        removingIds.push(goodId);
      }
    });

    goodIds = addRemoveList(goodIds, addingIds, removingIds);
    goodIds.forEach(goodId => {
      if(dealGoods[goodId] == undefined) {
        const good = goodsMap[goodId]
        dealGoods[goodId] = {
          good_id:goodId,
          num:1,
          total_price:good.price,
          total_tax: calcTax(good.tax_type, good.price)
        }
      }
    })
    Object.keys(dealGoods).forEach((goodId) => {
      const dealGood = dealGoods[goodId]
      if(!goodIds.includes(parseInt(dealGood.good_id))) {
        delete dealGoods[dealGood.good_id]
      }
    })
    setTotalPrices()
    updateOptionList()
  }

  function updateModalOptions() {
    document.querySelectorAll('input[name*=modal_good_ids]').forEach(elem => {
      if(goodIds.includes(parseInt(elem.value))) {
        elem.checked = true;
      } else {
        elem.checked = false;
      }
    })
  }

  function updateOptionList() {
    removeAllChildNodes(optionItemSection)
    const el = document.createElement('div');
    el.classList.add("p-register__optionItem")
    el.innerHTML = `
      <div class="">駐車料金（税抜）</div>
      <div class="p-register__optionPrice">
        ${formatCurrency(deal.price)}
        <span class="u-font-yen">円</span>
      </div>
    `
    optionItemSection.appendChild(el)

    Object.keys(dealGoods).forEach((goodId) => {
      const dealGood = dealGoods[goodId]
      const good = goodsMap[goodId]
      const row = makeOptionRow(good.name, dealGood.num, dealGood.total_price, dealGood.good_id)
      optionItemSection.appendChild(row)
    })
  }

  function makeOptionRow(name, count, price, goodId) {
    const el = document.createElement('div');
    el.classList.add("p-register__optionItem")

    el.innerHTML = `
    <div>${name}</div>
    <div class="p-register__optionItem--right">
      <div>
        <button type="button" class="button c-button-quantity" onclick="updateQuantity(this, ${goodId}, '+')" data-operation="up">＋</button>
        <button type="button" class="button c-button-quantity" onclick="updateQuantity(this, ${goodId}, '-')" data-operation="down">－</button>
      </div>
      <div><span class="count">${count}</span>点</div>
      <div class="p-register__optionPrice optionPrice">
        ${formatCurrency(price)}
        <span class="u-font-yen">円</span>
      </div>
    </div>
    `;

    return el;
  }

  function updateQuantity(button, goodId, mode) {
    const dealGood = dealGoods[goodId]
    const good = goodsMap[goodId]
    if(dealGood == undefined) {
      return;
    }
    switch(mode) {
      case '+':
      dealGood.num += 1
      break;
      case '-':
      dealGood.num -= 1
      break;
    }
    dealGood.total_price = good.price * dealGood.num
    dealGood.total_tax = calcTax(good.tax_type, dealGood.total_price)
    setTotalPrices()
    handleClickQuantityButton(button)
    if(dealGood.num == 0) {
      delete dealGoods[goodId]
      updateOptionList()
    }
  }


  function setTotalPrices() {
    let tenPercentAmountExcludingTax = deal.price;
    let tenPercentTax = deal.tax;
    let totalAmount = deal.price + deal.tax;
    let eightPercentAmountExcludingTax = 0;
    let eightPercentTax = 0;
    let NoTaxAmount = 0;

    Object.keys(dealGoods).forEach((goodId) => {
      const dealGood = dealGoods[goodId]
      const good = goodsMap[goodId]
      switch (good.tax_type) {
        case TaxTypes.EIGHT_PERCENT:
          eightPercentAmountExcludingTax += dealGood.total_price
          eightPercentTax += dealGood.total_tax
          break;
        case TaxTypes.TEN_PERCENT:
          tenPercentAmountExcludingTax += dealGood.total_price
          tenPercentTax += dealGood.total_tax
          break;
        case TaxTypes.EXEMPT:
          NoTaxAmount += dealGood.total_price
          break;
        default:
          throw new Error(good.name + '取引商品に税種別がありません！');
          break;
      }
      totalAmount += dealGood.total_price + dealGood.total_tax
    })

    // 12	対象予約の商品のうち、税区分が8％の商品の総額を表示する
    reducedSubTotalDisp.textContent = formatCurrency(eightPercentAmountExcludingTax,null,' 円');
    reducedSubTotalInput.value = eightPercentAmountExcludingTax;
    // 13	上記商品の消費税を表示する
    reducedTaxDisp.textContent = formatCurrency(eightPercentTax,null,' 円');
    reducedTaxInput.value = eightPercentTax;
    // 14	対象予約の商品のうち、税区分が10％の商品の総額を表示する
    subTotalDisp.textContent = formatCurrency(tenPercentAmountExcludingTax,null,' 円');
    subTotalInput.value = tenPercentAmountExcludingTax;
    // 15	上記商品の消費税を表示する
    taxDisp.textContent = formatCurrency(tenPercentTax,null,' 円');
    taxInput.value = tenPercentTax;
    // 16	対象予約の商品のうち、税区分が税無しの商品の総額を表示する
    taxExemptDisp.textContent = formatCurrency(NoTaxAmount,null,' 円');
    taxExemptInput.value = NoTaxAmount;
    // 17	対象予約の総額を表示する
    totalDisp.textContent = formatCurrency(totalAmount,null,' 円');
    totalInput.value = totalAmount;
  }

  window.addEventListener('DOMContentLoaded', function() {
    const BASE_PATH = document.getElementById('base_path').value;
    optionItemSection = document.getElementById('optionItem');
    reducedSubTotalDisp = document.getElementById('reduced_subTotal');
    reducedTaxDisp = document.getElementById('reduced_tax');
    subTotalDisp = document.getElementById('subTotal');
    taxDisp = document.getElementById('tax');
    taxExemptDisp = document.getElementById('tax_exempt');
    totalDisp = document.getElementById('total');
    reducedSubTotalInput = document.getElementById('reducedSubTotalInput');
    reducedTaxInput = document.getElementById('reducedTaxInput');
    subTotalInput = document.getElementById('subtotalInput');
    taxInput = document.getElementById('taxInput');
    taxExemptInput = document.getElementById('taxExemptInput');
    totalInput = document.getElementById('totalInput');
    toDealsShowLink = document.getElementById('to_deals_show');
    toMembersShowLink = document.getElementById('to_members_show');
    const optionInfosSavedInput = document.getElementById('optionInfosSaved');
    const originalTotalChangeInput = document.getElementById('originalTotalChange');
    const originalTotalPayInput = document.getElementById('originalTotalPay');
    const categoryPaymentDetailMapInput = document.getElementById('categoryPaymentDetailMap');

    const modalArea = document.getElementById('modalArea');
    const modalOpen = document.getElementById('modal_open');
    const modalClose = document.getElementById('modal_close');

    modalOpen.addEventListener('click', async function() {
      modalArea.classList.add('is-active');
      if(dealId == '') {
        return;
      }

      optionInfosSavedInput.value = 0;
      // オプションデータをAPIに送信
      const json = await apiRequest.put(BASE_PATH + "/manage/deals/" + dealId + "/update_goods", {
        'dealGoods': dealGoods,
        'total_price': parseInt(totalInput.value),
        'total_tax': parseInt(reducedTaxInput.value) + parseInt(taxInput.value),
      });

      console.log(json); // `data.json()` の呼び出しで解釈された JSON データ
      if(json.success){
        optionInfosSavedInput.value = 1;
      } else {
        alert(json.message);
      }

    });
    modalClose.addEventListener('click', function() {
      modalArea.classList.remove('is-active');
    });

    // オプション情報表示
    async function dispOptionTable() {
      if(dealId == '') {
        return;
      }

      // 取引IDをAPIに送信
      const json = await apiRequest.get(BASE_PATH + "/manage/registers/" + dealId)

      console.log(json); // `data.json()` の呼び出しで解釈された JSON データ
      if(json.success){
        optionInfosSavedInput.value = 0;
        originalTotalChangeInput.value = json.data.payment?.cash_change;
        originalTotalPayInput.value = json.data.payment?.total_pay;
        categoryPaymentDetailMapInput.value = JSON.stringify(json.data.categoryPaymentDetailMap);
        console.log(json.data);

        deal = json.data.deal
        if(isObject(json.data.dealGoods)) {
          dealGoods = json.data.dealGoods
        }
        goodIds = Object.keys(dealGoods).map(goodId => parseInt(goodId))

        toDealsShowLink.href = BASE_PATH + "/manage/deals/" + deal.id
        toMembersShowLink.href = BASE_PATH + "/manage/members/" + deal.member_id

        // dealGoods
        // 6	取引商品データの名前を表示する
        // 9	上記商品の数量を表示する
        // 10	上記商品の総額を表示する
        updateOptionList()
        updateModalOptions()
        // totalPrices
        // 12	対象予約の商品のうち、税区分が8％の商品の総額を表示する
        reducedSubTotalDisp.textContent = formatCurrency(json.data.totalPrices.eightPercentAmountExcludingTax,null,' 円');
        reducedSubTotalInput.value = json.data.totalPrices.eightPercentAmountExcludingTax;
        // 13	上記商品の消費税を表示する
        reducedTaxDisp.textContent = formatCurrency(json.data.totalPrices.eightPercentTax,null,' 円');
        reducedTaxInput.value = json.data.totalPrices.eightPercentTax;
        // 14	対象予約の商品のうち、税区分が10％の商品の総額を表示する
        subTotalDisp.textContent = formatCurrency(json.data.totalPrices.tenPercentAmountExcludingTax,null,' 円');
        subTotalInput.value = json.data.totalPrices.tenPercentAmountExcludingTax;
        // 15	上記商品の消費税を表示する
        taxDisp.textContent = formatCurrency(json.data.totalPrices.tenPercentTax,null,' 円');
        taxInput.value = json.data.totalPrices.tenPercentTax;
        // 16	対象予約の商品のうち、税区分が税無しの商品の総額を表示する
        taxExemptDisp.textContent = formatCurrency(json.data.totalPrices.NoTaxAmount,null,' 円');
        taxExemptInput.value = json.data.totalPrices.NoTaxAmount;
        // 17	対象予約の総額を表示する
        totalDisp.textContent = formatCurrency(json.data.totalPrices.totalAmount,null,' 円');
        totalInput.value = json.data.totalPrices.totalAmount;
      }
    }

    // 初期画面で取引を取得
    dispOptionTable()
  })

  // 数量変更ボタンのイベントリスナーを設定
  {{--  document.querySelectorAll('.c-button-quantity').forEach(function(button) {
    button.addEventListener('click', function() {
      handleClickQuantityButton(button)
    });
  });  --}}

  function handleClickQuantityButton(button) {
      // オプションアイテムの要素を取得
      let optionItem = button.closest('.p-register__optionItem');
      // 数量と価格の要素を取得
      let countElement = optionItem.querySelector('.count');
      let priceElement = optionItem.querySelector('.optionPrice');
      // 数量と価格の初期値を取得
      let count = parseInt(countElement.textContent);
      let pricePerItem = parseInt(priceElement.textContent.replace(',', '').replace('円', '')) / count;
      // ボタンの操作に基づいて数量を増減
      if (button.getAttribute('data-operation') === 'up') {
        count++;
      } else if (button.getAttribute('data-operation') === 'down' && count > 1) {
        count--;
      }
      // 数量と価格を更新
      countElement.textContent = count;
      priceElement.textContent = (pricePerItem * count).toLocaleString() + '円';
  }
</script>
<script>
  // クーポンの選択要素を取得
  let couponSelect = document.getElementById('coupon');
  // 適用ボタンの要素を取得
  let applyButton = document.querySelector('.apply_button');

  // クーポンの選択肢が変更されたときのイベントリスナーを設定
  couponSelect.addEventListener('change', function() {
    // 選択肢が選ばれている場合、適用ボタンのdisabled属性と--disabledクラスを削除
    if (couponSelect.value !== '0') {
      applyButton.disabled = false;
      applyButton.classList.remove('--disabled');
    }
    // 選択肢が選ばれていない場合、適用ボタンにdisabled属性と--disabledクラスを追加
    else {
      applyButton.disabled = true;
      applyButton.classList.add('--disabled');
    }
  });
</script>
@endpush
@push('css')
<style>
</style>
@endpush
