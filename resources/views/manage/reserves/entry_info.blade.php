<!-- 受付入力1（受付予約・スタッフが入力） -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main l-container__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">予約管理TOP</li>
    <li class="l-breadcrumb__list">受付入力</li>
  </ul>

  <div class="l-container__inner">
    <form action="/admin/reservation_confirm.php" method="POST">
      <!-- BOX1 顧客検索 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">顧客検索</div>

        <div class="l-flex--start l-flex--item-end l-grid--gap2">
          <div class="l-grid l-grid--col2 l-grid--gap2">
            <div>
              <label for="tel">電話番号</label>
              <input type="tel" id="tel" name="tel" class="u-mb0">
            </div>
            <div>
              <label for="kana">ふりがな</label>
              <input type="text" id="kana" name="kana" class="u-mb0">
            </div>
          </div>

          <button type="button" class="c-button--light-deep-gray u-w160 is-block">検索</button>
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
          <div>
            <label for="zip">郵便番号</label>
            <input type="text" id="zip" name="zip">
          </div>
          <div>
            <label for="email">メールアドレス</label>
            <input type="text" id="email" name="email" class="c-form-input--w100">
          </div>
          <!-- 領収書のあて名 -->
          <div>
            <label for="receipt">領収書のあて名</label>
            <input type="text" id="receipt" name="receipt" class="c-form-input--w100 u-mb0">
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">
          <div>顧客コード: </div>
          <div class="text-right">1234567890</div>
          <div>予約コード: </div>
          <div class="text-right">1234567890</div>
          <div>利用回数: </div>
          <div class="text-right">10</div>
          <div>会員ランク: </div>
          <div class="text-right">S</div>
          <div>トラブル: </div>
          <div class="text-right">あり</div>
          <div>ラベル3: </div>
          <div class="text-right">ラベル3</div>
          <div>ラベル4: </div>
          <div class="text-right">ラベル4</div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX3 車両情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">車両情報</div>

        <div>
          <div class="l-grid--col2 l-grid--gap1">
            <div>
              <label for="maker">メーカー</label>
              <input type="text" id="maker" name="maker" class="c-form-input--w100">
            </div>
            <div>
              <label for="car">車種</label>
              <input type="text" id="car" name="car" class="c-form-input--w100">
            </div>
            <div>
              <label for="color">色</label>
              <input type="text" id="color" name="color" class="c-form-input--w100">
            </div>
            <div>
              <label for="number">ナンバー（※４桁の数字）</label>
              <input type="text" id="number" name="number" class="c-form-input--w100" maxlength="4" minlength="4">
            </div>
            <div>
              <!-- セレクト（取扱注意メモ） -->
              <label for="memo">取扱注意メモ</label>
              <div class="c-form-select-color">
                <select name="memo" id="memo" class="u-mb0" multiple>
                  <option value="1">MT車</option>
                  <option value="2">取扱注意メモ2</option>
                  <option value="3">取扱注意メモ3</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">
          <div>車両サイズ: </div>
          <div class="text-right">1234567890</div>
          <div>予約コード: </div>
          <div class="text-right">1234567890</div>
          <div>利用回数: </div>
          <div class="text-right">10</div>
          <div>会員ランク: </div>
          <div class="text-right">S</div>
          <div>トラブル: </div>
          <div class="text-right">あり</div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX4 予約情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">予約情報</div>
        <div class="l-grid--col2 l-grid--gap1">
          <div>
            <!-- ご利用人数（ご本人含む） -->
            <label for="people">ご利用人数（ご本人含む）</label>
            <input type="number" id="people" name="people" class="c-form-input--w100">
          </div>
          <div>
            <!-- 到着便 -->
            <label for="flight">到着便</label>
            <input type="text" id="flight" name="flight" class="c-form-input--w100">
          </div>
          <div>
            <!-- 到着日 -->
            <label for="arrival">到着日</label>
            <input type="date" id="arrival" name="arrival" class="c-form-input--w100 u-mb05">
            <div class="c-label">到着日とお迎え日が異なる</div>
          </div>
          <div>
            <!-- 到着時間 -->
            <label for="arrival-time">到着時間</label>
            <input type="time" id="arrival-time" name="arrival-time" class="c-form-input--w100">
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">
          <div>航空会社名: </div>
          <div class="text-right">1234567890</div>
          <div>出発空港: </div>
          <div class="text-right">1234567890</div>
          <div>到着空港: </div>
          <div class="text-right">10</div>
          <div>到着予定時間: </div>
          <div class="text-right">あり</div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX5 オプション選択 -->
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
      <div class="p-input-user-option__box">
        <div class="u-font--20 u-font--medium u-font--green">予約メモ</div>
        <textarea name="" id="" cols="30" rows="5"></textarea>
      </div>

      <div class="l-flex--center l-grid--gap2">
        <button type="button" class="c-button__pagination--return">前のページに戻る</button>
        <button type="submit" class="c-button__pagination--next">予約内容確認</button>
      </div>

    </form>

  </div><!-- /.l-container__inner -->
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
