@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">代理店実績表</li>
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
            <!-- 入庫日  -->
            <label for="entry_date" class="l-flex l-grid--gap05">入庫日
              <input type="date" id="entry_date" name="entry_date" placeholder="2021/01/01" class="u-mb0">
              ～
              <input type="date" id="exit_date" name="exit_date" placeholder="2021/12/31" class="u-mb0">
            </label>
          </div>
          <input type="submit" value="CSVをダウンロード" class="c-button__submit u-w-auto u-horizontal-auto">
        </form>

        <div class="u-font--20 u-font--medium u-font--green u-mb05">マージン計算月範囲指定</div>
        <form action="" class="u-mb4">
          <div class="l-flex l-grid--gap2 u-mb2">
            <!-- text 代理店番号 -->
            <label for="store_number" class="l-flex l-grid--gap05">代理店
              <input type="text" id="store_number_3" name="store_number_3" placeholder="1234" maxlength="4" class="u-mb0 u-w70">
              -
              <input type="text" id="store_number_4" name="store_number_4" placeholder="567" maxlength="3" class="u-mb0 u-w50">
            </label>
            <!-- マージン計算月  -->
            <label for="entry_date" class="l-flex l-grid--gap05">マージン計算月
              <!-- 年と月のみを分ける input -->
              <input type="text" id="margin_year" name="margin_year" placeholder="2021" maxlength="4" class="u-mb0 u-w50">
              年
              <input type="text" id="margin_month" name="margin_month" placeholder="01" maxlength="2" class="u-mb0 u-w40">
              月
            </label>
          </div>
          <input type="submit" value="CSVをダウンロード" class="c-button__submit u-w-auto u-horizontal-auto">
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