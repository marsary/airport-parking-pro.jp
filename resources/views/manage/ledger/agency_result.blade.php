@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <!-- D-1-0にリンク -->
    <li class="l-breadcrumb__list"><a href="">帳票印刷</a></li>
    <li class="l-breadcrumb__list">代理店実績表</li>
  </ul>

  <div class="l-container__inner">
    <!-- 日付範囲指定 -->
    <div class="u-font--20 u-font--medium u-font--green u-mb05">日付範囲指定</div>
    <form action="" class="u-mb4">
      <div class="l-flex l-grid--gap2 u-mb2">
        <!-- 代理店番号 -->
        <label for="store_number" class="l-flex--column l-flex--item-start l-grid--gap05">代理店
          <input type="text" id="store_number" name="store_number" class="u-mb0"><!-- maxlength 未設定 -->
        </label>
        <!-- 入出庫日  -->
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
      <input type="submit" value="CSVをダウンロード" class="c-button__submit u-w-auto u-horizontal-auto">
    </form>

    <!-- マージン計算月範囲指定 -->
    <div class="u-font--20 u-font--medium u-font--green u-mb05">マージン計算月範囲指定</div>
    <form action="" class="u-mb4">
      <div class="l-flex l-grid--gap2 u-mb2">
        <!-- text 代理店番号 -->
        <label for="store_number" class="l-flex--column l-flex--item-start l-grid--gap05">代理店
          <input type="text" id="store_number" name="store_number" class="u-mb0"><!-- maxlength 未設定 -->
        </label>
        <!-- 入出庫日  -->
        <div class="l-flex--column l-flex--item-start l-grid--gap05">入庫日
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
        </div>
      </div>
      <input type="submit" value="CSVをダウンロード" class="c-button__submit u-w-auto u-horizontal-auto">
    </form>

    <ul class="u-font--md">
      <li>・実績は入庫日ペースです。入庫時のデータのみが対象になります。</li>
      <li>・年をまたぐことは考慮されておりません。</li>
      <li>・2009/03以前のデータは正しく計算できません。2009/04以降で指定してください。</li>
      <li>・未収、返金、後払いは含まれません。</li>
      <li>・<span class="note">レッドの入庫キャンセルは考慮していません。</span></li>
      <li>・<span class="note">消費税は10%として計算しています。</span></li>
      <li>・出力結果に一日利用、商品は含まれません。</li>
      <li>・<span class="note">現在、代理店マスタの「マージン支払区分」の設定は無視しています。</span></li>
      <li>・<span class="note">現在、代理店親子関係は無視しています。</span></li>
      <li>・日付範囲指定（上段）の場合、マージンサイクル月は無視されます。</li>
      <li>・web予約サーバにインボートするデータは、マージンサイクル月指定（下段）で取得して下さい。</li>
      <li>・代理店 ID
        <ul class="u-pl1">
          <li>・代理店IDを指定しない場合は全代理店が対象になります。</li>
          <li>・代理店IDの下3桁を省略した場合は 000 - 999 が対象になります。</li>
        </ul>
      </li>
      <li>・駐車料金
        <ul class="u-pl1">
          <li>・駐車料金に固定料金を税金は含まれません。また、割引券は適用済みの価格になります。</li>
          <li>・割引券の税抜き額は、割引額 +1.10 で計算し、<span class="note">小数点以下は切り上げています</span>。これは他のページにおける計算方法とは異なります。</li>
          <li>・支払区分 = 調整・チケット・ポイントは駐車料金として支払われたと仮定し、その分は差し引かれます。0円以下にはなりません。</li>
          <li>・クーポンは差し引かれません。</li>
        </ul>
      </li>
    </ul>
  </div><!-- l-container__inner -->
</main>

@endsection