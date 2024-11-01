<!-- 代理店設定 -->
<!-- agency-setting.php -->
<!-- G-3 -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">代理店設定</li>
      </ul>

      <div class="l-container__inner">
      <h2 class="c-title__lv2 l-flex--sb u-w-full-wide">料金設定登録<span class="close_button c-button__close">閉じる</span></h2>

      <form action="" method="post" enctype="multipart/form-data" class="l-grid__right-submitButton l-grid__agency u-mb3-half is-active">
        <div>
          <div class="l-grid__agency--up">
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="agency_code">代理店コード</label>
                <input type="text" id="agency_code" name="agency_code">
              </div>
              <div>
                <label for="company_name">社名</label>
                <input type="text" id="company_name" name="company_name">
              </div>
            </div>
            
            <div style="width: 33%;padding-right:1.25rem;">
              <label for="tel">電話番号</label>
              <input type="text" id="tel" name="tel">
            </div>
            
            <!-- LAST -->
            <div>
              <label for="search_keywords">検索用キーワード</label>
              <input type="text" id="search_keywords" name="search_keywords" class="u-mb0">
            </div>
          </div>
        </div><!-- ./left -->
        <!-- 登録ボタン -->
        <div class="l-grid__right-submitButton--button c-button__csv--upload">
          <button type="submit" class="c-button__register">検索</button>
          <button type="button" class="c-button__register  --gray button_select">新規登録</button>
          <!-- <a href="" download="" class="c-button__load" style="text-decoration: none;">CSVダウンロード</a> -->
          <button type="button" class="c-button__load upload">CSVアップロード</button>
          <input type="file" id="csvFileInput" />
          <div>
            <div id="csvFileNameDisplay" class="u-pt-1 u-w160 l-position__upload u-mb025"></div>
            <input type="submit" value="登録" class="c-button__register fileUpload u-mb0" disabled>
          </div>
        </div>

      </form>

      <h2 class="c-title__lv2 l-flex--sb u-w-full-wide">登録済み 代理店設定一覧</h2>
      <!-- 料金一覧テーブル -->
      <!-- body直下にソートのスクリプトあり -->
      <div class="l-table-list--agency_setting__container">
        <table class="l-table-list l-table-list--agency_setting__table">
          <thead>
            <tr class="l-table-list__head l-table-list--agency_setting__head u-font-nowrap">
              <th><div class="c-button-sort --desc">社名</div></th>
              <th><div class="c-button-sort">支店名</div></th>
              <th><div class="c-button-sort">住所</div></th>
              <th><div class="c-button-sort">電話番号</div></th>
              <th><div class="c-button-sort">担当者部署</div></th>
              <th><div class="c-button-sort">担当者役職</div></th>
              <th><div class="c-button-sort">担当者氏名</div></th>
              <th><div class="c-button-sort">担当者メールアドレス</div></th>
              <th></th>
            </tr>
          </thead>
          <tbody class="l-table-list__body">
            <tr>
              <td>代理店1</td>
              <td>札幌支店</td>
              <td>広島県広島市西区観音町13-9マンション名1001</td>
              <td>072-209-0601</td>
              <td></td>
              <td></td>
              <td></td>
              <td>example@gmail.com</td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>
          </tbody>
        </table>
      </div>
      </div><!-- ./l-container__inner -->
    </main>
  </div><!-- ./l-wrap -->

  <!-- 「編集」をクリックしたら出てくるmodal -->
  <div id="modalAreaOption" class="l-modal">
    <!-- モーダルのinnerを記述   -->
    <div class="l-modal__inner l-modal--trash">
      <div class="l-modal__head">編集</div>
      <!-- close button -->
      <div class="l-modal__close modal_optionClose">×</div>
      <div class="l-modal__content">
        <form action="" method="post" enctype="multipart/form-data" class="l-flex--column l-flex--sb u-w-full">
        <!-- <form class="l-grid__right-submitButton l-grid__agency "> -->
        <div>
          <div class="c-title__modal--lv3">代理店情報編集</div>
          <div class="l-grid__agency--up u-mb2-half">

            <!-- 一段目 -->
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="agency_code" class="u-font--md">代理店コード</label>
                <input type="text" id="agency_code" name="agency_code">
              </div>
              <div>
                <label for="company_name" class="u-font--md">社名</label>
                <input type="text" id="company_name" name="company_name">
              </div>
              <div>
                <label for="branch_name" class="u-font--md">支店名</label>
                <input type="text" id="branch_name" name="branch_name">
              </div>
            </div>
            
            <!-- 2段目 -->
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="postal_code" class="u-font--md">住所 〒</label>
                <input type="text" id="postal_code" name="postal_code">
              </div>
              <div>
                <label for="address1" class="u-font--md">住所1</label>
                <input type="text" id="address1" name="address1">
              </div>
              <div>
                <label for="address2" class="u-font--md">住所2</label>
                <input type="text" id="address2" name="address2">
              </div>
            </div>
            
            <!-- 3段目 -->
            <div style="width: 33%;padding-right:1.25rem;">
              <label for="tel" class="u-font--md">電話番号</label>
              <input type="text" id="tel" name="tel">
            </div>
            
            <!-- 4段目 -->
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="department" class="u-font--md">担当者部署</label>
                <input type="text" id="department" name="department">
              </div>
              <div>
                <label for="position" class="u-font--md">担当者役職</label>
                <input type="text" id="position" name="position">
              </div>
              <div>
                <label for="contact_name" class="u-font--md">担当者氏名</label>
                <input type="text" id="contact_name" name="contact_name">
              </div>
            </div>
            
            <!-- 5段目 -->
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="email" class="u-font--md">担当者メールアドレス</label>
                <input type="text" id="email" name="email">
              </div>
              <div>
                <label for="payment_terms" class="u-font--md">支払いサイト</label>
                <input type="text" id="payment_terms" name="payment_terms">
              </div>
              <div>
                <label for="bank_info" class="u-font--md">振込先情報</label>
                <input type="text" id="bank_info" name="bank_info">
              </div>
            </div>
            
            <!-- 6段目 -->
            <div>
              <label for="memo" class="u-font--md">社内共有メモ</label>
              <input type="text" id="memo" name="memo" class="c-form-input--w100">
            </div>
            
            <!-- LAST -->
            <div>
              <label for="search_keywords" class="u-font--md">検索用キーワード</label>
              <input type="text" id="search_keywords" name="search_keywords">
            </div>
          </div>
          
          <!-- 下段 -->
          <div class="l-grid__agency--center l-grid--gap1 u-mb2-half">
              <span class="u-font--md">月額固定費用</span>
              <div class="c-button-optionSelect">
                <input type="radio" id="payment" name="paymentOption" value="" checked>
                <label for="payment" class="text-center u-lh38 c-button-optionSelect__radioLabel">支払う</label>
              </div>
              <div class="c-button-optionSelect">
                <input type="radio" id="non-payment" name="paymentOption" value="">
                <label for="non-payment" class="text-center u-lh38 c-button-optionSelect__radioLabel">支払わない</label>
              </div>
              <div>
                <input type="text" class="u-w-auto u-mr025 u-mb0"><span class="u-font--md">円</span>
              </div>

              <span class="u-font--md">インセンティブ</span>
              <div class="c-button-optionSelect">
                <input type="radio" id="incentive" name="incentiveOption" value="" checked>
                <label for="incentive" class="text-center u-lh38 u-w120 c-button-optionSelect__radioLabel">支払う</label>
              </div>
              <div class="c-button-optionSelect">
                <input type="radio" id="non-incentive" name="incentiveOption" value="">
                <label for="non-incentive" class="text-center u-lh38 u-w120 c-button-optionSelect__radioLabel">支払わない</label>
              </div>
              <div>
                <input type="text" class="u-w-auto u-mr025 u-mb0"><span class="u-font--md">%</span>
              </div>
          </div>

          <div class="l-grid__agency--bottom">
            <div>
              <label for="" class="u-font--md">バナーコメントの設定</label>         
              <input type="text" name="" id="">
              <label for="" class="u-font--md">タイトルの設定</label>
              <input type="text" name="" id="" class="u-mb0">
            </div>
            <div class="l-flex--column">
              <label for="logo" class="u-font--md">ロゴ画像の登録</label>
              <div class="c-form-input__upload-image u-mb025 p-master-agency-setting__upload-image l-position__upload">
                <img id="logoImageDisplay" style="display:none; max-width:100px;">
              </div>
              <button type="button" class="c-button-file u-font--md" id="logoUploadButton">アップロード</button>
              <input type="file" id="logoFileInput" name="logo" accept="image/*" style="display: none;">
            </div>
            <div class="l-flex--column">
              <label for="campaign" class="u-font-nowrap u-font--md">キャンペーン画像の登録</label>
              <div class="c-form-input__upload-image u-mb025 p-master-agency-setting__upload-image l-position__upload">
                <img id="campaignImageDisplay" style="display:none;">
              </div>
              <button type="button" class="c-button-file u-font--md" id="campaignUploadButton">アップロード</button>
              <input type="file" id="campaignFileInput" name="campaign" accept="image/*" style="display: none;">
            </div>
          </div>
        </div><!-- ./left -->
      
        <!-- 登録ボタン -->
        <div class="l-flex--center l-grid--gap1 u-mt2 u-mb2 c-button__csv--upload">
          <!-- <button type="submit" class="c-button__submit--dark-gray">新規保存</button> -->
          <button type="submit" id="modal_add" class="c-button__submit">保存</button>
          <!-- CSVダウンロードボタンは新規登録の場合非表示 -->
          <a href="" download="" class="c-button__submit--dark-gray" style="text-decoration: none;">CSVダウンロード</a>
        </div>
      </form>
    </div><!-- ./l-modal__content -->

      <!-- データ削除ボタン -->
      <div class="l-modal__trashButton">
        <img src="{{asset('images/svg/trash.svg')}}" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
      </div>
    </div><!-- ./l-modal inner -->
    <!-- 閉じる・追加ボタン -->
  </div>
