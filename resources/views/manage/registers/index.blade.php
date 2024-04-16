<!-- F-0 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">レジTOP</li>
  </ul>

  <div class="l-container__inner">
    <div class="l-grid--col2 l-grid--gap2">
      <div>
        <ul class="l-grid--col2 l-grid--gap1 u-pb2 u-mb2 u-border--bottom">
          <li class="c-button__select--h90">ご予約情報</li>
          <li class="c-button__select--h90">顧客情報</li>
        </ul>

        <!-- select ボタン -->
        <div class="p-input-user-option__select--input">
          <div class="c-button__select button_select">洗車</div>
          <div class="c-button__select button_select">メンテナンス</div>
          <div class="c-button__select button_select">保険</div>
          <div class="c-button__select button_select">回数券</div>
          <div class="c-button__select button_select">物販</div>
          <div class="c-button__select button_select">その他</div>
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
        </form>
      </div>
    </div>
  </div><!-- /.l-container__inner -->

  <!-- オプションをクリックしたら出てくるmodal -->
  @include('include.option.option')


  <!-- 決済画面 -->
  <!--　モーダル -->
  <div id="modalArea" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner">
      <div class="l-modal__head">決済画面</div>
      <div id="modal_close" class="l-modal__close">×</div>

      <form action="" method="POST">
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
                <div>C</div>
              </div>
              <div class="p-register__calculator--center">
                <div>7</div>
                <div>8</div>
                <div>9</div>
                <div>4</div>
                <div>5</div>
                <div>6</div>
                <div>1</div>
                <div>2</div>
                <div>3</div>
                <div>0</div>
                <div>00</div>
                <div class="l-flex--center"><img src="{{ asset('images/icon/deleteButton.svg') }}" width="25" height="18" alt="deleteボタン"></div>
              </div>
              <div class="p-register__calculator--footer">ENTER</div>
            </div><!-- ./p-register__calculator -->

            <!-- right -->
            <div class=" p-register__settlement--right">

              <!-- クーポンコード キャンペーン 伝票値引き option-->
              <div class="l-grid--col3-auto l-grid--gap1">
                <label for="coupon" class="c-button__apply--auto c-button__apply--green u-mb1">割引クーポン</label>
                <div class="c-form-select-wrap">
                  <select id="coupon" name="coupon" class="">
                    <option value="0" selected>割引クーポンを選択して下さい</option>
                    <option value="1">クーポンコード1</option>
                    <option value="2">クーポンコード2</option>
                    <option value="3">クーポンコード3</option>
                  </select>
                </div>
                <button type="button" class="apply_button c-button__apply--green --disabled u-mb1" disabled>適用</button>
              </div>
              <div class="p-register__adjustment c-button-optionSelect-light l-grid--col4 l-grid--gap05">
                <div>
                  <input type="checkbox" id="campaign" name="campaign" value="値引き" /><label for="campaign" class="">値引き</label>
                </div>
                <div>
                  <input type="checkbox" id="discount" name="discount" value="調整" /><label for="discount" class="">調整</label>
                </div>
              </div>
              <!-- 支払方法　チェックボックス -->
              <div class="p-register__paymentMethod">
                <div class="c-button-optionSelect">
                  <input type="checkbox" id="paymentMethod_cash" name="paymentMethod" value="現金" checked />
                  <label for="paymentMethod_cash" class="u-border--none c-button--light-gray">現金</label>
                </div>
                <div class="c-form-select-wrap">
                  <select name="paymentMethod" id="paymentMethod_credit" class="">
                    <option value="クレジット" selected>クレジット</option>
                    <option value="クレジット">VISA</option>
                    <option value="クレジット">JCB</option>
                  </select>
                </div>
                <div class="c-form-select-wrap">
                  <select name="paymentMethod" id="paymentMethod_emoney" class="">
                    <option value="電子マネー" selected>電子マネー</option>
                    <option value="電子マネー">楽天Edy</option>
                    <option value="電子マネー">iD</option>
                  </select>
                </div>
                <div class="c-form-select-wrap">
                  <select name="paymentMethod" id="paymentMethod_qrcode" class="">
                    <option value="QRコード" selected>QRコード</option>
                    <option value="QRコード">PayPay</option>
                    <option value="QRコード">LINE Pay</option>
                  </select>
                </div>
                <div class="c-button-optionSelect">
                  <input type="checkbox" id="paymentMethod_certificate " name="paymentMethod" value="商品券" />
                  <label for="paymentMethod_certificate " class="u-border--none c-button--light-gray">商品券</label>
                </div>
                <div class="c-form-select-wrap">
                  <!-- 旅行支援 -->
                  <select name="paymentMethod" id="paymentMethod_travel" class="">
                    <option value="旅行支援" selected>旅行支援</option>
                    <option value="旅行支援">Go To トラベル</option>
                    <option value="旅行支援">Go To Eat</option>
                  </select>
                </div>
                <div class="c-form-select-wrap">
                  <!-- バウチャー -->
                  <select name="paymentMethod" id="paymentMethod_voucher" class="">
                    <option value="バウチャー" selected>バウチャー</option>
                    <option value="バウチャー">飲食券</option>
                  </select>
                </div>
                <div class="c-form-select-wrap">
                  <!-- バウチャー -->
                  <select name="paymentMethod" id="paymentMethod_other" class="">
                    <option value="その他" selected>その他</option>
                    <option value="その他">その他1</option>
                    <option value="その他">その他2</option>
                  </select>
                </div>
              </div>

              <!-- 金額自動出力 -->
              <div class="p-register-checkout">
                <div class="p-register-checkout__subtotal">
                  <div class="p-register-checkout__head">
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
                    <div class="p-register-checkout__price--big">10,0000<span class="u-font-yen">円</span></div>
                  </div>
                </div>
                <div class="p-register-checkout__amount-received">
                  <div class="u-pl1">お預かり</div>
                  <div class="p-register-checkout__head">
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
                    <div class="p-register-checkout__price--big">10,0000<span class="u-font-yen">円</span></div>
                  </div>
                </div>
              </div>
            </div><!-- ./p-register__settlement--right -->
          </div>
        </div>

        <div class="l-modal__footer p-register__settlement--foot">
          <!-- disablesの時のクラス付与[--disabled2] -->
          <button type="submit" class="is-block c-button__submit --disabled2 u-horizontal-auto" disabled>決済する</button>
        </div>
      </form>

    </div>
  </div><!-- /.l-modal -->
</main>


@endsection
@push("scripts")
<!-- モーダル -->
<script src="{{ asset('js/modalOption.js') }}"></script>
<!-- 決済画面をモーダルで表示するスクリプト-->
<script src="{{ asset('js/removeButton.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>

<script>
  // 数量変更ボタンのイベントリスナーを設定
  document.querySelectorAll('.c-button-quantity').forEach(function(button) {
    button.addEventListener('click', function() {
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
    });
  });
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
