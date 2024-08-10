  <!-- オプションをクリックしたら出てくるmodal -->
  <div id="modalAreaOption{{$modalId}}" class="l-modal modal-option">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner">
      <div class="l-modal__head">オプション選択：{{$goodCategory->name}}
        <!-- 合計金額を表示 -->
        <div class="l-modal__head--option__total">
          合計金額 :  <span id="total-amount">0</span>円
        </div>
      </div>
      <div class="l-modal__close modal_optionClose" onclick="closeOptionModal({{$modalId}})">×</div>
      <div class="l-modal__content">
        <div class="l-grid--col4 l-grid--gap1">
          <!-- オプションを押したら表示 -->
          @foreach ($goods as $good)
            <div class="c-button-optionSelect">
              <input type="checkbox" id="modal_good_ids_{{$good->id}}" name="modal_good_ids[]" value="{{$good->id}}"
                {{(in_array($good->id, old('modal_good_ids', isset($reserve->good_ids) ? $reserve->good_ids: []))) ? 'checked ':''}}
              >
              <label for="modal_good_ids_{{$good->id}}" class="text-center u-pt2 u-pb2">{{$good->name}}<br>{{number_format($good->price)}}円</label>
              <div class="c-button-optionQuantity__wrap">
              <!-- 合計金額をdata-priceの値から計算 -->
                <input type="text" id="car_wash" class="c-buttonQuantity__input" name="car_wash" value="0" readonly data-price="{{$good->price}}">
                <button class="c-button-optionQuantity c-button-optionQuantity--up" onclick="changeQuantity('car_wash', 1)">＋</button>
                <button class="c-button-optionQuantity c-button-optionQuantity--down" onclick="changeQuantity('car_wash', -1)">－</button>
              </div>
            </div>
          @endforeach
          {{--  <div class="c-button-optionSelect">
            <input type="checkbox" id="wax" name="wax" value="WAX">
            <label for="wax" class="text-center u-pt2 u-pb2">WAX<br>2，500円</label>
          </div>
          <div class="c-button-optionSelect">
            <input type="checkbox" id="dummy1" name="dummy1" value="">
            <label for="dummy1" class="text-center u-pt2 u-pb2">dummy1<br>2，500円</label>
          </div>
          <div class="c-button-optionSelect">
            <input type="checkbox" id="dummy2" name="dummy2" value="">
            <label for="dummy2" class="text-center u-pt2 u-pb2">dummy2<br>2，500円</label>
          </div>
          <div class="c-button-optionSelect">
            <input type="checkbox" id="dummy3" name="dummy3" value="">
            <label for="dummy3" class="text-center u-pt2 u-pb2">dummy3<br>2，500円</label>
          </div>
          <div class="c-button-optionSelect">
            <input type="checkbox" id="dummy4" name="dummy4" value="">
            <label for="dummy4" class="text-center u-pt2 u-pb2">dummy4<br>2，500円</label>
          </div>  --}}
        </div>
        <div class="l-flex--center l-grid--gap1 u-mt3">
          <button type="button" class="c-button__submit--gray modal_optionClose" onclick="closeOptionModal({{$modalId}})">閉じる</button>
          <button type="button" id="modal_add_{{$modalId}}" onclick="addOptions({{$modalId}})" class="c-button__submit--green">追加</button>
        </div>
      </div>
    </div>
  </div>

<script>
function changeQuantity(inputId, change) {
  // 数量を変更
  const input = document.getElementById(inputId);
  let currentValue = parseInt(input.value, 10);
  currentValue += change;
  if (currentValue < 0) {
      currentValue = 0;
  }
  input.value = currentValue;

  // 1以上の場合は背景色を変更
  const headElement = input.closest('.c-button-optionSelect').querySelector('.c-button-optionSelect__head');
  if (currentValue >= 1) {
      headElement.classList.add('background-green');
  } else {
      headElement.classList.remove('background-green');
  }

  updateTotalAmount();
}

// 合計金額を更新
function updateTotalAmount() {
  const inputs = document.querySelectorAll('.c-buttonQuantity__input');
  let totalAmount = 0;

  inputs.forEach(input => {
    const quantity = parseInt(input.value, 10);
    const price = parseInt(input.getAttribute('data-price'), 10);
    totalAmount += quantity * price;
  });

  document.getElementById('total-amount').textContent = totalAmount;
}

</script>