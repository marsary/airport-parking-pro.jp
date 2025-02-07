@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">代理店別売上リスト</li>
      </ul>

      <div class="l-container__inner">
        <form action="">
          <div class="u-mb2">
            <div class="l-flex l-grid--gap2 u-mb1">
              <!-- text 代理店番号 4桁-4桁 -->
              <label for="store_number" class="l-flex l-grid--gap05">代理店番号
                <input type="text" id="store_number" name="store_number" placeholder="1234" maxlength="4" class="u-mb0 u-w70">
                -
                <input type="text" id="store_number_2" name="store_number_2" placeholder="567" maxlength="3" class="u-mb0 u-w50">
              </label>
              <!-- 入庫日  -->
              <label for="entry_date" class="l-flex l-grid--gap05">入庫日
                <input type="date" id="entry_date" name="entry_date" placeholder="例) 2021/01/01" class="u-mb0">
                ～
                <input type="date" id="exit_date" name="exit_date" placeholder="例) 2021/12/31" class="u-mb0">
              </label>
            </div>

            <!-- マイル チェックボタン -->
            <label>
              <input type="checkbox" name="mile" value="1">
              マイル順にソート
            </label>
          </div>

          <input type="submit" value="CSVをダウンロード" class="c-button__submit u-w-auto u-horizontal-auto">
        </form>
        <ul class="u-font--md">
          <li>・売上リストの計算方法は代理店実績表と同様です。</li>
          <li>・代理店実績表リストとは、1台単位の明細が表示される点のみ異なります。</li>
        </ul>
      </div><!-- l-container__inner -->
    </main>

@endsection