@endsection
@push("scripts")
  <!-- ファイルアップロードの時、ファイル名/画像表示スクリプト -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        setupImageUpload('logoFileInput', 'logoImageDisplay', 'logoUploadButton');
        setupImageUpload('campaignFileInput', 'campaignImageDisplay', 'campaignUploadButton');
        setupCsvUpload('csvFileInput', 'csvFileNameDisplay');
    });

    function setupImageUpload(inputId, imageDisplayId, buttonId) {
        const fileInput = document.getElementById(inputId);
        const imageDisplay = document.getElementById(imageDisplayId);
        const uploadButton = document.getElementById(buttonId);
        const container = imageDisplay.parentElement;

        uploadButton.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageDisplay.src = e.target.result;
                    imageDisplay.style.display = 'block';
                    addDeleteButton(container, fileInput, imageDisplay);
                };
                reader.readAsDataURL(file);
            } else {
                imageDisplay.style.display = 'none';
            }
        });
    }

    function setupCsvUpload(inputId, fileNameDisplayId) {
      const fileInput = document.getElementById(inputId);
      const fileNameDisplay = document.getElementById(fileNameDisplayId);
      const uploadButton = document.querySelector('.c-button__load.upload');
      const registerButton = document.querySelector('.fileUpload');

      uploadButton.addEventListener('click', function() {
          fileInput.click();
      });

      fileInput.addEventListener('change', function(event) {
          const file = event.target.files[0];
          if (file) {
              fileNameDisplay.innerHTML = `選択されたファイル: ${file.name}`;
              addDeleteButton(fileNameDisplay, fileInput);
              registerButton.removeAttribute('disabled');
          } else {
              fileNameDisplay.textContent = 'ファイルが選択されていません';
              registerButton.setAttribute('disabled', '');
          }
      });
    }

    function addDeleteButton(container, fileInput, imageDisplay = null) {
    // 既存の削除ボタンを削除
    const existingDeleteButton = container.querySelector('.delete-button');
    if (existingDeleteButton) {
        existingDeleteButton.remove();
    }

    const deleteButton = document.createElement('img');
    deleteButton.src = '../images/icon/closeButton.svg';
    deleteButton.alt = '削除';
    deleteButton.className = 'delete-button';
    deleteButton.style.cursor = 'pointer';
    deleteButton.style.marginLeft = '10px';

    deleteButton.addEventListener('click', function() {
        fileInput.value = '';
        if (imageDisplay) {
            imageDisplay.style.display = 'none';
        } else {
            container.textContent = '';
        }
        deleteButton.remove();

        // CSVファイルが削除された場合、登録ボタンを無効化
        if (fileInput.id === 'csvFileInput') {
            const registerButton = document.querySelector('.fileUpload');
            registerButton.setAttribute('disabled', '');
        }
    });

    container.appendChild(deleteButton);
    }
  </script>
  <!-- モーダル -->
  <script src="{{ asset('js/modalOption.js') }}"></script>
  <!-- ソートスクリプト -->
  <script src="{{ asset('js/tableHeaderSort.js') }}"></script>
  <!-- 閉じるボタン -->
  <script src="{{ asset('js/close_button_toggle.js') }}"></script>
@endpush
