<div id="modalAreaOption_{{$mode}}_{{$agency?->id}}" class="l-modal">
  <!-- モーダルのinnerを記述   -->
  <div class="l-modal__inner l-modal--trash">
    <div class="l-modal__head">{{$label}}</div>
    <!-- close button -->
    @if ($mode == 'new')
      <div class="l-modal__close modal_optionClose" onclick="closeCreateModal()">×</div>
    @else
      <div class="l-modal__close modal_optionClose" onclick="closeEditModal({{$agency->id}})">×</div>
    @endif
    <div class="l-modal__content">
      <form action="{{$action}}" method="POST" enctype="multipart/form-data" class="l-flex--column l-flex--sb u-w-full">
        @csrf
        @method($method)
        <!-- <form class="l-grid__right-submitButton l-grid__agency "> -->
        <div>
          <div class="c-title__modal--lv3">代理店情報{{$label}}</div>
          <div class="l-grid__agency--up u-mb2-half">

            <!-- 一段目 -->
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="code" class="u-font--md">代理店コード</label>
                <input type="text" id="code" name="code" value="{{old('code', $agency?->code)}}">
              </div>
              <div>
                <label for="name" class="u-font--md">社名</label>
                <input type="text" id="name" name="name" value="{{old('name', $agency?->name)}}">
              </div>
              <div>
                <label for="branch" class="u-font--md">支店名</label>
                <input type="text" id="branch" name="branch" value="{{old('branch', $agency?->branch)}}">
              </div>
            </div>

            <!-- 2段目 -->
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="zip" class="u-font--md">住所 〒</label>
                <input type="text" id="zip" name="zip" value="{{old('zip', $agency?->zip)}}">
              </div>
              <div>
                <label for="address1" class="u-font--md">住所1</label>
                <input type="text" id="address1" name="address1" value="{{old('address1', $agency?->address1)}}">
              </div>
              <div>
                <label for="address2" class="u-font--md">住所2</label>
                <input type="text" id="address2" name="address2" value="{{old('address2', $agency?->address2)}}">
              </div>
            </div>

            <!-- 3段目 -->
            <div style="width: 33%;padding-right:1.25rem;">
              <label for="tel" class="u-font--md">電話番号</label>
              <input type="text" id="tel" name="tel" value="{{old('tel', $agency?->tel)}}">
            </div>

            <!-- 4段目 -->
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="department" class="u-font--md">担当者部署</label>
                <input type="text" id="department" name="department" value="{{old('department', $agency?->department)}}">
              </div>
              <div>
                <label for="position" class="u-font--md">担当者役職</label>
                <input type="text" id="position" name="position" value="{{old('position', $agency?->position)}}">
              </div>
              <div>
                <label for="person" class="u-font--md">担当者氏名</label>
                <input type="text" id="person" name="person" value="{{old('person', $agency?->person)}}">
              </div>
            </div>

            <!-- 5段目 -->
            <div class="l-grid--col3 l-grid--cgap2-half">
              <div>
                <label for="email" class="u-font--md">担当者メールアドレス</label>
                <input type="text" id="email" name="email" value="{{old('email', $agency?->email)}}">
              </div>
              <div>
                <label for="payment_site" class="u-font--md">支払いサイト</label>
                <input type="text" id="payment_site" name="payment_site" value="{{old('payment_site', $agency?->payment_site)}}">
              </div>
              <div>
                <label for="payment_destination" class="u-font--md">振込先情報</label>
                <input type="text" id="payment_destination" name="payment_destination" value="{{old('payment_destination', $agency?->payment_destination)}}">
              </div>
            </div>

            <!-- 6段目 -->
            <div>
              <label for="memo" class="u-font--md">社内共有メモ</label>
              <input type="text" id="memo" name="memo" class="c-form-input--w100" value="{{old('memo', $agency?->memo)}}">
            </div>

            <!-- LAST -->
            <div>
              <label for="keyword" class="u-font--md">検索用キーワード</label>
              <input type="text" id="keyword" name="keyword" value="{{old('keyword', $agency?->keyword)}}">
            </div>
          </div>

          <!-- 下段 -->
          <div class="l-grid__agency--center l-grid--gap1 u-mb2-half">
              <span class="u-font--md">月額固定費用</span>
              <div class="c-button-optionSelect">
                <input type="radio" id="monthly_fixed_cost_flag1-{{$agency?->id}}" name="monthly_fixed_cost_flag" value="1"  {{old('monthly_fixed_cost_flag', $agency?->monthly_fixed_cost_flag) == 1 ? "checked":""}}>
                <label for="monthly_fixed_cost_flag1-{{$agency?->id}}" class="text-center u-lh38 c-button-optionSelect__radioLabel">支払う</label>
              </div>
              <div class="c-button-optionSelect">
                <input type="radio" id="monthly_fixed_cost_flag2-{{$agency?->id}}" name="monthly_fixed_cost_flag" value="0" {{old('monthly_fixed_cost_flag', $agency?->monthly_fixed_cost_flag) == 0 ? "checked":""}}>
                <label for="monthly_fixed_cost_flag2-{{$agency?->id}}" class="text-center u-lh38 c-button-optionSelect__radioLabel">支払わない</label>
              </div>
              <div>
                <input type="text" name="monthly_fixed_cost" class="u-w-auto u-mr025 u-mb0" value="{{old('monthly_fixed_cost', $agency?->monthly_fixed_cost)}}"><span class="u-font--md">円</span>
              </div>

              <span class="u-font--md">インセンティブ</span>
              <div class="c-button-optionSelect">
                <input type="radio" id="incentive_flag1-{{$agency?->id}}" name="incentive_flag" value="1" {{old('incentive_flag', $agency?->incentive_flag) == 1 ? "checked":""}}>
                <label for="incentive_flag1-{{$agency?->id}}" class="text-center u-lh38 u-w120 c-button-optionSelect__radioLabel">支払う</label>
              </div>
              <div class="c-button-optionSelect">
                <input type="radio" id="incentive_flag2-{{$agency?->id}}" name="incentive_flag" value="0" {{old('incentive_flag', $agency?->incentive_flag) == 0 ? "checked":""}}>
                <label for="incentive_flag2-{{$agency?->id}}" class="text-center u-lh38 u-w120 c-button-optionSelect__radioLabel">支払わない</label>
              </div>
              <div>
                <input type="text" name="incentive" class="u-w-auto u-mr025 u-mb0" value="{{old('incentive', $agency?->incentive)}}"><span class="u-font--md">%</span>
              </div>
          </div>

          <div class="l-grid__agency--bottom">
            <div>
              <label for="banner_comment_set" class="u-font--md">バナーコメントの設定</label>
              <input type="text" name="banner_comment_set" id="banner_comment_set" value="{{old('banner_comment_set', $agency?->banner_comment_set)}}">
              <label for="title_set" class="u-font--md">タイトルの設定</label>
              <input type="text" name="title_set" id="title_set" class="u-mb0" value="{{old('title_set', $agency?->title_set)}}">
            </div>
            <div class="l-flex--column">
              <label for="logo" class="u-font--md">ロゴ画像の登録</label>
              <div class="c-form-input__upload-image u-mb025 p-master-agency-setting__upload-image l-position__upload">
                <img id="logoImageDisplay{{$agency?->id}}"
                  @if (!$agency || !$agency->logo_url)
                    style="display:none; max-width:100px;"
                  @else
                    style="max-width:100px;"
                  @endif
                  src="{{$agency?->logo_url}}"
                >
              </div>
              <button type="button" class="c-button-file u-font--md" id="logoUploadButton{{$agency?->id}}">アップロード</button>
              <input type="file" id="logoFileInput{{$agency?->id}}" name="logo_image" accept="image/*" style="display: none;" value="{{old('logo_image')}}">
            </div>
            <div class="l-flex--column">
              <label for="campaign" class="u-font-nowrap u-font--md">キャンペーン画像の登録</label>
              <div class="c-form-input__upload-image u-mb025 p-master-agency-setting__upload-image l-position__upload">
                <img id="campaignImageDisplay{{$agency?->id}}"
                  @if (!$agency || !$agency->campaign_image_url)
                    style="display:none;"
                  @endif
                  src="{{$agency?->campaign_image_url}}"
                >
              </div>
              <button type="button" class="c-button-file u-font--md" id="campaignUploadButton{{$agency?->id}}">アップロード</button>
              <input type="file" id="campaignFileInput{{$agency?->id}}" name="campaign_image" accept="image/*" style="display: none;" value="{{old('campaign_image')}}">
            </div>
          </div>
        </div><!-- ./left -->

        <!-- 登録ボタン -->
        <div class="l-flex--center l-grid--gap1 u-mt2 u-mb2 c-button__csv--upload">
          <!-- <button type="submit" class="c-button__submit--dark-gray">新規保存</button> -->
          <button type="submit" id="modal_{{$mode}}_{{$agency?->id}}" class="c-button__submit">保存</button>
          <!-- CSVダウンロードボタンは新規登録の場合非表示 -->
          @if ($mode == 'edit')
            <a href="{{route('manage.master.agencies.download', [$agency->id])}}" download="" class="c-button__submit--dark-gray" style="text-decoration: none;">CSVダウンロード</a>
          @endif
        </div>
      </form>
    </div><!-- ./l-modal__content -->

    <!-- データ削除ボタン -->
    @if ($mode == 'edit')
      <form id="delete_{{$agency->id}}_form" action="{{route('manage.master.agencies.destroy', [$agency->id])}}" method="post">
        @csrf
        @method('DELETE')
      </form>
      <div class="l-modal__trashButton" onclick="deleteAgency({{$agency->id}})">
        <img src="{{asset('images/svg/trash.svg')}}" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
      </div>
    @endif
    {{--  <div class="l-modal__trashButton">
      <img src="../images/svg/trash.svg" alt="ゴミ箱" width="100%" class="l-modal--trashButton">
    </div>  --}}
  </div><!-- ./l-modal inner -->
  <!-- 閉じる・追加ボタン -->
</div>