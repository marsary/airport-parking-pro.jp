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

      @include('include.messages.errors')

      <div class="l-container__inner">
      <h2 class="c-title__lv2 l-flex--sb u-w-full-wide">料金設定登録<span class="close_button c-button__close">閉じる</span></h2>

      <form action="{{route('manage.master.agencies.index')}}" method="get" class="l-grid__right-submitButton l-grid__agency u-mb3-half is-active">
        <div>
          <div class="l-grid__agency--up">
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="code">代理店コード</label>
                <input type="text" id="code" name="code" value="{{request('code')}}">
              </div>
              <div>
                <label for="name">社名</label>
                <input type="text" id="name" name="name" value="{{request('name')}}">
              </div>
            </div>

            <div style="width: 33%;padding-right:1.25rem;">
              <label for="tel">電話番号</label>
              <input type="text" id="tel" name="tel" value="{{request('tel')}}">
            </div>

            <!-- LAST -->
            <div>
              <label for="keyword">検索用キーワード</label>
              <input type="text" id="keyword" name="keyword" value="{{request('keyword')}}" class="u-mb0">
            </div>
          </div>
        </div><!-- ./left -->
        <!-- 登録ボタン -->
        <div class="l-grid__right-submitButton--button c-button__csv--upload">
          <button type="submit" class="c-button__register">検索</button>
          <button type="button" class="c-button__register  --gray button_select" onclick="openCreateModal()">新規登録</button>
          <!-- <a href="" download="" class="c-button__load" style="text-decoration: none;">CSVダウンロード</a> -->
          <button type="button" class="c-button__load upload">CSVアップロード</button>
          <div>
            <div id="csvFileNameDisplay" class="u-pt-1 u-w160 l-position__upload u-mb025"></div>
            <input type="submit" value="登録" class="c-button__register fileUpload u-mb0" form="csv-upload-form" disabled>
          </div>
        </div>

      </form>
      <form id="csv-upload-form" action="{{route('manage.master.agencies.upload')}}" method="POST" enctype="multipart/form-data">
        @method('POST')
        @csrf
        <div class="c-button__csv--upload">
          <input type="file" name="csvFileInput" id="csvFileInput" />
        </div>
      </form>

      <h2 class="c-title__lv2 l-flex--sb u-w-full-wide">登録済み 代理店設定一覧</h2>
      <!-- 料金一覧テーブル -->
      <!-- body直下にソートのスクリプトあり -->
      <div class="l-table-list--agency_setting__container">
        <table class="l-table-list l-table-list--agency_setting__table">
          <thead>
            <tr class="l-table-list__head l-table-list--agency_setting__head u-font-nowrap">
              <th><div class="c-button-sort sort-enable --desc">社名</div></th>
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
            @foreach ($agencies as $agency)
              <tr>
                <td>{{$agency->name}}</td>
                <td>{{$agency->branch}}</td>
                <td>{{$agency->address}}</td>
                <td>{{$agency->tel}}</td>
                <td>{{$agency->department}}</td>
                <td>{{$agency->position}}</td>
                <td>{{$agency->person}}</td>
                <td>{{$agency->email}}</td>
                <td>
                  <button class="c-button__edit button_select" onclick="openEditModal({{$agency->id}})">編集</button>
                </td>
              </tr>
            @endforeach
            {{--  <tr>
              <td>代理店1</td>
              <td>札幌支店</td>
              <td>広島県広島市西区観音町13-9マンション名1001</td>
              <td>072-209-0601</td>
              <td></td>
              <td></td>
              <td></td>
              <td>example@gmail.com</td>
              <td><button class="c-button__edit button_select">編集</button></td>
            </tr>  --}}
          </tbody>
        </table>
      </div>
      </div><!-- ./l-container__inner -->
    </main>
  </div><!-- ./l-wrap -->
  @include('manage.master.components.agency_modal', [
    'mode' => 'new',
    'label' => '新規登録',
    'method' => 'POST',
    'action' => route('manage.master.agencies.store'),
    'agency' => null,
    ]
  )
  <!-- 「編集」をクリックしたら出てくるmodal -->
  @foreach ($agencies as $agency)
    @include('manage.master.components.agency_modal', [
      'mode' => 'edit',
      'label' => '編集',
      'method' => 'PUT',
      'action' => route('manage.master.agencies.update', [$agency->id]),
      'agency' => $agency,
      ]
    )
  @endforeach

@endsection
@push("scripts")

  <!-- ファイルアップロードの時、ファイル名/画像表示スクリプト -->
  <script>
    let createModal;
    let modalAreaOptions;
    let modalCloseOption;
    const agencyIds = @js($agencies->pluck('id')->toArray())

    function openCreateModal() {
      createModal.classList.add('is-active');
    }
    function openEditModal(agencyId) {
      document.getElementById(`modalAreaOption_edit_${agencyId}`).classList.add('is-active');
    }
    function closeCreateModal() {
      createModal.classList.remove('is-active');
    }
    function closeEditModal(agencyId) {
      document.getElementById(`modalAreaOption_edit_${agencyId}`).classList.remove('is-active');
    }
    function deleteAgency(agencyId) {
      document.getElementById(`delete_${agencyId}_form`).submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
      createModal = document.getElementById('modalAreaOption_new_');
      modalAreaOptions = document.querySelectorAll('.modal_area');
      modalCloseOption = document.querySelectorAll('.modal_optionClose');

      setupImageUpload('logoFileInput', 'logoImageDisplay', 'logoUploadButton');
      setupImageUpload('campaignFileInput', 'campaignImageDisplay', 'campaignUploadButton');

      agencyIds.forEach(agencyId => {
        initDeleteButton(agencyId);
        setupImageUpload('logoFileInput' + agencyId, 'logoImageDisplay' + agencyId, 'logoUploadButton' + agencyId);
        setupImageUpload('campaignFileInput' + agencyId, 'campaignImageDisplay' + agencyId, 'campaignUploadButton' + agencyId);
      })
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

    function initDeleteButton(agencyId) {
      const logoImageDisplay = document.getElementById('logoImageDisplay' + agencyId);
      const campaignImageDisplay = document.getElementById('campaignImageDisplay' + agencyId);

      if(logoImageDisplay.getAttribute('src') != '')  {
        const logoFileInput = document.getElementById('logoFileInput' + agencyId);
        const container = logoImageDisplay.parentElement;
        addDeleteButton(container, logoFileInput, logoImageDisplay);
      }
      if(campaignImageDisplay.getAttribute('src') != '')  {
        const campaignFileInput = document.getElementById('campaignFileInput' + agencyId);
        const container = campaignImageDisplay.parentElement;
        addDeleteButton(container, campaignFileInput, campaignImageDisplay);
      }
    }

    document.querySelectorAll('.l-table-list th .sort-enable').forEach(th => th.onclick = (e) => sortRows(e, '.l-table-list'));
  </script>
  <!-- ソートスクリプト -->
  <script src="{{ asset('js/tableHeaderSort.js') }}"></script>
  <!-- 閉じるボタン -->
  <script src="{{ asset('js/close_button_toggle.js') }}"></script>
@endpush
