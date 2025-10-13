<!-- N-5  -->
@extends('layouts.mypage.authenticated')
@section('content')
  <main class="p-user__inner">
    <div class="p-user__container">
      <div class="">
        <h2 class="c-title__user--lv2">マイページ</h2>
        <div class="p-user-head__wrap">
          <a href="" class="p-user-head__item--reserve">
            <img src="/assets/images/icon/icon-car.svg" alt="">新規予約はこちら
          </a>
          <a href="https://www.sunparking.co.jp/guide/" target="_blank" class="p-user-head__item--access">
            <img src="/assets/images/icon/icon-parking.svg" alt="">駐車場のアクセス
          </a>
          <a href="mailto:" class="p-user-head__item--contact">
            <img src="/assets/images/icon/icon-contact.svg" alt="">お問い合わせ
          </a>
        </div>

        <h2 class="c-title__user--lv2">予約情報</h2>
        <div class="p-user-top__tab">
          <button id="buttonOne" class="p-user-top__tab-item is-active">予約一覧</button>
          <button id="buttonTwo" class="p-user-top__tab-item">過去の予約</button>
        </div>
        <!-- 予約一覧のコンテンツ --><!-- 最大5件表示 -->
        <div class="l-user__content p-user-top__tab-inner contentOne is-active">

          <!-- 1 -->
          <div class="l-user-reserve-info__wrap">
            <div class="l-user-reserve-info__item">
              <div class="l-flex--column l-flex--item-start l-grid--gap05">
                <div class="l-user-reserve-info__status">入庫中</div><!-- 入庫中の場合表示 -->
                <span class="l-user-reserve-info__label">取引コード:01234567898</span>
              </div>
              <div class="l-user-reserve-info__date">
                <div class="l-user-reserve-info__date--title">入庫日時</div>
                <div>2025年12月31日10:35</div>
              </div>
              <div class="l-user-reserve-info__date">
                <div class="l-user-reserve-info__date--title">出庫予定日</div>
                <div>2025年12月31日</div>
              </div>
            </div>
            <div class="l-user-reserve-info__button-area">
              <a href="/user/n-13.php" class="l-user-reserve-info__button ">詳細 / QR</a>
              <a href="/user/change.php" class="l-user-reserve-info__button">変更</a>
              <a href="/user/cancel.php" class="l-user-reserve-info__button">キャンセル</a>
            </div>
          </div>
          <!-- 2 -->
          <div class="l-user-reserve-info__wrap">
            <div class="l-user-reserve-info__item">
              <div class="l-flex--column l-flex--item-start l-grid--gap05">
                <span class="l-user-reserve-info__label">取引コード:01234567898</span>
              </div>
              <div class="l-user-reserve-info__date">
                <div class="l-user-reserve-info__date--title">入庫日時</div>
                <div>2025年12月31日10:35</div>
              </div>
              <div class="l-user-reserve-info__date">
                <div class="l-user-reserve-info__date--title">出庫予定日</div>
                <div>2025年12月31日</div>
              </div>
            </div>
            <div class="l-user-reserve-info__button-area">
              <a href="/user/n-13.php" class="l-user-reserve-info__button ">詳細 / QR</a>
              <a href="/user/change.php" class="l-user-reserve-info__button">変更</a>
              <a href="/user/cancel.php" class="l-user-reserve-info__button">キャンセル</a>
            </div>
          </div>

          <!-- ページャー -->
          <div class="l-user-pager">
            <a class="l-user-pager__item --current">1</a>
            <a href="/" class="l-user-pager__item">2</a>
            <a href="/" class="l-user-pager__item">3</a>
            <a href="/" class="l-user-pager__item">4</a>
            <a href="/" class="l-user-pager__item">5</a>
          </div>
        </div>

        <!-- 過去の予約のコンテンツ -->
        <div class="l-user__content p-user-top__tab-inner contentTwo is-none">
          <div class="l-user-reserve-info__wrap--old">
            <div class="l-user-reserve-info__item">
              <div>
                <span class="l-user-reserve-info__label">取引コード:01234567898</span>
              </div>
              <div class="l-user-reserve-info__date">
                <div class="l-user-reserve-info__date--title">入庫日時</div>
                <div>2025年2月1日10:35</div>
              </div>
              <div class="l-user-reserve-info__date">
                <div class="l-user-reserve-info__date--title">出庫日</div>
                <div>2025年2月1日</div>
              </div>
            </div>
            <div class="l-user-reserve-info__button-area">
              <a href="/user/n-13.php" class="l-user-reserve-info__button ">詳細</a>
            </div>
          </div>
          <div class="l-user-reserve-info__wrap--old">
            <div class="l-user-reserve-info__item">
              <div>
                <span class="l-user-reserve-info__label">取引コード:01234567898</span>
              </div>
              <div class="l-user-reserve-info__date">
                <div class="l-user-reserve-info__date--title">入庫日時</div>
                <div>2025年12月31日10:35</div>
              </div>
              <div class="l-user-reserve-info__date">
                <div class="l-user-reserve-info__date--title">出庫日</div>
                <div>2025年12月31日</div>
              </div>
            </div>
            <div class="l-user-reserve-info__button-area">
              <a href="/user/n-13.php" class="l-user-reserve-info__button ">詳細</a>
            </div>
          </div>
          <!-- ページャー 5件以上の場合ページャーを表示-->
          <div class="l-user-pager">
            <a class="l-user-pager__item --current">1</a>
            <a href="/" class="l-user-pager__item">2</a>
            <a href="/" class="l-user-pager__item">3</a>
            <a href="/" class="l-user-pager__item">4</a>
            <a href="/" class="l-user-pager__item">5</a>
          </div>
        </div>
        <!-- 過去の予約のコンテンツ fin -->

        <!-- 顧客情報　start -->
        <h2 class="c-title__user--lv2">顧客情報</h2>
        <div class="l-user__content">
          <div class="l-user-info__wrap">
            <div class="l-user-info__item">
              <div>
                <span class="l-user-info__label">顧客コード:01234567898</span>
              </div>
              <div class="l-user-info__name">
                <div>サン太郎</div>
              </div>
              <div>
                <div>利用回数: 8回</div>
                <div>会員ランク: シルバー</div>
              </div>
            </div>
            <div class="l-user-reserve-info__button-area">
              <a href="/user/n-14.php" class="l-user-reserve-info__button">顧客情報の詳細</a>
              <a href="/user/n-20.php" class="l-user-reserve-info__button">パスワード変更</a>
              <button class="l-user-reserve-info__button addVehicleModalButton">車両追加</button>
            </div>
          </div>
          <!-- 車両情報 -->
          <!-- 1 -->
          <div class="l-user-car-info__wrap VehicleModal">
            <div class="l-user-car-info__label" id="VehicleInfo">車両情報</div>
            <div class="l-user-car-info__inner">

              <div class="l-user-car-info__item">
                <div class="l-user-car-info__name">
                  <div class="u-font--sm is-block">トヨタ<span class="l-user-car-info__name--mark">デフォルト</span></div>
                  <!-- 12文字以上は「...」で省略 -->
                  長い車名長い車名長い車名...
                </div>
                <div class="l-user-car-info__detail">
                  <div>ナンバー: 1234</div>
                  <div>色: 黒</div>
                </div>
                <div class="l-user-car-info__button-area">
                  <button class="l-user-reserve-info__button editVehicleModalButton">変更</button>
                  <button class="l-user-reserve-info__button deleteVehicleButton">削除</button>
                </div>
              </div>
              <!-- 2 -->
              <div class="l-user-car-info__item VehicleModal">
                <div class="l-user-car-info__name"><span class="u-font--sm is-block">トヨタ</span>セルシオ</div>
                <div class="l-user-car-info__detail">
                  <div>ナンバー: 1234</div>
                  <div>色: 黒</div>
                </div>
                <div class="l-user-car-info__button-area">
                  <button class="l-user-reserve-info__button editVehicleModalButton">変更</button>
                  <button class="l-user-reserve-info__button deleteVehicleButton">削除</button>
                </div>
              </div>
              <!-- 3 -->
              <div class="l-user-car-info__item VehicleModal">
                <div class="l-user-car-info__name"><span class="u-font--sm is-block">トヨタ</span>セルシオ</div>
                <div class="l-user-car-info__detail">
                  <div>ナンバー: 1234</div>
                  <div>色: 黒</div>
                </div>
                <div class="l-user-car-info__button-area">
                  <button class="l-user-reserve-info__button editVehicleModalButton">変更</button>
                  <button class="l-user-reserve-info__button deleteVehicleButton">削除</button>
                </div>
              </div>
              <!-- 4 -->
              <div class="l-user-car-info__item VehicleModal">
                <div class="l-user-car-info__name"><span class="u-font--sm is-block">トヨタ</span>セルシオ</div>
                <div class="l-user-car-info__detail">
                  <div>ナンバー: 1234</div>
                  <div>色: 黒</div>
                </div>
                <div class="l-user-car-info__button-area">
                  <button class="l-user-reserve-info__button editVehicleModalButton">変更</button>
                  <button class="l-user-reserve-info__button deleteVehicleButton">削除</button>
                </div>
              </div>

            </div>
          </div>
          <!-- 車両情報 fin -->
        </div>
        <!-- 顧客情報　fin -->

      </div>
    </div>
  </main>

  <!-- QRコードモーダル -->
  <div id="qrViewModal" class="c-user-modal">
    <div class="c-user-modal__content">
      <span class="c-user-modal__close">&times;</span>
      <figure class="c-user-modal__qr">
        <img src="../assets/images/dummy/dummy_qr.jpg" width="100%" height="auto" alt="QR">
      </figure>
    </div>
  </div>

  <!-- 車両追加モーダル -->
  <div id="addVehicleModal" class="c-user-modal">
    <div class="c-user-modal__content">
      <span class="c-user-modal__close">&times;</span>
      <h2 class="c-user-modal__title">車両情報編集</h2>
      <div class="c-user-modal__form">
        <div>
          <label for="maker">メーカー</label>
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
        <button class="c-button__submit u-mt1 u-mb1 u-horizontal-auto" type="submit">追加</button>
      </div>
    </div>
  </div>

  <!-- 車両編集モーダル -->
  <div id="editVehicleModal" class="c-user-modal">
    <div class="c-user-modal__content">
      <span class="c-user-modal__close">&times;</span>
      <h2 class="c-user-modal__title">車両情報変更</h2>
      <div class="c-user-modal__form">
        <div>
          <label for="maker">メーカー</label>
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
        <button class="c-button__submit u-mt1 u-mb1 u-horizontal-auto" type="submit">変更</button>
      </div>
    </div>
  </div>

