<div id="modalAreaOption_{{$mode}}_{{$good?->id}}" class="l-modal">
  <!-- モーダルのinnerを記述   -->
  <div class="l-modal__inner l-modal--trash">
    <div class="l-modal__head">{{$label}}</div>
    <!-- close button -->
    @if ($mode == 'new')
      <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
    @else
      <div class="l-modal__close modal_optionClose" onclick="closeEditModal({{$good->id}})">×</div>
    @endif
    <div class="l-modal__content">
      <form action="{{$action}}" class="l-flex--column l-flex--column l-flex--sb u-w-full" method="POST">
        @csrf
        @method($method)
        <div class="u-w-full-wide">
          <div class="c-title__modal--lv3">商品{{$label}}</div>
          <div class="l-modal--product-edit">
            <!-- 1列目 -->
            <dl>
              <dt>商品名</dt>
              <dd>
                <input type="text" name="name" value="{{old('name', $good?->name)}}" class="u-w-full-wide">
              </dd>
            </dl>
            <div class="l-grid--col2 l-grid--gap1">
              <dl>
                <dt>
                  カテゴリー
                </dt>
                <dd class="c-form-select-color u-mb0">
                  <select name="good_category_id" id="form_good_category_id_{{$good?->id}}">
                    @foreach ($goodCategories as $goodCategory)
                      <option value="{{ $goodCategory->id }}"
                        {{old('good_category_id', $good?->good_category_id)==$goodCategory->id ? 'selected':''}}>
                        {{$goodCategory->name }}
                      </option>
                    @endforeach
                    {{--  <option value="">洗車</option>
                    <option value="">オプション</option>  --}}
                  </select>
                </dd>
              </dl>
              <dl>
                <dt>
                  税区分
                </dt>
                <dd class="c-form-select-color u-mb0">
                  <select name="tax_type" id="tax_type_{{$good?->id}}" onchange="dispTotal({{$good?->id}});" >
                    @foreach(\App\Enums\TaxType::cases() as $type)
                      <option value="{{$type->value}}" {{old('tax_type', $good?->tax_type)==$type->value ? 'selected':''}}>
                        {{$type->label()}}
                      </option>
                    @endforeach
                    {{--  <option value="1.1">10%</option>
                    <option value="1.08">8%</option>  --}}
                  </select>
                </dd>
              </dl>
            </div>

            <!-- 2列目 -->
            <dl>
              <dt>略称</dt>
              <dd>
                <input type="text" name="abbreviation" value="{{old('abbreviation', $good?->abbreviation)}}" class="u-w-full-wide">
              </dd>
            </dl>
            <dl>
              <dt>金額（税抜）</dt>
              <dd>
                <input type="text" name="price" value="{{old('price', $good?->price)}}" id="price_{{$good?->id}}"
                 onchange="dispTotal({{$good?->id}});" class="u-w-full-wide">
              </dd>
            </dl>

            <!-- 3列目 -->
            <dl>
              <dt>説明</dt>
              <dd>
                <input type="text" name="memo" value="{{old('memo', $good?->memo)}}" class="u-w-full-wide">
              </dd>
            </dl>

            <div class="l-flex l-flex--space-between u-border--bottom u-pb05">
              <label for="total">税込価格</label>
              @php
                $total = 0;
                if($good) {
                  $total = $good->totalPrice();
                } elseif(null !== old('price') && null !== old('tax_type')) {
                  $total = \App\Services\Goods\GoodPrice::getTotalPrice(old('tax_type'), old('price'));
                }
              @endphp
              <span id="total_{{$good?->id}}" class="u-font--24 text-right">{{$total}}円</span>
            </div>
          </div>
        </div>

        <div class="l-flex--center l-grid--gap1 u-mt4 u-mb2">
          <button type="submit" id="modal_{{$mode}}_{{$good?->id}}" class="c-button__submit">保存</button>
        </div>
      </form>
    </div><!-- ./l-modal__content -->

    <!-- データ削除ボタン -->
    @if ($mode == 'edit')
      <form id="delete_{{$good->id}}_form" action="{{route('manage.master.goods.destroy', [$good->id])}}" method="post">
        @csrf
        @method('DELETE')
      </form>
      <div class="l-modal__trashButton" onclick="deleteGood({{$good->id}})">
        <img src="{{asset('images/svg/trash.svg')}}" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
      </div>
    @endif
  </div><!-- ./l-modal inner -->
  <!-- 閉じる・追加ボタン -->
</div>
