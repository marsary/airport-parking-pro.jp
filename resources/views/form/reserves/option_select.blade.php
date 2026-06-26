<!-- B-1-3 オプション選択 -->
@extends('layouts.form.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "option"])
<div class="p-user-input__inner--sm">
  <form action="{{route('form.reserves.option_select')}}" method="POST">
    @csrf
    <div class="p-user-input-auto-output__wrap u-mb3 u-pb3" style="display: block;width: 100%;padding: 10px;margin-bottom: 20px;">
    <!-- お客様選択予約　ラジオボタン -->
    <!-- 旅行保険への加入検討有無 -->
    <div class="p-user-input-optionSelect__wrap">
      <p>万が一の時にも安心、安全な<br class="is-none--md">旅行保険への加入を検討していますか？</p>
      <div class="p-user-input-optionSelect__radio">
        <label for="insurance_yes">
          <input type="radio" id="insurance_yes" name="insurance" value="1" class="c-button__radio--input" {{ old('insurance', $reserve->insurance) == 1 ? 'checked' : '' }}>はい
        </label>
        <label for="insurance_no">
          <input type="radio" id="insurance_no" name="insurance" value="0" class="c-button__radio--input" {{ old('insurance', $reserve->insurance) == 0 ? 'checked' : '' }}>いいえ
        </label>
      </div>
    </div>
    <div class="p-user-input-optionSelect__wrap">
      <!-- 当店自慢の洗車希望有無 -->
      <p class="">当店自慢の洗車を希望しますか？</p>
      <div class="p-user-input-optionSelect__radio">
        <label for="carwash_yes">
          <input type="radio" id="carwash_yes" name="carwash" value="1" class="c-button__radio--input" {{ old('carwash', $reserve->carwash) == 1 ? 'checked' : '' }}>はい
        </label>
        <label for="carwash_no">
          <input type="radio" id="carwash_no" name="carwash" value="0" class="c-button__radio--input" {{ old('carwash', $reserve->carwash) == 0 ? 'checked' : '' }}>いいえ
        </label>
      </div>
    </div>

    <!-- お得な情報などが届くメルマガを希望しますか？ -->
    <div class="p-user-input-optionSelect__wrap">
      <p class="">お得な情報などが届く<br class="is-none--md">メルマガを希望しますか？</p>
      <div class="p-user-input-optionSelect__radio">
        <label for="newsletter_yes">
          <input type="radio" id="newsletter_yes" name="newsletter" value="yes" class="c-button__radio--input" checked>はい
        </label>
        <label for="newsletter_no">
          <input type="radio" id="newsletter_no" name="newsletter" value="no" class="c-button__radio--input">いいえ
        </label>
      </div>
    </div>

    {{-- <div class="p-user-input-auto-output__wrap u-mb4">
      <div class="l-flex--start l-flex--item-end l-grid--gap05">
        <div>
          <label for="coupon_code">割引クーポン</label>
          <!-- クーポン -->
          <input type="text" id="coupon_code" name="coupon_code" class="u-mb0" value="{{old('coupon_code', $reserve->coupon_code)}}">
        </div>
      </div>
      <div class="p-user-input-optionSelect__wrap u-mb0">
        <!-- 当店自慢の洗車希望有無 -->
        <p class="">当店自慢の洗車をご希望されていますか？</p>
        <div class="p-user-input-optionSelect__radio">
          <label for="carwash_yes">
            <input type="radio" id="carwash_yes" name="carwash" value="yes" class="c-button__radio--input" required>希望する
          </label>
          <label for="carwash_no">
            <input type="radio" id="carwash_no" name="carwash" value="no" class="c-button__radio--input" required>希望しない
          </label>
        </div>
      </div>

      {{-- <div class="p-user-input-auto-output__wrap u-mb4">
        <div class="l-flex--start l-flex--item-end l-grid--gap05">
          <div>
            <label for="coupon_code">割引クーポン</label>
            <!-- クーポン -->
            <input type="text" id="coupon_code" name="coupon_code" class="u-mb0" value="{{old('coupon_code', $reserve->coupon_code)}}">
          </div>
          <button type="button" class="c-button__apply--green" id="coupon_code_btn">適用</button>
        </div>
        <!-- オプション選択項目自動出力 -->
        <div class="p-user-input-auto-output__right u-pl1" id="selected-coupon-info">
          @if ($reserve->coupon_code)
          @php
            $coupon = \Illuminate\Support\Arr::first($couponsMap, function($coupon) use($reserve) {
            return $coupon->code == $reserve->coupon_code;
            });
          @endphp
            <div class="c-button__remove remove_coupon item-container">
              <img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="coupon_remove">
              {{$coupon->name}}
            </div>
          @endif
        </div>
        <input type="hidden" id="coupon_ids" name="coupon_ids" value="{{old('coupon_ids', implode(',', $reserve->coupon_ids))}}">
      </div> --}}

      <!-- pager -->
      <div class="c-button-group__form u-mt3">
        <button type="button" id="returnButton" onclick="location.href='{{route('form.reserves.entry_car')}}';" class="c-button__pagination--return">前のページに戻る</button>
        <button type="submit" class="c-button__pagination--next">内容確認へ進む</button>
        {{-- <button type="button" class="c-button__pagination--next">事前決済に進む</button> --}}
        {{-- <img src="{{ asset('images/card_5brand.png') }}" height="16"> --}}
      </div>
    </div>
  </form>
</div>



@endsection
@push("scripts")
<script>
</script>
<!-- Enterキーで「次の入力欄に移動」する（Tabキーの代わり） -->
<script>
document.querySelectorAll('input').forEach(function(input) {
  // input, selectのみEnterで次の入力欄に移動。textareaは除外。
  const inputs = document.querySelectorAll('input, select');
  input.addEventListener('keydown', function(event) {
    // Enterキーが押された時の処理
    if (event.key === 'Enter') {
      // 【追加】日本語の変換中（確定のEnter）なら処理を抜ける
      if (event.isComposing || event.keyCode === 229) {
        return;
      }

      event.preventDefault(); // フォームの誤送信を防ぐ場合

      inputs.forEach((input, index) => {
        input.addEventListener('keydown', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault(); // 改行やSubmitを防止
            // 次の要素が存在すればフォーカスを移動
            if (inputs[index + 1]) {
              inputs[index + 1].focus();
            }
          }
        });
      });
    }
  });
});
</script>
@endpush
@push('css')
<style>
</style>
@endpush
