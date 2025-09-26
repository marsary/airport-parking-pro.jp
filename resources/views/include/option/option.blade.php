  @php
    $totalAmount = 0;
    foreach ($goods as $good) {
      $goodNums = old('modal_good_nums', isset($reserve->good_nums) ? $reserve->good_nums: []);
      $goodNum = $goodNums[$good->id] ?? 0;

      $totalAmount += $goodNum * $good->price;
    }
  @endphp
  <!-- オプションをクリックしたら出てくるmodal -->
  <div id="modalAreaOption{{$modalId}}" class="l-modal modal-option">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner">
      <div class="l-modal__head">オプション選択：{{$goodCategory->name}}
        <!-- 合計金額を表示 -->
        <div class="l-modal__head--option__total">
          合計金額 :  <span id="total-amount-{{$modalId}}" class="total-amount">{{$totalAmount}}</span>円
        </div>
      </div>
      <div class="l-modal__close modal_optionClose" onclick="closeOptionModal({{$modalId}})">×</div>
      <div class="l-modal__content">
        <div class="l-grid--col4 l-grid--gap1">
          <!-- オプションを押したら表示 -->
          @foreach ($goods as $good)
            <div class="c-button-optionSelect" onclick="handleOptionChange({{$good->id}})">
              <input type="checkbox" id="modal_good_ids_{{$good->id}}" name="modal_good_ids[]" value="{{$good->id}}" autocomplete="off"
                {{(in_array($good->id, old('modal_good_ids', isset($reserve->good_ids) ? $reserve->good_ids: []))) ? 'checked="checked"':''}}
              >
              <label for="modal_good_ids_{{$good->id}}" class="text-center u-pt2 u-pb2">{{$good->name}}<br>{{number_format($good->price)}}円</label>
              <div class="c-button-optionQuantity__wrap">
              <!-- 合計金額をdata-priceの値から計算 -->
                @php
                  $goodNums = old('modal_good_nums', isset($reserve->good_nums) ? $reserve->good_nums: []);
                  $goodNum = $goodNums[$good->id] ?? 0;
                @endphp
                <button type="button" class="c-button-optionQuantity c-button-optionQuantity--down" onclick="changeQuantity('modal_good_nums_{{$good->id}}', -1);updateOptionQuantity({{$good->id}})">－</button>
                <input type="text" id="modal_good_nums_{{$good->id}}" class="c-buttonQuantity__input modal_good_nums" name="modal_good_nums[{{$good->id}}]" value="{{$goodNum}}" readonly data-price="{{$good->price}}">
                <button type="button" class="c-button-optionQuantity c-button-optionQuantity--up" onclick="changeQuantity('modal_good_nums_{{$good->id}}', 1);updateOptionQuantity({{$good->id}})">＋</button>
              </div>
            </div>
          @endforeach
        </div>
        <div class="l-flex--center l-grid--gap1 u-mt3">
          <button type="button" class="c-button__submit--gray modal_optionClose" onclick="closeOptionModal({{$modalId}})">閉じる</button>
          <button type="button" id="modal_add_{{$modalId}}" onclick="addOptions({{$modalId}}); closeOptionModal({{$modalId}});" class="c-button__submit--green">追加</button>
        </div>
      </div>
    </div>
  </div>

<script>
// オプションの選択時処理
function handleOptionChange(goodId) {
  const checkboxElem = document.getElementById('modal_good_ids_' + goodId);
  const modalGoodNumElem = document.getElementById('modal_good_nums_' + goodId);
  if(checkboxElem.checked) {
    if(modalGoodNumElem.value == 0) {
      modalGoodNumElem.value = 1
    }
  } else {
    modalGoodNumElem.value = 0
  }
  updateTotalAmount(checkboxElem)
}

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
  {{--  const headElement = input.closest('.c-button-optionSelect').querySelector('.c-button-optionSelect__head');
  if (currentValue >= 1) {
      headElement.classList.add('background-green');
  } else {
      headElement.classList.remove('background-green');
  }  --}}

  updateTotalAmount(input);
}

</script>
