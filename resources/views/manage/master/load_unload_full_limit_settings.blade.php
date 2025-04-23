<!-- G-18 入出庫上限・在庫設定 -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">入出庫上限・在庫設定</li>
      </ul>

      <div class="l-container__inner">

        <h2 class="c-title__lv2 u-mb2">入出庫上限登録</h2>

          <!-- 一括登録ボタン -->
          <button class="c-button__register l-grid--self-end u-font--normal" formaction="" onclick="openPeriodModal()" style="float:right;margin-bottom:10px;">期間登録</button>
          
        </form>

        <!-- カレンダー -->
        <!-- ページネーション -->
        <div class="c-pager__pagination-container">
          <button class="c-pager__button c-button__prev" id="prevButton" onclick="prevPage()"></button>
          <div class="c-pager__year-list-wrapper">
              <div class="c-pager__year-list" id="yearList">
                  <div class="c-pager__year-item">2015</div>
                  <div class="c-pager__year-item">2016</div>
                  <div class="c-pager__year-item">2017</div>
                  <div class="c-pager__year-item">2018</div>
                  <div class="c-pager__year-item">2019</div>
                  <div class="c-pager__year-item">2020</div>
                  <div class="c-pager__year-item">2021</div>
                  <div class="c-pager__year-item">2022</div>
                  <div class="c-pager__year-item">2023</div>
                  <div class="c-pager__year-item --selected">2024</div>
              </div>
          </div>
          <button class="c-pager__button c-button__next" id="nextButton" onclick="nextPage()"></button>
        </div>
      </div><!-- ./l-container__inner -->
    </main>
    <!-- 「期間登録」をクリックしたら出てくるmodal -->
    @include('manage.master.components.limit_modal', [
      'mode' => 'Period',
      'label' => '期間登録',
      'method' => 'POST',
      // 'action' => route('manage.master.prices.store'),
      'day' => null,
      ]
    )
  
    <!-- 「日付」をクリックしたら出てくるmodal -->
    @include('manage.master.components.limit_modal', [
      'mode' => 'edit',
      'label' => '編集',
      'method' => 'PUT',
      // 'action' => route('manage.master.prices.update', [$day]),
      'day' => null,
      ]
    )
@endsection
@push("scripts")
  <!-- 閉じる・開く切替 -->
  <script src="{{ asset('js/close_button_toggle.js') }}"></script>

  <script>
    let periodModal;
    let modalAreaOptions;
    let modalCloseOption;

    function openPeriodModal() {
      periodModal.classList.add('is-active');
    }
    function openEditModal() {
      document.getElementById(`modalAreaOption_edit_`).classList.add('is-active');
    }
    function closeCreateModal() {
      periodModal.classList.remove('is-active');
    }
    function closeEditModal() {
      document.getElementById(`modalAreaOption_edit_`).classList.remove('is-active');
    }
    function deleteLimit() {
      document.getElementById(`delete_form`).submit();
    }

    window.addEventListener('DOMContentLoaded', function() {
      periodModal = document.getElementById('modalAreaOption_Period_');
      modalAreaOptions = document.querySelectorAll('.modal_area');
      modalCloseOption = document.querySelectorAll('.modal_optionClose');
    })

  </script>
  <!-- カレンダー（year部分） -->
  <script src="../js/yearList.js"></script>
  <!-- ファイルアップロードの時スクリプト -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        setupCsvUpload('csvFileInput', 'csvFileNameDisplay');
    });

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
@endpush