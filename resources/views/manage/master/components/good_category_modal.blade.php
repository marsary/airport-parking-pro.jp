<div id="modalAreaOption_{{$mode}}_{{$goodCategory?->id}}" class="modal_area l-modal">
  <!-- モーダルのinnerを記述   -->
  <div class="l-modal__inner l-modal--trash">
    <div class="l-modal__head">{{$label}}</div>
    <!-- close button -->
    @if ($mode == 'new')
      <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
    @else
      <div class="l-modal__close modal_optionClose" onclick="closeEditModal({{$goodCategory->id}})">×</div>
    @endif
    <div class="l-modal__content">
      <form action="{{$action}}" class="l-flex--column l-flex--column l-flex--sb u-w-full" method="POST">
        @csrf
        @method($method)
        <div class="l-modal--productCategory-edit">
          <div class="c-title__modal--lv3">商品カテゴリー{{$label}}</div>

            <!-- 1列目 -->
            <dl>
              <dt>商品カテゴリー名</dt>
              <dd>
                <input type="text" name="name" class="u-w-full-wide" value="{{old('name', $goodCategory?->name)}}">
              </dd>
            </dl>
            <dl>
              <dt>
                区分
              </dt>
              <dd class="c-form-select-color u-mb0">
                <select name="type">
                  @foreach(\App\Enums\GoodCategoryType::cases() as $type)
                    <option value="{{$type->value}}" {{old('type', $goodCategory?->type)==$type->value ? 'selected':''}}>
                      {{$type->label()}}
                    </option>
                  @endforeach
                  {{--  <option value="">出庫までに作業が必要</option>
                  <option value="">出庫までに作業が不要</option>  --}}
                </select>
              </dd>
            </dl>
            <dl>
              <dt>メモ</dt>
              <dd>
                <textarea class="u-w-full-wide" rows="5" name="memo">{{old('memo', $goodCategory?->memo)}}</textarea>
              </dd>
            </dl>

        </div>

        <div class="l-flex--center l-grid--gap1 u-mt2 u-mb2">
          <button type="submit" id="modal_{{$mode}}_{{$goodCategory?->id}}" class="c-button__submit">保存</button>
        </div>
      </form>
    </div><!-- ./l-modal__content -->

    <!-- データ削除ボタン -->

    @if ($mode == 'edit')
      <form id="delete_{{$goodCategory->id}}_form" action="{{route('manage.master.good_categories.destroy', [$goodCategory->id])}}" method="post">
        @csrf
        @method('DELETE')
      </form>
      <div class="l-modal__trashButton" onclick="deleteGoodCategory({{$goodCategory->id}})">
        <img src="{{asset('images/svg/trash.svg')}}" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
      </div>
    @endif
  </div><!-- ./l-modal inner -->
  <!-- 閉じる・追加ボタン -->
</div>
