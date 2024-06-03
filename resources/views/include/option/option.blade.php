  <!-- オプションをクリックしたら出てくるmodal -->
  <div id="modalAreaOption{{$modalId}}" class="l-modal modal-option">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner">
      <div class="l-modal__head">オプション選択：{{$goodCategory->name}}</div>
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

