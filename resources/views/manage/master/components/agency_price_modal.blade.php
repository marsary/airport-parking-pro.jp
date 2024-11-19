<div id="modalAreaOption_{{$mode}}_{{$agencyPrice?->id}}" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal--trash">
      <div class="l-modal__head">{{$label}}</div>
      <!-- close button -->
      @if ($mode == 'new')
        <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
      @else
        <div class="l-modal__close modal_optionClose" onclick="closeEditModal({{$agencyPrice->id}})">×</div>
      @endif
      <div class="l-modal__content">
        <form action="{{$action}}" method="POST" class="l-flex--column l-flex--sb u-w-full">
          @csrf
          @method($method)
          <!-- 入力フォーム -->
          <div class="u-w-full-wide">
            <div class="c-title__modal--lv3">代理店料金設定{{$label}}</div>
            <div class="">
              <div class="l-grid--col2 l-grid--gap2">
                @if ($mode == 'new')
                  <div>
                    <label for="agency_id{{$agencyPrice?->id ?? 0}}" class="u-font--md">代理店選択</label>
                    <div class="c-form-select-wrap">
                      <select name="agency_id" id="agency_id{{$agencyPrice?->id ?? 0}}">
                        <option value="" disabled>選択してください</option>
                        @foreach ($agencies as $agency)
                          <option value="{{ $agency->id }}"
                            {{old('agency_id')==$agency->id ? 'selected':''}}>
                            {{$agency->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                @endif

                <div>
                  <label for="price_list" class="u-font--md">料金表選択</label>
                  <div class="c-form-select-wrap">
                    <select name="price_list" id="price_list">
                      <option value="" disabled selected>選択してください</option>
                      <option value="price_list_New">新規登録</option>
                      <option value="price_list1">2024年1月1日～2024年3月31日</option>
                      <option value="price_list2">2024年4月28日～2024年5月8日</option>
                    </select>
                  </div>
                </div>

                <!-- 料金の適用期間 -->
                <div>
                  <p class="u-mb025 u-font--md">料金の適用期間</p>
                  <div class="l-flex l-grid--gap05">
                    <input type="date" id="start_date{{$agencyPrice?->id ?? 0}}" name="start_date" value="{{old('start_date', $agencyPrice?->start_date->format('Y-m-d'))}}" class="u-w-full-wide">
                    <p class="u-mb1">～</p>
                    <input type="date" name="end_date" id="end_date{{$agencyPrice?->id ?? 0}}" value="{{old('end_date', $agencyPrice?->end_date->format('Y-m-d'))}}" class="u-w-full-wide">
                  </div>
                </div>
              </div>

              <!-- MEMO -->
              <label for="memo" class="u-font--md">メモ</label>
              <textarea name="memo" id="memo{{$agencyPrice?->id ?? 0}}" rows="1" class="u-w-full-wide"> {{old('memo', $agencyPrice?->memo)}}</textarea>

              <!-- 1日目～15日まで料金を入力するinput -->
              <div class="l-grid--pricing l-grid--input">
                <div>
                  <label for="d1">1日目</label>
                  <input type="text" id="d1_{{$agencyPrice?->id ?? 0}}" name="d1" value="{{old('d1', $agencyPrice?->d1)}}">
                </div>
                <div>
                  <label for="d2_{{$agencyPrice?->id ?? 0}}">2日目</label>
                  <input type="text" id="d2_{{$agencyPrice?->id ?? 0}}" name="d2" value="{{old('d2', $agencyPrice?->d2)}}">
                </div>
                <div>
                  <label for="d3_{{$agencyPrice?->id ?? 0}}">3日目</label>
                  <input type="text" id="d3_{{$agencyPrice?->id ?? 0}}" name="d3" value="{{old('d3', $agencyPrice?->d3)}}">
                </div>
                <div>
                  <label for="d4_{{$agencyPrice?->id ?? 0}}">4日目</label>
                  <input type="text" id="d4_{{$agencyPrice?->id ?? 0}}" name="d4" value="{{old('d4', $agencyPrice?->d4)}}">
                </div>
                <div>
                  <label for="d5_{{$agencyPrice?->id ?? 0}}">5日目</label>
                  <input type="text" id="d5_{{$agencyPrice?->id ?? 0}}" name="d5" value="{{old('d5', $agencyPrice?->d5)}}">
                </div>
                <div>
                  <label for="d6_{{$agencyPrice?->id ?? 0}}">6日目</label>
                  <input type="text" id="d6_{{$agencyPrice?->id ?? 0}}" name="d6" value="{{old('d6', $agencyPrice?->d6)}}">
                </div>
                <div>
                  <label for="d7_{{$agencyPrice?->id ?? 0}}">7日目</label>
                  <input type="text" id="d7_{{$agencyPrice?->id ?? 0}}" name="d7" value="{{old('d7', $agencyPrice?->d7)}}">
                </div>
                <div>
                  <label for="d8_{{$agencyPrice?->id ?? 0}}">8日目</label>
                  <input type="text" id="d8_{{$agencyPrice?->id ?? 0}}" name="d8" value="{{old('d8', $agencyPrice?->d8)}}">
                </div>
                <div>
                  <label for="d9_{{$agencyPrice?->id ?? 0}}">9日目</label>
                  <input type="text" id="d9_{{$agencyPrice?->id ?? 0}}" name="d9" value="{{old('d9', $agencyPrice?->d9)}}">
                </div>
                <div>
                  <label for="d10_{{$agencyPrice?->id ?? 0}}">10日目</label>
                  <input type="text" id="d10_{{$agencyPrice?->id ?? 0}}" name="d10" value="{{old('d10', $agencyPrice?->d10)}}">
                </div>
                <div>
                  <label for="d11_{{$agencyPrice?->id ?? 0}}">11日目</label>
                  <input type="text" id="d11_{{$agencyPrice?->id ?? 0}}" name="d11" value="{{old('d11', $agencyPrice?->d11)}}">
                </div>
                <div>
                  <label for="d12_{{$agencyPrice?->id ?? 0}}">12日目</label>
                  <input type="text" id="d12_{{$agencyPrice?->id ?? 0}}" name="d12" value="{{old('d12', $agencyPrice?->d12)}}">
                </div>
                <div>
                  <label for="d13_{{$agencyPrice?->id ?? 0}}">13日目</label>
                  <input type="text" id="d13_{{$agencyPrice?->id ?? 0}}" name="d13" value="{{old('d13', $agencyPrice?->d13)}}">
                </div>
                <div>
                  <label for="d14_{{$agencyPrice?->id ?? 0}}">14日目</label>
                  <input type="text" id="d14_{{$agencyPrice?->id ?? 0}}" name="d14" value="{{old('d14', $agencyPrice?->d14)}}">
                </div>
                <div>
                  <label for="d15_{{$agencyPrice?->id ?? 0}}">15日目</label>
                  <input type="text" id="d15_{{$agencyPrice?->id ?? 0}}" name="d15" value="{{old('d15', $agencyPrice?->d15)}}">
                </div>
                <div>
                  <label for="price_per_day{{$agencyPrice?->id ?? 0}}">16日目以降</label>
                  <input type="text" id="price_per_day{{$agencyPrice?->id ?? 0}}" name="price_per_day" value="{{old('price_per_day', $agencyPrice?->price_per_day)}}">
                </div>
                <div>
                  <label for="late_fee{{$agencyPrice?->id ?? 0}}">延長料</label>
                  <input type="text" id="late_fee{{$agencyPrice?->id ?? 0}}" name="late_fee" value="{{old('late_fee', $agencyPrice?->late_fee)}}">
                </div>
                <div class="--last">
                  <label for="base_price{{$agencyPrice?->id ?? 0}}">基本料金</label>
                  <input type="text" id="base_price{{$agencyPrice?->id ?? 0}}" name="base_price" value="{{old('base_price', $agencyPrice?->base_price)}}">
                </div>
              </div>
            </div>
          </div>
          <!-- 閉じる・追加ボタン -->
          <div class="l-flex--center l-grid--gap1 u-mt2 u-mb4">
            @if ($mode == 'new')
              <button type="button" class="c-button__submit--dark-gray modal_optionClose" onclick="closeCreateModal()">閉じる</button>
            @else
              <button type="button" class="c-button__submit--dark-gray modal_optionClose" onclick="closeEditModal({{$agencyPrice->id}})">閉じる</button>
            @endif
            <button type="submit" id="modal_{{$mode}}_{{$agencyPrice?->id}}" class="c-button__submit">登録</button>
          </div>
        </form>
      </div><!-- ./l-modal__content -->

      <!-- 編集の場合のデータ削除ボタン -->
      @if ($mode == 'edit')
        <form id="delete_{{$agencyPrice->id}}_form" action="{{route('manage.master.agency_prices.destroy', [$agencyPrice->id])}}" method="post">
          @csrf
          @method('DELETE')
        </form>
        <div class="l-modal__trashButton" onclick="deleteagencyPrice({{$agencyPrice->id}})">
          <img src="{{asset('images/svg/trash.svg')}}" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
        </div>
      @endif
    </div><!-- ./l-modal inner -->
  </div>
