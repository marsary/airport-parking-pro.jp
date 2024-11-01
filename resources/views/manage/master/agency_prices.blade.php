<!-- I-4 代理店 料金設定 -->
@extends('layouts.manage.authenticated')

@section('content')    <main class="l-wrap__main l-container__main">
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">設定</li>
        <li class="l-breadcrumb__list">代理店料金設定</li>
        </li>
      </ul>

      <div class="l-container__inner">
        <h2 class="c-title__lv2 l-flex--sb">代理店検索<span class="close_button c-button__close">閉じる</span></h2>

        <div class="is-active">
          <form action="" class="u-mb1">
            <div>
              <div class="l-grid--col3 l-grid--cgap1">
                <div>
                  <label for="agency_code" class="u-font--md">代理店コード</label>
                  <input type="text" id="agency_code" name="agency_code" class="u-w-full-wide">
                </div>
                <div>
                  <label for="company_name" class="u-font--md">社名</label>
                  <input type="text" id="company_name" name="company_name" class="u-w-full-wide">
                </div>
                <div>
                  <label for="tel" class="u-font--md">電話番号</label>
                  <input type="text" id="tel" name="tel" class="u-w-full-wide">
                </div>
              </div>
              <div>
                <label for="search_keywords" class="u-font--md">検索用キーワード</label>
                <input type="text" id="search_keywords" name="search_keywords" class="u-w-full-wide u-mb0">
              </div>
              <button type="submit" class="c-button__register w-160 u-mt2 u-horizontal-auto">検索</button>
            </div>
            <div></div>
          </form>

          <h2>検索結果</h2>
          <ul class="l-flex l-flex--wrap l-grid--cgap2 l-grid--rgap1">
            <li class="pointer link-text">HIS なんば支店</li>
            <li class="pointer link-text">HIS 梅田支店</li>
            <li class="pointer link-text">HIS 千里中央支店</li>
            <li class="pointer link-text">JTB 甲東支店</li>
            <li class="pointer link-text">JTB 尼崎支店</li>
            <li class="pointer link-text">JTB 神崎支店</li>
            <li class="pointer link-text">JTB 住之江支店</li>
            <li class="pointer link-text">近畿ツーリスト 広島支店</li>
            <li class="pointer link-text">近畿ツーリスト 広島支店</li>
          </ul>
          <!-- 装飾 -->
          <div class="u-border--bottom u-pt2 u-mb2"></div>
        </div>

        <!-- 中段　料金表一覧 -->
        <div class="u-mb1">
          <h2 class="c-title__lv2 l-flex--sb">登録済み 代理店 適用料金表一覧<span class="close_button c-button__close">閉じる</span></h2>
          <!-- 検索フォーム -->
          <div class="is-active">
            <table class="l-table-list u-mb2">
              <thead>
                <tr class="l-table-list__head l-table-list--scroll__head">
                  <th><div class="c-button-sort">代理店名</div></th>
                  <th class="u-w240"><div class="c-button-sort --desc">適用期間</div></th>
                  <th>メモ</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody class="l-table-list__body">
                <tr>
                  <td>HIS なんば支店</td>
                  <td>2021/01/01～2021/12/31</td>
                  <td>メモメモメモ</td>
                  <td>
                    <button type="button" class="c-button__edit button_select">編集</button>
                  </td>
                </tr>
                <tr>
                  <td>HIS なんば支店</td>
                  <td>2021/01/01～2021/12/31</td>
                  <td>メモメモメモ</td>
                  <td>
                    <button type="button" class="c-button__edit button_select">編集</button>
                  </td>
                </tr>
                <tr>
                  <td>HIS なんば支店</td>
                  <td>2021/01/01～2021/12/31</td>
                  <td>メモメモメモ</td>
                  <td>
                    <button type="button" class="c-button__edit button_select">編集</button>
                  </td>
                </tr>
              </tbody>
            </table>
            <button type="" class="button_select c-button__register u-mb4 u-horizontal-auto">新規登録</button>
          </div>
        </div>
      </div>

    </main><!-- /.l-container__main -->
  </div><!-- /.l-wrap -->

  <!-- ▼閉じるボタン -->
  <script src="{{ asset('js/close_button_toggle.js') }}"></script>

  <!-- ソート -->
  <script src="../js/tableHeaderSort.js"></script>

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
            <div class="c-title__modal--lv3">代理店料金設定編集</div>
            <div class="">
              <div class="l-grid--col2 l-grid--gap2">
                <div>
                  <label for="price_list" class="u-font--md">料金表選択</label>
                  <div class="c-form-select-wrap">
                    <select name="price_list" id="price_list">
                      <option value="" disabled selected>選択してください</option>
                      <option value="price_list_New">新規登録</option>
                      <option value="price_list1">2024年1月1日～2024年3月31日</option>
                      <option value="price_list2">2024年4月28日～2024年5月8日</option>
                    </select>
                  </div>
                </div>

                <!-- 料金の適用期間 -->
                <div>
                  <p class="u-mb025 u-font--md">料金の適用期間</p>
                  <div class="l-flex l-grid--gap05">
                    <input type="date" id="start_date" name="start_date" class="u-w-full-wide">
                    <p class="u-mb1">～</p>
                    <input type="date" id="end_date" name="end_date" class="u-w-full-wide">
                  </div>
                </div>
              </div>

              <!-- MEMO -->
              <label for="memo" class="u-font--md">メモ</label>
              <textarea name="memo" id="memo" rows="1" class="u-w-full-wide"></textarea>

              <!-- 1日目～15日まで料金を入力するinput -->
              <div class="l-grid--pricing l-grid--input">
                <div>
                  <label for="day1">1日目</label>
                  <input type="text" id="day1" name="day1">
                </div>
                <div>
                  <label for="day2">2日目</label>
                  <input type="text" id="day2" name="day2">
                </div>
                <div>
                  <label for="day3">3日目</label>
                  <input type="text" id="day3" name="day3">
                </div>
                <div>
                  <label for="day4">4日目</label>
                  <input type="text" id="day4" name="day4">
                </div>
                <div>
                  <label for="day5">5日目</label>
                  <input type="text" id="day5" name="day5">
                </div>
                <div>
                  <label for="day6">6日目</label>
                  <input type="text" id="day6" name="day6">
                </div>
                <div>
                  <label for="day7">7日目</label>
                  <input type="text" id="day7" name="day7">
                </div>
                <div>
                  <label for="day8">8日目</label>
                  <input type="text" id="day8" name="day8">
                </div>
                <div>
                  <label for="day9">9日目</label>
                  <input type="text" id="day9" name="day9">
                </div>
                <div>
                  <label for="day10">10日目</label>
                  <input type="text" id="day10" name="day10">
                </div>
                <div>
                  <label for="day11">11日目</label>
                  <input type="text" id="day11" name="day11">
                </div>
                <div>
                  <label for="day12">12日目</label>
                  <input type="text" id="day12" name="day12">
                </div>
                <div>
                  <label for="day13">13日目</label>
                  <input type="text" id="day13" name="day13">
                </div>
                <div>
                  <label for="day14">14日目</label>
                  <input type="text" id="day14" name="day14">
                </div>
                <div>
                  <label for="day15">15日目</label>
                  <input type="text" id="day15" name="day15">
                </div>
                <div>
                  <label for="day16">16日目以降</label>
                  <input type="text" id="day16" name="day16">
                </div>
                <div>
                  <label for="extensionFee">延長料</label>
                  <input type="text" id="extensionFee" name="extensionFee">
                </div>
                <div class="--last">
                  <label for="basicFee">基本料金</label>
                  <input type="text" id="basicFee" name="basicFee">
                </div>
              </div>
            </div>
          </div>
          <!-- 閉じる・追加ボタン -->
          <div class="l-flex--center l-grid--gap1 u-mt2 u-mb4">
            <button type="submit" class="c-button__submit--dark-gray modal_optionClose">閉じる</button>
            <button type="submit" class="c-button__submit">登録</button>
          </div>
        </form>
      </div><!-- ./l-modal__content -->

      <!-- 編集の場合のデータ削除ボタン -->
      <div class="l-modal__trashButton">
        <img src="{{asset('images/svg/trash.svg')}}" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
      </div>
    </div><!-- ./l-modal inner -->
  </div>

  <!-- モーダル -->
  <script src="{{ asset('js/modalOption.js') }}"></script>
@endsection
