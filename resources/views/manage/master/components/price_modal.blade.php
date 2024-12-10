<div id="modalAreaOption_{{$mode}}_{{$price?->id}}" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal--trash">
      <div class="l-modal__head">{{$label}}</div>
      <!-- close button -->
      @if ($mode == 'new')
        <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
      @else
        <div class="l-modal__close modal_optionClose" onclick="closeEditModal({{$price->id}})">×</div>
      @endif
      <div class="l-modal__content">
        <form action="{{$action}}" method="POST" class="l-flex--column l-flex--sb u-w-full">
          @csrf
          @method($method)
          <!-- 入力フォーム -->
          <div class="u-w-full-wide">
            <div class="c-title__modal--lv3">基本料金設定{{$label}}</div>
            <div class="">
              <div class="l-grid--col2 l-grid--gap2">
                {{--  <div>
                  <label for="price_list" class="u-font--md">料金表選択</label>
                  <div class="c-form-select-wrap">
                    <select name="price_list" id="price_list">
                      <option value="" disabled selected>選択してください</option>
                      <option value="price_list_New">新規登録</option>
                      <option value="price_list1">2024年1月1日～2024年3月31日</option>
                      <option value="price_list2">2024年4月28日～2024年5月8日</option>
                    </select>
                  </div>
                </div>  --}}

                <!-- 料金の適用期間 -->
                <div>
                  <p class="u-mb025 u-font--md">料金の適用期間</p>
                  <div class="l-flex l-grid--gap05">
                    <input type="date" id="start_date{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[start_date]" value="{{old('record_' . ($price?->id ?? 0) . '.start_date', $price?->start_date->format('Y-m-d'))}}" class="u-w-full-wide">
                    <p class="u-mb1">～</p>
                    <input type="date" name="record_{{ $price?->id ?? 0 }}[end_date]" id="end_date{{$price?->id ?? 0}}" value="{{old('record_' . ($price?->id ?? 0) . '.end_date', $price?->end_date->format('Y-m-d'))}}" class="u-w-full-wide">
                  </div>
                </div>
              </div>

              <!-- MEMO -->
              <label for="memo" class="u-font--md">メモ</label>
              <textarea name="record_{{ $price?->id ?? 0 }}[memo]" id="memo{{$price?->id ?? 0}}" rows="1" class="u-w-full-wide"> {{old('record_' . ($price?->id ?? 0) . '.memo', $price?->memo)}}</textarea>

              <!-- 1日目～15日まで料金を入力するinput -->
              <div class="l-grid--pricing l-grid--input">
                <div>
                  <label for="d1">1日目</label>
                  <input type="text" id="d1_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d1]" value="{{old('record_' . ($price?->id ?? 0) . '.d1', $price?->d1)}}">
                </div>
                <div>
                  <label for="d2_{{$price?->id ?? 0}}">2日目</label>
                  <input type="text" id="d2_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d2]" value="{{old('record_' . ($price?->id ?? 0) . '.d2', $price?->d2)}}">
                </div>
                <div>
                  <label for="d3_{{$price?->id ?? 0}}">3日目</label>
                  <input type="text" id="d3_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d3]" value="{{old('record_' . ($price?->id ?? 0) . '.d3', $price?->d3)}}">
                </div>
                <div>
                  <label for="d4_{{$price?->id ?? 0}}">4日目</label>
                  <input type="text" id="d4_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d4]" value="{{old('record_' . ($price?->id ?? 0) . '.d4', $price?->d4)}}">
                </div>
                <div>
                  <label for="d5_{{$price?->id ?? 0}}">5日目</label>
                  <input type="text" id="d5_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d5]" value="{{old('record_' . ($price?->id ?? 0) . '.d5', $price?->d5)}}">
                </div>
                <div>
                  <label for="d6_{{$price?->id ?? 0}}">6日目</label>
                  <input type="text" id="d6_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d6]" value="{{old('record_' . ($price?->id ?? 0) . '.d6', $price?->d6)}}">
                </div>
                <div>
                  <label for="d7_{{$price?->id ?? 0}}">7日目</label>
                  <input type="text" id="d7_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d7]" value="{{old('record_' . ($price?->id ?? 0) . '.d7', $price?->d7)}}">
                </div>
                <div>
                  <label for="d8_{{$price?->id ?? 0}}">8日目</label>
                  <input type="text" id="d8_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d8]" value="{{old('record_' . ($price?->id ?? 0) . '.d8', $price?->d8)}}">
                </div>
                <div>
                  <label for="d9_{{$price?->id ?? 0}}">9日目</label>
                  <input type="text" id="d9_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d9]" value="{{old('record_' . ($price?->id ?? 0) . '.d9', $price?->d9)}}">
                </div>
                <div>
                  <label for="d10_{{$price?->id ?? 0}}">10日目</label>
                  <input type="text" id="d10_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d10]" value="{{old('record_' . ($price?->id ?? 0) . '.d10', $price?->d10)}}">
                </div>
                <div>
                  <label for="d11_{{$price?->id ?? 0}}">11日目</label>
                  <input type="text" id="d11_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d11]" value="{{old('record_' . ($price?->id ?? 0) . '.d11', $price?->d11)}}">
                </div>
                <div>
                  <label for="d12_{{$price?->id ?? 0}}">12日目</label>
                  <input type="text" id="d12_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d12]" value="{{old('record_' . ($price?->id ?? 0) . '.d12', $price?->d12)}}">
                </div>
                <div>
                  <label for="d13_{{$price?->id ?? 0}}">13日目</label>
                  <input type="text" id="d13_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d13]" value="{{old('record_' . ($price?->id ?? 0) . '.d13', $price?->d13)}}">
                </div>
                <div>
                  <label for="d14_{{$price?->id ?? 0}}">14日目</label>
                  <input type="text" id="d14_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d14]" value="{{old('record_' . ($price?->id ?? 0) . '.d14', $price?->d14)}}">
                </div>
                <div>
                  <label for="d15_{{$price?->id ?? 0}}">15日目</label>
                  <input type="text" id="d15_{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[d15]" value="{{old('record_' . ($price?->id ?? 0) . '.d15', $price?->d15)}}">
                </div>
                <div>
                  <label for="price_per_day{{$price?->id ?? 0}}">16日目以降</label>
                  <input type="text" id="price_per_day{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[price_per_day]" value="{{old('record_' . ($price?->id ?? 0) . '.price_per_day', $price?->price_per_day)}}">
                </div>
                <div>
                  <label for="late_fee{{$price?->id ?? 0}}">延長料</label>
                  <input type="text" id="late_fee{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[late_fee]" value="{{old('record_' . ($price?->id ?? 0) . '.late_fee', $price?->late_fee)}}">
                </div>
                <div class="--last">
                  <label for="base_price{{$price?->id ?? 0}}">基本料金</label>
                  <input type="text" id="base_price{{$price?->id ?? 0}}" name="record_{{ $price?->id ?? 0 }}[base_price]" value="{{old('record_' . ($price?->id ?? 0) . '.base_price', $price?->base_price)}}">
                </div>
              </div>
            </div>
          </div>
          <!-- 閉じる・追加ボタン -->
          <div class="l-flex--center l-grid--gap1 u-mt2 u-mb4">
            @if ($mode == 'new')
              <button type="button" class="c-button__submit--dark-gray modal_optionClose" onclick="closeCreateModal()">閉じる</button>
            @else
              <button type="button" class="c-button__submit--dark-gray modal_optionClose" onclick="closeEditModal({{$price->id}})">閉じる</button>
            @endif
            <button type="submit" id="modal_{{$mode}}_{{$price?->id}}" class="c-button__submit">登録</button>
          </div>
        </form>
      </div><!-- ./l-modal__content -->

      <!-- 編集の場合のデータ削除ボタン -->
      @if ($mode == 'edit')
        <form id="delete_{{$price->id}}_form" action="{{route('manage.master.prices.destroy', [$price->id])}}" method="post">
          @csrf
          @method('DELETE')
        </form>
        <div class="l-modal__trashButton" onclick="deletePrice({{$price->id}})">
          <img src="{{asset('images/svg/trash.svg')}}" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
        </div>
      @endif
    </div><!-- ./l-modal inner -->
  </div>
