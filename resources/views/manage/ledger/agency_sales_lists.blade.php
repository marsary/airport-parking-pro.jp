@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <!-- D-1-0にリンク -->
    <li class="l-breadcrumb__list"><a href="">帳票印刷</a></li>
    <li class="l-breadcrumb__list">代理店別売上リスト</li>
  </ul>

  <div class="l-container__inner">
    <form action="">
      <div class="u-mb2">
        <div class="l-flex l-grid--gap2 u-mb1">
          <!-- 代理店番号 桁数指示なし -->
          <label for="store_number" class="l-flex--column l-flex--item-start l-grid--gap05">代理店番号
            <input type="text" id="store_number" name="store_number" class="u-mb0">
          </label>
          <!-- 入庫日  -->
          <label for="date_of_entry_exit" class="l-flex--column l-flex--item-start l-grid--gap05">入庫日
            <div class="l-flex l-grid--gap1">
              <!-- 年 -->
              <div class="l-flex l-grid--gap05">
                <div class="c-form-select-color u-mb0">
                  <select name="" id="" class="u-mb0 text-center u-w140">
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                  </select>
                </div>
                年
                <!-- 月 -->
                <div class="c-form-select-color u-mb0">
                  <select name="" id="" class="u-mb0 text-center">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                </div>
                月
              </div>
              ～
              <div class="l-flex l-grid--gap05">
                <!-- 年 -->
                <div class="c-form-select-color u-mb0">
                  <select name="" id="" class="u-mb0 text-center u-w140">
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                  </select>
                </div>
                年
                <!-- 月 -->
                <div class="c-form-select-color u-mb0">
                  <select name="" id="" class="u-mb0 text-center">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                </div>
                月
              </div>
            </div>
          </label>
        </div>

        <!-- マイル チェックボタン -->
        <div class="l-flex--column l-flex--item-start l-grid--gap05">
          マイル
          <label>
            <input type="checkbox" name="mile" value="1" class="u-mb0">
            マイル順にソート
          </label>
        </div>
      </div>

      <input type="submit" value="CSVをダウンロード" class="c-button__submit u-w-auto u-horizontal-auto">
      <ul class="u-font--md">
        <li>・売上リストの計算方法は代理店実績表と同様です。</li>
        <li>・代理店実績表リストとは、1台単位の明細が表示される点のみ異なります。</li>
      </ul>
    </form>
  </div><!-- l-container__inner -->
</main>
@endsection