@endsection
@push("scripts")
  <!-- 予約一覧と過去の予約切り替えスクリプト -->
  <script src="../js/toggle_display.js"></script>
  <!-- モーダルスクリプト -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // モーダル要素取得
      const addModal = document.getElementById('addVehicleModal');
      const editModal = document.getElementById('editVehicleModal');

      // 車両追加ボタン
      document.querySelector('.addVehicleModalButton')
        .addEventListener('click', () => {
          // が5台以下の場合のみモーダル表示
          if (document.querySelectorAll('.VehicleModal').length < 5) {
            document.body.style.overflow = 'hidden';
            addModal.style.display = 'block';
          } else {
            alert('登録可能な車両は5台までです');
          }
        });

      // 車両編集ボタン
      document.querySelectorAll('.editVehicleModalButton')
        .forEach(btn => {
          btn.addEventListener('click', () => {
            editModal.style.display = 'block';
            document.body.style.overflow = 'hidden';
          });
        });

      // モーダル閉じる処理
      document.querySelectorAll('.c-user-modal__close, .c-user-modal').forEach(el => {
        el.addEventListener('click', e => {
          if (e.target.classList.contains('c-user-modal__close') || e.target.classList.contains('c-user-modal')) {
            addModal.style.display = 'none';
            editModal.style.display = 'none';
            document.body.style.overflow = '';
          }
        });
      });
    });
  </script>

  <!-- 車両情報削除ボタンを押したときの挙動スクリプト -->
  <script>
    document.querySelectorAll('.deleteVehicleButton').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('車両情報を削除します。よろしいですか？')) {
          // ここに処理を指定  
        }
      });
    });
  </script>
@endpush
@push('css')
<style>
</style>
@endpush
