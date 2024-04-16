  <!-- オプションをクリックしたら出てくるmodal -->
  <div id="modalAreaOption" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner">
      <div class="l-modal__head">オプション選択：洗車</div>
      <div class="l-modal__close modal_optionClose">×</div>
      <div class="l-modal__content">
        <div class="l-grid--col4 l-grid--gap1">
          <!-- 洗車を押したら表示 -->
          <div class="c-button-optionSelect">
            <input type="checkbox" id="car_wash" name="car_wash" value="洗車">
            <label for="car_wash" class="text-center u-pt2 u-pb2">手洗いWAX洗車<br>2，500円</label>
          </div>
          <div class="c-button-optionSelect">
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
          </div>
        </div>
        <div class="l-flex--center l-grid--gap1 u-mt3">
          <button type="button" class="c-button__submit--gray modal_optionClose">閉じる</button>
          <button type="button" id="modal_add" class="c-button__submit--green">追加</button>
        </div>
      </div>
    </div>
  </div>