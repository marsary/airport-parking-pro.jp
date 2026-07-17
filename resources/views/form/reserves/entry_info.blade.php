<!-- B-1-1 受付入力 -->
@extends('layouts.form.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "user-info"])
@include('include.messages.errors')
<div class="p-user-input__inner--sm">
  <!-- 受付入力 -->
  <form action="{{route('form.reserves.post_entry_info')}}" method="POST">
    @csrf
    <!-- 基本情報 -->
    <div class="c-form__admin--col2 l-flex--items-start--md">
      <div class="u-w-full-wide">
        <label for="name">氏名<span class="c-label--required">※必須</span><br class="is-none--md" /><span class="u-font--xs">（間にスペースを入れて下さい。例:空港　太郎）</span></label><label for="name"></label>
        <input type="text" id="name" name="name" class="u-w-full-wide" value="{{old('name', $reserve->name)}}" placeholder="空港　太郎">
      </div>
      <div class="u-w-full-wide">
        <label for="kana">ふりがな<span class="c-label--required">※必須</span><br class="is-none--md" /><span class="u-font--xs">（間にスペースを入れて下さい。例:くうこう　たろう）</span></label>
        <input type="text" id="kana" name="kana" class="u-w-full-wide" value="{{old('kana', $reserve->kana)}}" placeholder="くうこう　たろう">
      </div>
    </div>

    <div class="c-form__admin--half">
      <label for="tel">携帯番号<span class="c-label--required">※必須</span></label>
      <input type="tel" id="tel" name="tel" class="u-w-full-wide" value="{{old('tel', $reserve->tel)}}" placeholder="000-1234-5678">
    </div>

    <!-- 郵便番号・メールアドレス・領収書の名前・備考 -->
    <div class="c-form__admin--half">
      <label for="zip">郵便番号（ハイフンなし）</label>
      <input type="text" id="zip" name="zip" class="u-w-full-wide" value="{{old('zip', $reserve->zip)}}" placeholder="0000000" pattern="\d{7}" title="郵便番号は7桁の数字で入力してください。">
    </div>
    
    <div class="c-form__admin--col2 l-flex--items-start--mdd">
      <div>
        <label for="email">メールアドレス<span class="c-label--required">※必須</span></label>
        <input type="email" id="email" name="email" class="u-w-full-wide" placeholder="example@example.com" value="{{old('email', $reserve->email)}}">
      </div>
      {{-- <div>
        <label for="receipt_address">領収書の名前</label>
        <input type="text" id="receipt_address" name="receipt_address" class="u-w-full-wide" value="{{old('receipt_address', $reserve->receipt_address)}}">
      </div> --}}
    </div>

    <label for="note" style="display:none;">備考※領収書の宛名が必要な場合はこちらに記入ください</label>
    <textarea name="remarks" id="remarks" cols="50" rows="3" class="u-w-full-wide" style="display:none;">{{old('remarks', $reserve->remarks)}}</textarea>

    <div class="l-flex--center l-grid--gap1 u-mt3">
      <button type="button" class="c-button__pagination--return" onclick="location.href='{{route('form.reserves.entry_date')}}';">入出庫日の選択に戻る</button>
      <button type="button" onclick="submit();" class="c-button__pagination--next">次へ進む</button>
    </div>
  </form>
</div><!-- ./p-user-input__inner -->


@endsection
@push("scripts")
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
