<div id="modalAreaOption_{{$mode}}_{{$coupon?->id}}" class="l-modal">
  <!-- モーダルのinnerを記述   -->
  <div class="l-modal__inner l-modal--trash">
    <div class="l-modal__head">{{$label}}</div>
    <!-- close button -->
    @if ($mode == 'new')
      <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
    @else
      <div class="l-modal__close modal_optionClose" onclick="closeEditModal({{$coupon->id}})">×</div>
    @endif
    <div class="l-modal__content">
      <form action="{{$action}}" method="POST" enctype="multipart/form-data" class="u-w-full">
        @csrf
        @method($method)
      <!-- <form class="l-flex--column l-flex--column l-flex--sb "> -->
          <div class="c-title__modal--lv3">クーポン設定{{$label}}</div>
            <div class="u-mb3">
              <div class="l-grid--col3-auto l-grid--gap1">
                <div>
                  <!-- クーポン名 -->
                  <label for="name{{$coupon?->id ?? 0}}">クーポン名</label>
                  <input type="text" name="name" id="name{{$coupon?->id ?? 0}}" class="u-w-full-wide" value="{{old('name', $coupon?->name)}}">
                </div>
                <div>
                  <!-- クーポンコード -->
                  <label for="code{{$coupon?->id ?? 0}}">クーポンコード</label>
                  <input type="text" name="code" id="code{{$coupon?->id ?? 0}}" class="u-w-full-wide" value="{{old('code', $coupon?->code)}}">
                </div>
                <div class="l-grid--col3-1fr_auto_auto l-grid--gap025 l-grid--end">
                  <div>
                    <!-- 割引値 -->
                    <label for="discount_amount{{$coupon?->id ?? 0}}">割引値</label>
                    <input type="text" name="discount_amount" id="discount_amount{{$coupon?->id ?? 0}}" class="u-w-full-wide" value="{{old('discount_amount', $coupon?->discount_amount)}}">
                  </div>
                  <!-- 割引種別 1：円、2：% ラジオボタン -->
                  <div class="l-grid__radio">
                    <div class="c-button-optionSelect">
                      <input type="radio" name="discount_type" value="1" id="discount_type1-{{$coupon?->id ?? 0}}"  {{old('discount_type', $coupon?->discount_type) == 1 ? "checked":""}}>
                      <label for="discount_type1-{{$coupon?->id ?? 0}}" class="u-w38 u-h38 u-mb075">円</label>
                    </div>
                    <div class="c-button-optionSelect">
                      <input type="radio" name="discount_type" value="2" id="discount_type2-{{$coupon?->id ?? 0}}" {{old('discount_type', $coupon?->discount_type) == 2 ? "checked":""}}>
                      <label for="discount_type2-{{$coupon?->id ?? 0}}" class="u-w38 u-h38 u-mb075">%</label>
                    </div>
                  </div>
                </div>
              </div>
              <div>
                <div class="l-grid--col4-auto l-grid--gap1">
                  <div>
                    <!-- カテゴリーselect -->
                    <label for="category">対象カテゴリー</label>
                    <div class="c-form-select-wrap">
                      <select name="good_category_id" id="form_good_category_id_{{$coupon?->id}}">
                        <option value="" disabled>選択してください</option>
                        @foreach ($goodCategories as $goodCategory)
                          <option value="{{ $goodCategory->id }}"
                            {{old('good_category_id', $coupon?->good_category_id)==$goodCategory->id ? 'selected':''}}>
                            {{$goodCategory->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <!-- 利用回数制限 -->
                  <div>
                    <label for="use_limit">利用回数制限</label>
                    <!-- 「1回」「複数回」ラジオボタン -->
                    <div class="l-grid__radio">
                      <div class="c-button-optionSelect">
                        <input type="radio" name="limit_flg" value="0" id="limit_flg0-{{$coupon?->id ?? 0}}" {{old('limit_flg', $coupon?->limit_flg) == 0 ? "checked":""}}>
                        <label for="limit_flg0-{{$coupon?->id ?? 0}}" class="u-w70 u-h40 u-mb1">1回</label>
                      </div>
                      <div class="c-button-optionSelect">
                        <input type="radio" name="limit_flg" value="1" id="limit_flg1-{{$coupon?->id ?? 0}}" {{old('limit_flg', $coupon?->limit_flg) == 1 ? "checked":""}}>
                        <label for="limit_flg1-{{$coupon?->id ?? 0}}" class="u-w70 u-h40 u-mb1">複数回</label>
                      </div>
                    </div>
                  </div>

                  <!-- 併用 -->
                  <div>
                    <label for="allow_combine">併用</label>
                    <!-- 「可」「不可」ラジオボタン -->
                    <div class="l-grid__radio">
                      <div class="c-button-optionSelect">
                        <input type="radio" name="combination_flg" value="1" id="combination_flg1-{{$coupon?->id ?? 0}}" {{old('combination_flg', $coupon?->combination_flg) == 1 ? "checked":""}}>
                        <label for="combination_flg1-{{$coupon?->id ?? 0}}" class="u-w70 u-h40 u-mb1">可</label>
                      </div>
                      <div class="c-button-optionSelect">
                        <input type="radio" name="combination_flg" value="0" id="combination_flg0-{{$coupon?->id ?? 0}}" {{old('combination_flg', $coupon?->combination_flg) == 0 ? "checked":""}}>
                        <label for="combination_flg0-{{$coupon?->id ?? 0}}" class="u-w70 u-h40 u-mb1">不可</label>
                      </div>
                    </div>
                  </div>

                  <!-- メモ -->
                  <div>
                    <label for="memo{{$coupon?->id ?? 0}}">メモ</label>
                    <textarea name="memo" id="memo{{$coupon?->id ?? 0}}" cols="20" rows="1"> {{old('memo', $coupon?->memo)}}</textarea>
                  </div>
                </div>
              </div>

              <div class="l-flex l-grid--gap1">
                <div>
                  <!-- 割引対象となる入庫日 -->
                  <label for="start_date{{$coupon?->id ?? 0}}">割引対象となる入庫日</label>
                  <input type="date" name="start_date" id="start_date{{$coupon?->id ?? 0}}" value="{{old('start_date', $coupon?->start_date->format('Y-m-d'))}}">
                  <!-- 時間 -->
                  <input type="time" name="start_time" id="start_time{{$coupon?->id ?? 0}}" value="{{old('start_time', $coupon?->start_date->format('H:i'))}}">
                </div>
                <span>～</span>
                <div>
                  <!-- 終了日 -->
                  <label for="end_date{{$coupon?->id ?? 0}}">終了日</label>
                  <input type="date" name="end_date" id="end_date{{$coupon?->id ?? 0}}" value="{{old('end_date', $coupon?->end_date->format('Y-m-d'))}}">
                  <!-- 時間 -->
                  <input type="time" name="end_time" id="end_time{{$coupon?->id ?? 0}}" value="{{old('end_time', $coupon?->end_date->format('H:i'))}}">
                </div>
              </div>

            </div>

            <div class="l-flex--center l-grid--gap1">
              <button type="submit" id="modal_{{$mode}}_{{$coupon?->id}}" class="c-button__submit">保存</button>
            </div>

      </form>
    </div><!-- ./l-modal__content -->

  </div><!-- ./l-modal inner -->
  <!-- 閉じる・追加ボタン -->
</div>
