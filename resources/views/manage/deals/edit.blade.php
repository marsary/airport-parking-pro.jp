<!-- B-3-4 予約編集 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">予約編集</li>
  </ul>
  <div class="l-container__inner">
    <form action="" id="form" method="POST" class="l-container__button-fixed-parent">
      <!-- BOX1 顧客検索 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">予約情報</div>

        <div class="l-grid l-grid--col2 l-grid--cgap2">
          <div>
            <label for="">入庫日</label>
            <input type="" id="" name="" class="u-w-full-wide">
          </div>
          <div>
            <label for="">入庫時間</label>
            <input type="text" id="" name="" class="u-w-full-wide">
          </div>
          <div>
            <label for="">出庫日</label>
            <input type="text" id="" name="" class="u-w-full-wide">
          </div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX2 顧客情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">顧客情報</div>

        <div>
          <div>
            <label for="name">氏名</label>
            <input type="text" id="name" name="name" class="c-form-input--w100">
          </div>
          <div class="l-grid--col2-auto l-grid--cgap1">
            <div>
              <label for="kana">ふりがな</label>
              <input type="text" id="kana" name="kana" class="c-form-input--w100">
            </div>
            <div>
              <label for="zip">郵便番号</label>
              <input type="text" id="zip" name="zip">
            </div>
            <div>
              <label for="tel">携帯番号</label>
              <input type="tel" id="tel" name="tel" class="c-form-input--w100">
            </div>
            <div>
              <label for="email">メールアドレス</label>
              <input type="text" id="email" name="email" class="c-form-input--w100">
            </div>
          </div>
          <!-- 領収書のあて名 -->
          <div>
            <label for="receipt">領収書のあて名</label>
            <input type="text" id="receipt" name="receipt" class="c-form-input--w100 u-mb0">
          </div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- 到着情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">到着予定</div>

        <div>
          <div>
            <label for="arrival">到着予定日</label>
            <input type="date" id="arrival" name="arrival" class="c-form-input--w100 u-mb05">
          </div>
          <div>
            <label for="arrival-time">到着予定時間</label>
            <input type="time" id="arrival-time" name="arrival-time" class="c-form-input--w100">
          </div>
          <div>
            <label for="flight">到着便</label>
            <input type="text" id="flight" name="flight" class="c-form-input--w100">
          </div>
        </div>
      </div>

      <!-- BOX3 車両情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">車両情報</div>

        <div>
          <div>
            <div class="l-grid--col2 l-grid--gap1">
              <div>
                <label for="maker">メーカー</label>
                <div class="c-form-select-color">
                  <select name="maker" id="maker" class="u-mb0">
                    <option value="1">メーカー1</option>
                    <option value="2">メーカー2</option>
                    <option value="3">メーカー3</option>
                  </select>
                </div>
              </div>
              <div>
                <!-- 車種 -->
                <label for="car">車種</label>
                <div class="c-form-select-color">
                  <select name="car" id="car" class="u-mb0">
                    <option value="1">車種1</option>
                    <option value="2">車種2</option>
                    <option value="3">車種3</option>
                  </select>
                </div>
              </div>
              <div>
                <label for="number">ナンバー（※４桁の数字）</label>
                <input type="text" id="number" name="number" class="c-form-input--w100" maxlength="4" minlength="4">
              </div>
              <div>
                <!-- 色 -->
                <label for="color">色</label>
                <input type="text" id="color" name="color" class="c-form-input--w100">
              </div>
              <div>
                <label for="people">ご利用人数（ご本人含む）</label>
                <input type="number" id="people" name="people" class="c-form-input--w100">
              </div>
            </div>
            <div>
              <!-- セレクト（取扱注意メモ） -->
              <label for="memo">取扱注意メモ</label>
              <div class="c-form-select-color">
                <select name="memo" id="memo" class="u-mb0">
                  <option value="1">MT車</option>
                  <option value="2">取扱注意メモ2</option>
                  <option value="3">取扱注意メモ3</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">

        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- オプション選択 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">オプション選択</div>

        <div class="p-input-user-option__select c-button_optionSelect">

          <!-- select ボタン -->
          <div class="l-grid--col3 l-grid--gap1">
            <div class="c-button__select--gray button_select">洗車</div>
            <div class="c-button__select--gray button_select">メンテナンス</div>
            <div class="c-button__select--gray button_select">保険</div>
            <div class="c-button__select--gray button_select">回数券</div>
            <div class="c-button__select--gray button_select">物販</div>
            <div class="c-button__select--gray button_select">その他</div>
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">
          <div>
            <div class="c-button__remove item-container"><img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">手洗いWAX洗車 ¥2,500</div>
            <div class="c-button__remove item-container"><img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">iPhone充電ケーブル ¥1,200</div>
          </div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX6 予約メモ -->
      <div class="p-input-user-option__box u-border--none">
        <div class="u-font--20 u-font--medium u-font--green">顧客メモ</div>
        <textarea name="" id="" cols="30" rows="3"></textarea>
        <div class="u-font--20 u-font--medium u-font--green">予約メモ</div>
        <textarea name="" id="" cols="30" rows="3"></textarea>
        <div class="u-font--20 u-font--medium u-font--green">受付メモ</div>
        <textarea name="" id="" cols="30" rows="3"></textarea>
      </div>
    </form>

  </div><!-- /.l-container__inner -->

  <div class="l-container__button-fixed">
    <div class="c-button-group__form">
      <button type="button" class="c-button__clear">キャンセル</button>
      <button type="submit" form="from" class="c-button__submit">予約内容を更新</button>
    </div>
  </div>

</main><!-- /.l-container__main -->

<!-- オプションをクリックしたら出てくるmodal -->
@include('include.option.option')

@endsection
@push("scripts")
<script src="{{ asset('js/modalOption.js') }}"></script>
<script src="{{ asset('js/removeButton.js') }}"></script>
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush
