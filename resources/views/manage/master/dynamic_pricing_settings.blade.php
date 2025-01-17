<!-- ダイナミックプライシング料金適用設定 -->
@extends('layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main l-container__main">
    <main class="l-wrap__main l-container__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">設定</li>
        <li class="l-breadcrumb__list">料金適用設定 ＋ ダイナミックプライシング適用台数（100％表記）</li>
      </ul>

      <div class="l-container__inner">
        <!-- PDFを参照してください。 -->
        <div class="l-flex--center l-grid--gap1 u-mb4">
          <div class="c-button__prev"></div>
          <div class="u-w-full-wide text-center">PDFを参照してください。</div>
          <div class="c-button__next"></div>
        </div>

        <div class="l-flex--center l-grid--cgap1">
          <button class="c-button__register c-button__submit c-button--deep-gray">クリア</button>
          <button type="submit" class="c-button__register c-button__submit--green button_select">一括編集</button>
          <button type="submit" class="c-button__register c-button__submit">登録</button>
        </div>
      </div>
    </main><!-- /.l-container__main -->
  </div><!-- /.l-wrap -->

    <!-- modal i-1と同じコード -->
    <div id="modalAreaOption" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal--trash">
      <div class="l-modal__head">編集</div>
      <!-- close button -->
      <div class="l-modal__close modal_optionClose">×</div>
      <div class="l-modal__content">
        <form action="" method="POST" class="l-flex--column l-flex--sb u-w-full">
          <!-- 入力フォーム -->
          <div class="u-w-full-wide">
            <div class="c-title__modal--lv3">ダイナミックプライシング台数一括設定</div>

              <div class="l-flex--center l-grid--col2 l-grid--gap2">
                <!-- 料金の適用期間 -->
                <div class="l-grid--dynamic-pricing-modal">
                  <label for="start_date" class="u-mb025 u-font--md">開始日</label>
                  <div></div>
                  <label for="end_date" class="u-mb025 u-font--md">終了日</label>
                  <input type="date" id="start_date" name="start_date" class="u-w-full-wide">
                  <p class="u-mb1">～</p>
                  <input type="date" id="end_date" name="end_date" class="u-w-full-wide">
                </div>
                <!-- 台数設定 -->
                <div>
                  <label for="" class="u-font--md">台数</label>
                  <div class="">
                    <input type="text" class="u-mr05 u-w120">台
                  </div>
                </div>
              </div>

          </div>
          <!-- button -->
          <div class="l-flex--center l-grid--gap1 u-mt2 u-mb4">
            <button type="submit" class="c-button__submit--dark-gray modal_optionClose">閉じる</button>
            <button type="submit" class="c-button__submit">登録</button>
          </div>
        </form>
      </div><!-- ./l-modal__content -->
    </div><!-- ./l-modal inner -->
  </div>

  <!-- モーダル -->
  <script src="{{ asset('js/modalOption.js') }}"></script>
@endsection
