<!-- G-8 商品設定 -->
<!DOCTYPE html>
<html lang="ja">
<?php include_once '../include/head.php'; ?>

<body>
  <div class="l-wrap">
    <?php include_once '../include/header.php'; ?>

    <?php include_once '../include/nav.php'; ?>

    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">商品設定</li>
      </ul>

      <div class="l-container__inner">
        <h2 class="c-title__lv2 l-flex--sb u-w-full-wide">新規登録 および 検索<span class="close_button c-button__close">閉じる</span></h2>
        <form method="POST" action="" class="u-mb3 is-active">         
            <div class="l-grid--col2-1fr_auto l-grid--end l-grid--gap1 u-mb2">
              <div class="l-grid--col2 l-grid--gap1">
                <div>
                  <!-- 商品名 -->
                  <label for="product_name" class="u-font--md">商品名</label>
                  <input type="text" name="product_name" id="product_name" class="u-w-full-wide u-mb0">
                </div>
                <div>
                  <!-- カテゴリーselect -->
                  <label for="category" class="u-font--md">カテゴリー</label>
                  <div class="c-form-select-color u-mb0">
                    <select name="category" id="category">
                      <option value="" disabled selected>選択してください</option>
                      <option value="category1">カテゴリ1</option>
                      <option value="category2">カテゴリ2</option>
                      <option value="category3">カテゴリ3</option>
                    </select>
                  </div>
                </div>
              </div>
              <button class="c-button__register --gray u-mb0">検索</button>
          </div>

          <div class="l-flex l-flex--center l-grid--gap1">
            <button type="button" class="c-button__register button_select">新規登録</button>
          </div>
        </form>

        <!-- 登録済み商品一覧 -->
        <h2 class=" c-title__lv2">登録済み商品一覧</h2>
        <table class="l-table-list">
          <thead class="l-table-list__head l-table-list__head--sort l-table-list--scroll-vertical__head">
            <tr>
              <th><div class="c-button-sort --asc">商品名</div></th>
              <th><div class="c-button-sort">略称</div></th>
              <th><div class="c-button-sort">商品カテゴリ</div></th>
              <th><div class="c-button-sort">税区分</div></th>
              <th><div class="c-button-sort">金額（税抜）</div></th>
              <th>メモ</th>
              <th></th>
            </tr>
          </thead>

          <tbody class="l-table-list__body">
            <tr>
              <td>洗車</td>
              <td>S洗</td>
              <td>洗車</td>
              <td class="text-right">10%</td>
              <td class="text-right">20,000円</td>
              <td class="c-balloon-memo">
                <button type="button" class="button_open c-button__memo">メモ</button>
                <!-- 吹き出し -->
                <div class="c-balloon-memo__box">
                  <div class="c-balloon-memo__text">メモ内容が入ります。メモ内容が入ります。
                    <div class="button_close c-balloon-memo__close">閉じる</div>
                  </div>
                </div>
              </td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>
            <tr>
              <td>洗車</td>
              <td>S洗</td>
              <td>洗車</td>
              <td class="text-right">10%</td>
              <td class="text-right">00,000円</td>
              <td class="c-balloon-memo">
                <button type="button" class="button_open c-button__memo">メモ</button>
                <!-- 吹き出し -->
                <div class="c-balloon-memo__box">
                  <div class="c-balloon-memo__text">メモ内容が入ります。メモ内容が入ります。
                    <div class="button_close c-balloon-memo__close">閉じる</div>
                  </div>
                </div>
              </td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>
            <tr>
              <td>洗車</td>
              <td>S洗</td>
              <td>洗車</td>
              <td class="text-right">10%</td>
              <td class="text-right">00,000円</td>
              <td class="c-balloon-memo text-center">
                <button type="button" class="button_open c-button__memo">メモ</button>
                <!-- 吹き出し -->
                <div class="c-balloon-memo__box">
                  <div class="c-balloon-memo__text">メモ内容が入ります。メモ内容が入ります。
                    <div class="button_close c-balloon-memo__close">閉じる</div>
                  </div>
                </div>
              </td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>
            <tr>
              <td>洗車</td>
              <td>S洗</td>
              <td>洗車</td>
              <td class="text-right">10%</td>
              <td class="text-right">00,000円</td>
              <td class="c-balloon-memo text-center">
                <button type="button" class="button_open c-button__memo">メモ</button>
                <!-- 吹き出し -->
                <div class="c-balloon-memo__box">
                  <div class="c-balloon-memo__text">メモ内容が入ります。メモ内容が入ります。
                    <div class="button_close c-balloon-memo__close">閉じる</div>
                  </div>
                </div>
              </td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>
            <tr>
              <td>洗車</td>
              <td>S洗</td>
              <td>洗車</td>
              <td class="text-right">10%</td>
              <td class="text-right">00,000円</td>
              <td class="c-balloon-memo text-center">
                <button type="button" class="button_open c-button__memo">メモ</button>
                <!-- 吹き出し -->
                <div class="c-balloon-memo__box">
                  <div class="c-balloon-memo__text">メモ内容が入ります。メモ内容が入ります。
                    <div class="button_close c-balloon-memo__close">閉じる</div>
                  </div>
                </div>
              </td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>
            <tr>
              <td>洗車</td>
              <td>S洗</td>
              <td>洗車</td>
              <td class="text-right">10%</td>
              <td class="text-right">00,000円</td>
              <td class="c-balloon-memo text-center">
                <button type="button" class="button_open c-button__memo">メモ</button>
                <!-- 吹き出し -->
                <div class="c-balloon-memo__box c-balloon-memo--master__box">
                  <div class="c-balloon-memo--master__text">メモ内容が入ります。メモ内容が入ります。
                    <div class="button_close c-balloon-memo__close">閉じる</div>
                  </div>
                </div>
              </td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>
          </tbody>
        </table>
      </div><!-- ./l-container__inner -->
    </main><!-- /.l-container__main -->
  </div><!-- /.l-container__wrap -->

  <!-- 「編集」をクリックしたら出てくるmodal -->
  <div id="modalAreaOption" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal--trash">
      <div class="l-modal__head">編集</div>
      <!-- close button -->
      <div class="l-modal__close modal_optionClose">×</div>
      <div class="l-modal__content">
        <form class="l-flex--column l-flex--column l-flex--sb u-w-full">
          <div class="u-w-full-wide">
            <div class="c-title__modal--lv3">商品カテゴリー編集</div>
            <div class="l-modal--product-edit">
              <!-- 1列目 -->
              <dl>
                <dt>商品名</dt>
                <dd>
                  <input type="text" class="u-w-full-wide">
                </dd>          
              </dl>
              <div class="l-grid--col2 l-grid--gap1">
                <dl>
                  <dt>
                    カテゴリー
                  </dt>
                  <dd class="c-form-select-color u-mb0">
                    <select name="" id="">
                      <option value="">洗車</option>
                      <option value="">オプション</option>
                    </select>
                  </dd>
                </dl>
                <dl>
                  <dt>
                    税区分
                  </dt>
                  <dd class="c-form-select-color u-mb0">
                    <select name="" id="tax_division">
                      <option value="1.1">10%</option>
                      <option value="1.08">8%</option>
                    </select>
                  </dd>
                </dl>
              </div>
  
              <!-- 2列目 -->
              <dl>
                <dt>略称</dt>
                <dd>
                  <input type="text" class="u-w-full-wide">
                </dd>          
              </dl>
              <dl>
                <dt>金額（税抜）</dt>
                <dd>
                  <input type="text" id="price" class="u-w-full-wide">
                </dd>
              </dl>
  
              <!-- 3列目 -->
              <dl>
                <dt>説明</dt>
                <dd>
                  <input type="text" class="u-w-full-wide">
                </dd>          
              </dl>

              <div class="l-flex l-flex--space-between u-border--bottom u-pb05">
                <label for="total">税込価格</label>
                <span id="total" class="u-font--24 text-right">0円</span>
              </div>
            </div>
          </div>

          <div class="l-flex--center l-grid--gap1 u-mt4 u-mb2">
            <button type="submit" id="modal_add" class="c-button__submit">保存</button>
          </div>
        </form>
      </div><!-- ./l-modal__content -->

      <!-- データ削除ボタン -->
      <div class="l-modal__trashButton">
        <img src="../images/svg/trash.svg" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
      </div>
    </div><!-- ./l-modal inner -->
    <!-- 閉じる・追加ボタン -->
  </div>
  <!-- モーダル -->
  <script src="../js/modalOption.js"></script>

  <!-- 閉じるボタン -->
  <script src="../js/close_button_toggle.js"></script>
  <!-- メモボタン/button_openを押したらbutton_closeにis-blockを付与 -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const buttonOpenList = document.querySelectorAll('.button_open');
      const buttonCloseList = document.querySelectorAll('.button_close');
      const balloonBoxList = document.querySelectorAll('.c-balloon-memo__box');

      buttonOpenList.forEach(function(buttonOpen, index) {
        buttonOpen.addEventListener('click', function() {
          // 対応する吹き出しの表示
          balloonBoxList[index].classList.add('is-inline-block');
        });
      });

      buttonCloseList.forEach(function(buttonClose, index) {
        buttonClose.addEventListener('click', function() {
          // 吹き出しを閉じる
          balloonBoxList[index].classList.remove('is-inline-block');
        });
      });
    });
  </script>

  <!-- テーブルヘッダー用ソートのスクリプト -->
  <script src="../js/tableHeaderSort.js"></script>
</body>

</html>