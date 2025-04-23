<div id="modalAreaOption_{{$mode}}_" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal--trash">
        <div class="l-modal__head">{{$label}}</div>
        <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
        <div class="l-modal__content">

            <form method="POST" action="?" class="l-grid--col2-1fr_160 l-grid--gap2 u-font--md l-grid--start">
                <!-- 開始日 -->
                <div class="l-grid--col2 l-grid--gap2">
                    <div class="form-item">
                    <label for="start_date">開始日</label>
                    <input type="date" id="start_date" name="start_date" class="u-mb0 c-input u-w-full-wide" required>
                    </div>
                    <div class="form-item">
                    <label for="end_date">終了日</label>
                    <input type="date" id="end_date" name="end_date" class="u-mb0 c-input u-w-full-wide" required>
                    </div>
                </div>

                <!-- 一括登録ボタン -->
                <button class="c-button__register l-grid--self-end u-font--normal" formaction="">登録</button>
                
                <div class="l-grid--col5 l-grid--gap1">
                    <!-- 入庫上限 -->
                    <div class="form-item">
                    <label for="max_entry">入庫上限</label>
                    <input type="text" id="max_entry" name="max_entry" class="c-input u-w-full-wide" required>
                    </div>
                    <!-- 出庫上限 -->
                    <div class="form-item">
                    <label for="max_withdrawal">出庫上限</label>
                    <input type="text" id="max_withdrawal" name="max_withdrawal" class="c-input u-w-full-wide" required>
                    </div>
                    <div class="form-item">
                    <label for="end_stock">おわり在庫</label>
                    <input type="text" id="end_stock" name="end_stock" class="c-input u-w-full-wide" required>
                    </div>
                    <div class="form-item">
                    <label for="limit_per_15min">15分あたりの上限</label>
                    <input type="text" id="limit_per_15min" name="limit_per_15min" class="c-input u-w-full-wide" required>
                    </div>
                </div>
                <div class="l-grid__right-submitButton--button c-button__csv--upload u-mb1 u-pt1-half">
                    {{-- <input type="submit" value="登録" class="c-button__register fileUpload" formaction="" > --}}
                    <button type="button" class="c-button__load upload">クリア</button>
                </div>
            </form>
        </div><!-- ./l-modal__content -->

        <!-- 編集の場合のデータ削除ボタン -->
        @if ($mode == 'edit')
            <form id="" action="" method="post">
                @csrf
                @method('DELETE')
            </form>
            <div class="l-modal__trashButton" onclick="">
                <img src="{{asset('images/svg/trash.svg')}}" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
            </div>
        @endif
    </div><!-- ./l-modal inner -->
</div>
