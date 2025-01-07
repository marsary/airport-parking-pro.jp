<!-- マスタ登録 -->
@extends('layouts.manage.authenticated')

@section('content')
  <main class="l-wrap__main">
    <!-- パンくず -->
    <ul class="l-wrap__breadcrumb l-breadcrumb">
      <li class="l-breadcrumb__list">マスタ登録</li>
    </ul>

    <div class="l-container__inner">
      <form action="" enctype="multipart/form-data" id="airlineForm" class="p-master-register">
        <div class="p-master-register__wrap">
          <div class="p-master-register__item">
            <div class="p-master-register__title">航空会社マスタ</div>
            <div class="c-button__csv--upload">
              <button class="c-button__csv" type="button">csvアップロード</button>
              <input type="file" id="airlineFileInput" />
            </div>
            <div id="airlineFileNameDisplay" class="l-position__upload"></div>
            <a href="" download="" class="c-button__csv--download">csvダウンロード</a>
          </div>

          <div class="p-master-register__item">
            <div class="p-master-register__title p-master-register__title--bordered">空港マスタ</div>
            <div class="c-button__csv--upload">
              <button class="c-button__csv" type="button">csvアップロード</button>
              <input type="file" id="airportFileInput" />
            </div>
            <div id="airportFileNameDisplay" class="l-position__upload"></div>
            <a href="" download="" class="c-button__csv--download">csvダウンロード</a>
          </div>

          <div class="p-master-register__item">
            <div class="p-master-register__title">フライトマスタ</div>
            <div class="c-button__csv--upload">
              <button class="c-button__csv" type="button">csvアップロード</button>
              <input type="file" id="flightFileInput" />
            </div>
            <div id="flightFileNameDisplay" class="l-position__upload"></div>
            <a href="" download="" class="c-button__csv--download">csvダウンロード</a>
          </div>
        </div>
        <input type="submit" value="登録" class="c-button__register is-block u-horizontal-auto" disabled>
      </form>

      <div class="l-flex--end">
        <a href=""
        class="c-link-no-border u-btn-h--m u-mt2
        c-button__pagination--prev c-button__pagination--prev-green">マスタに戻る</a>
      </div>

    </div>
  </main>
</div>
@endsection
@push("scripts")
  <!-- ファイルアップロードの時、ファイル名表示スクリプト -->
   <script>
    let uploadedFiles = 0;

    function setupForm(itemClass, fileInputId, fileNameDisplayId) {
      const item = document.querySelector(itemClass);
      const fileInput = document.getElementById(fileInputId);
      const fileNameDisplay = document.getElementById(fileNameDisplayId);
      const uploadButton = item.querySelector('.c-button__csv');
      const registerButton = document.querySelector('.c-button__register');

      uploadButton.addEventListener('click', () => {
        fileInput.click();
      });

      fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
          const fileName = file.name;
          fileNameDisplay.innerHTML = `
            <span>${fileName}</span>
            <img src="../images/icon/closeButton.svg" width="15px" height="15px" alt="削除" class="pointer delete-button">
          `;
          if (uploadedFiles === 0) {
            registerButton.removeAttribute('disabled');
          }
          uploadedFiles++;

          const deleteButton = fileNameDisplay.querySelector('.delete-button');
          deleteButton.addEventListener('click', function() {
            fileInput.value = '';
            fileNameDisplay.innerHTML = '';
            uploadedFiles--;
            if (uploadedFiles === 0) {
              registerButton.setAttribute('disabled', '');
            }
          });
        } else {
          fileNameDisplay.innerHTML = '';
        }
      });
    }


    // 各フォームのセットアップ
    setupForm('.p-master-register__item:nth-child(1)', 'airlineFileInput', 'airlineFileNameDisplay');
    setupForm('.p-master-register__item:nth-child(2)', 'airportFileInput', 'airportFileNameDisplay');
    setupForm('.p-master-register__item:nth-child(3)', 'flightFileInput', 'flightFileNameDisplay');
  </script>
@endpush
