<!-- D-1-5 -->
<!-- 代理店実績表 -->
@extends('layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">代理店実績表及び代理店別売上リスト</li>
      </ul>

      <div class="l-container__inner">
        <div class="u-font--20 u-font--medium u-font--green u-mb05">日付範囲指定</div>
        <form action="" class="u-mb4">
          <div class="l-flex l-grid--gap2 u-mb2">
            <!-- text 代理店番号 -->
            <label for="store_number" class="l-flex l-grid--gap05">代理店
              <input type="text" id="store_number" name="store_number" placeholder="1234" maxlength="4" class="u-mb0 u-w70">
              -
              <input type="text" id="store_number_2" name="store_number_2" placeholder="567" maxlength="3" class="u-mb0 u-w50">
            </label>
            <!-- 対象月  -->
            <label for="entry_date" class="l-flex l-grid--gap05">対象月
              <!-- 年と月のみを分ける input -->
              <input type="text" id="margin_year" name="margin_year" placeholder="2021" maxlength="4" class="u-mb0 u-w50">
              年
              <input type="text" id="margin_month" name="margin_month" placeholder="01" maxlength="2" class="u-mb0 u-w40">
              月
            </label>
          </div>
          <input type="submit" value="代理店別売上リスト" class="c-button__submit u-w-auto u-horizontal-auto">
          <input type="submit" value="代理店実績表" class="c-button__submit u-w-auto u-horizontal-auto">
          <input type="submit" value="マージン計算月" class="c-button__submit u-w-auto u-horizontal-auto">
        </form>

        <ul class="u-font--md">
          <li>・実績は入庫日べースです。入庫時のデータのみが対象になります。</li>
          <li>・年をまたぐことは考慮されておりません。</li>
          <li>・2009/03以前のデータは正しく計算できません。2009/04以降で指定してぐビさい。</li>
          <li>・未収、返金、後払いは含まれません。</li>
          <li>・レッドの入庫キャンセルは考慮していません。</li>
        </ul>
      </div><!-- l-container__inner -->
    </main>

@endsection
@push('css')
<style>
  .u-w-auto, input.u-w-auto {
    width: 200px !important;
    display: inline-block !important;
    margin-right: 50px !important;
  }
</style>
@endpush

