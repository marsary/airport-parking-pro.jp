<!-- B-1-1 受付入力 -->
@extends('layouts.member.authenticated')

@section('content')
@include('include.reserve.header_information')
@include('include.step', ['step' => "entry"])
<div class="p-user-input__inner--sm">
  <!-- 受付入力 -->
  <form action="option_user_select.php" method="POST">

    <!-- 詳細情報入力 -->
    <div class="p-user-input-auto-output__wrap l-flex--item-end">
      <!-- 入力エリア -->
      <div class="l-grid--col2-auto l-grid--cgap2">
        <div>
          <label for="maker">メーカー</label>
          <!-- 車メーカーのselect -->
          <div class="c-form-select-color">
            <select name="maker" id="maker">
              <option value="トヨタ">トヨタ</option>
              <option value="日産">日産</option>
              <option value="フォルクスワーゲン">フォルクスワーゲン</option>
              <option value="アウディ">メルセデス・ベンツ</option>
            </select>
          </div>
        </div>
        <div>
          <label for="car">車種</label>
          <div class="c-form-select-color">
            <select id="car" name="car">
              <option value="選択してください" selected disabled>選択してください</option>
              <option value="ダミーダミー">ダミーダミー</option>
            </select>
          </div>
        </div>
        <div>
          <label for="color">色</label>
          <div class="c-form-select-color">
            <select id="color" name="color">
              <option value="選択してください" selected disabled>選択してください</option>
              <option value="ダミーダミー">ダミーダミー</option>
            </select>
          </div>
        </div>
        <div>
          <label for="number">ナンバー（※4桁の数字）</label>
          <input type="text" id="number" name="number" maxlength="4" minlength="4" class="u-w-full-wide">
        </div>
        <div>
          <label for="arrival">到着便（例：JL200，NH300）</label>
          <input type="text" id="arrival" name="arrival" class="u-w-full-wide">
        </div>
        <div>
          <label for="date">到着日</label>
          <input type="date" id="date" name="date" class="u-w-full-wide u-mb025">
          <p class="text-center">到着日がお迎え日と異なる</p>
        </div>
        <div>
          <label for="people">ご利用人数</label>
          <input type="text" id="people" name="people">
        </div>
      </div><!-- 入力エリア -->

      <!-- 自動出力 -->
      <div class="p-user-input-auto-output__right u-mb1">
        <dl class="l-grid--col2 u-mb3">
          <dt>航空会社名</dt>
          <dd class="text-right">日本航空</dd>
          <!-- 福岡空港 成田空港 18:20 -->
          <dt>出発空港</dt>
          <dd class="text-right">福岡空港</dd>
          <dt>到着空港</dt>
          <dd class="text-right">成田空港</dd>
          <dt>到着予定時間</dt>
          <dd class="text-right">18:20</dd>
        </dl>
        <div>到着日がお迎え日と異なる</div>
      </div>
    </div>

    <!--  -->
    <div class="c-button-group__form u-mt3">
      <a id="returnButton" class="c-button__pagination--return">前のページに戻る</a>
      <button type="submit" class="c-button__pagination--next">次へ進む</button>
    </div>
  </form>
</div><!-- ./p-user-input__inner -->


@endsection
@push("scripts")
<script>
</script>
@endpush
@push('css')
<style>
</style>
@endpush
