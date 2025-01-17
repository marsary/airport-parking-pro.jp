<!-- G-15 割引クーポン設定 -->
@extends('layouts.manage.authenticated')

@section('content')
    <main class="l-wrap__main">
      <!-- パンくず -->
      <ul class="l-wrap__breadcrumb l-breadcrumb">
        <li class="l-breadcrumb__list">割引クーポン設定</li>
      </ul>

      @include('include.messages.errors')

      <div class="l-container__inner">
        <h2 class="c-title__lv2">新規登録 および 検索</h2>
        <form method="GET" action="{{route('manage.master.coupons.index')}}" class="l-grid--col2-auto l-grid--gap2 u-mb3 c-form-label--md">
          <div><!-- left side -->
            <div class="l-grid--col3-auto l-grid--gap1">
              <div>
                <!-- クーポン名 -->
                <label for="name">クーポン名</label>
                <input type="text" name="name" id="name" value="{{request('name')}}" class="u-w-full-wide">
              </div>
              <div>
                <!-- クーポンコード -->
                <label for="code">クーポンコード</label>
                <input type="text" name="code" id="code" value="{{request('code')}}" class="u-w-full-wide">
              </div>
              <!-- カテゴリーselect -->
              <div>
                <label for="category">対象カテゴリー</label>
                <div class="c-form-select-color">
                  <select name="good_category_id" id="good_category_id">
                    <option value="" selected>選択してください</option>
                    @foreach ($goodCategories as $goodCategory)
                      <option value="{{ $goodCategory->id }}"
                        {{request('good_category_id')==$goodCategory->id ? 'selected':''}}>
                        {{$goodCategory->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="l-grid--col4-auto l-grid--gap1">
                <!-- 利用回数制限 -->
                <div>
                  <label for="use_limit">利用回数制限</label>
                  <!-- 「1回」「複数回」ラジオボタン -->
                  <div class="l-grid__radio">
                    <div class="c-button-optionSelect">
                      <input type="radio" name="limit_flg" value="0" id="limit_flg0"  {{request('limit_flg') == 0 ? "checked":""}}>
                      <label for="limit_flg0" class="u-w70 u-h40 u-mb1 u-font--normal">1回</label>
                    </div>
                    <div class="c-button-optionSelect">
                      <input type="radio" name="limit_flg" value="1" id="limit_flg1" {{request('limit_flg') == 1 ? "checked":""}}>
                      <label for="limit_flg1" class="u-w70 u-h40 u-mb1 u-font--normal">複数回</label>
                    </div>
                  </div>
                </div>
                <!-- 併用 -->
                <div>
                  <label for="allow_combine">併用</label>
                  <!-- 「可」「不可」ラジオボタン -->
                  <div class="l-grid__radio">
                    <div class="c-button-optionSelect">
                      <input type="radio" name="combination_flg" value="1" id="combination_flg1"  {{request('combination_flg') == 1 ? "checked":""}}>
                      <label for="combination_flg1" class="u-w70 u-h40 u-mb1 u-font--normal">可</label>
                    </div>
                    <div class="c-button-optionSelect">
                      <input type="radio" name="combination_flg" value="0" id="combination_flg0" {{request('combination_flg') == 0 ? "checked":""}}>
                      <label for="combination_flg0" class="u-w70 u-h40 u-mb1 u-font--normal">不可</label>
                    </div>
                  </div>
                </div>
                <div class="l-flex l-grid--gap1">
                  <div>
                    <!-- 割引対象となる入庫日 -->
                    <label for="start_date">割引対象となる入庫日</label>
                    <input type="date" name="start_date" id="start_date" value="{{request('start_date')}}">
                  </div>
                  <span>～</span>
                  <div>
                    <!-- 終了日 -->
                    <label for="end_date">終了日</label>
                    <input type="date" name="end_date" id="end_date" value="{{request('end_date')}}">
                  </div>
                </div>
            </div>
          </div>
          <div class="l-flex--column l-flex--self-end">
            <button type="submit" class="c-button__register --gray u-mb1">検索</button>
            <button type="button" class="c-button__register u-mb05 button_select" onclick="openCreateModal()">新規登録</button>
          </div>
        </form>

        <h2 class=" c-title__lv2">登録済み割引クーポン一覧</h2>
        <table class="l-table-list">
          <thead class="l-table-list__head l-table-list--scroll__head">
            <tr>
              <th><div class="c-button-sort">クーポンコード</div></th>
              <th><div class="c-button-sort">クーポン名</div></th>
              <th><div class="c-button-sort">利用回数</div></th>
              <th><div class="c-button-sort">併用</div></th>
              <th><div class="c-button-sort">対象カテゴリ</div></th>
              <th><div class="c-button-sort --desc sort-enable">割引対象となる入庫日時</div></th>
              <th>メモ</th>
              <th></th>
            </tr>
          </thead>
          <tbody class="l-table-list__body">
            @foreach ($coupons as $coupon)
              <tr>
                <td>{{$coupon->code}}</td>
                <td>{{$coupon->name}}</td>
                <td class="text-center">{{\App\Enums\Coupon\LimitFlg::tryFrom($coupon->limit_flg)?->label()}}</td>
                <td class="text-center">{{\App\Enums\Coupon\CombinationFlg::tryFrom($coupon->combination_flg)?->label()}}</td>
                <td>{{$coupon->goodCategory?->name}}</td>
                <td>
                  {{$coupon->start_date->format("Y/m/d（{$coupon->start_date->shortDayName}）H:i")}} ～<br />{{$coupon->end_date->format("Y/m/d（{$coupon->end_date->shortDayName}）H:i")}}
                  {{--  2023/12/1（木）00:00 ～<br />2024/1/31（木）23:59  --}}
                </td>
                <td class="c-balloon-memo">
                  <button type="button" class="button_open c-button__memo">メモ</button>
                  <!-- 吹き出し -->
                  <div class="c-balloon-memo__box">
                    <div class="c-balloon-memo__text">{{$coupon->memo}}
                      <div class="button_close c-balloon-memo__close">閉じる</div>
                    </div>
                  </div>
                </td>
                <td><button class="c-button__edit button_select" onclick="openEditModal({{$coupon->id}})">編集</button></td>
              </tr>
            @endforeach
            {{--  <tr>
              <td>2023thankyou</td>
              <td>2023年感謝割引クーポン</td>
              <td class="text-center">複数回</td>
              <td class="text-center">不可</td>
              <td>駐車料金</td>
              <td>2023/12/1（木）00:00～<br />2024/1/31（木）23:59</td>
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
            </tr>  --}}
          </tbody>
        </table>
      </div>

    </main><!-- /.l-wrap__main -->
  </div><!-- /.l-container__wrap -->

  @include('manage.master.components.coupon_modal', [
    'mode' => 'new',
    'label' => '新規登録',
    'method' => 'POST',
    'action' => route('manage.master.coupons.store'),
    'goodCategories' => $goodCategories,
    'coupon' => null,
    ]
  )

  <!-- 「編集」をクリックしたら出てくるmodal -->
  @foreach ($coupons as $coupon)
    @include('manage.master.components.coupon_modal', [
      'mode' => 'edit',
      'label' => '編集',
      'method' => 'PUT',
      'action' => route('manage.master.coupons.update', [$coupon->id]),
      'goodCategories' => $goodCategories,
      'coupon' => $coupon,
      ]
    )
  @endforeach

@endsection
@push("scripts")
  <!-- button_openを押したらbutton_closeにis-blockを付与 -->
  <script src="{{ asset('js/memo_open.js') }}"></script>

  <!-- テーブルのヘッダ部分ソートスクリプト -->
  <script src="{{ asset('js/tableHeaderSort.js') }}"></script>

  <script>
    let createModal;
    let modalAreaOptions;
    let modalCloseOption;

    function openCreateModal() {
      createModal.classList.add('is-active');
    }
    function openEditModal(couponId) {
      document.getElementById(`modalAreaOption_edit_${couponId}`).classList.add('is-active');
    }
    function closeCreateModal() {
      createModal.classList.remove('is-active');
    }
    function closeEditModal(couponId) {
      document.getElementById(`modalAreaOption_edit_${couponId}`).classList.remove('is-active');
    }
    function deleteCoupon(couponId) {
      document.getElementById(`delete_${couponId}_form`).submit();
    }

    window.addEventListener('DOMContentLoaded', function() {
      createModal = document.getElementById('modalAreaOption_new_');
      modalAreaOptions = document.querySelectorAll('.modal_area');
      modalCloseOption = document.querySelectorAll('.modal_optionClose');
    })

    document.querySelectorAll('.l-table-list th .sort-enable').forEach(th => th.onclick = (e) => sortRows(e, '.l-table-list'));
  </script>
@endpush
