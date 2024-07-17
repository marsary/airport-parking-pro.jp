<!-- 受付入力1（受付予約・スタッフが入力） -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main l-container__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">予約管理TOP</li>
    <li class="l-breadcrumb__list">受付入力</li>
  </ul>

  @include('include.messages.errors')

  <div class="l-container__inner">
    <form action="{{route('manage.reserves.entry_info')}}" method="POST">
      @csrf
      <!-- BOX1 顧客検索 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">顧客検索</div>

        <div class="l-flex--start l-flex--item-end l-grid--gap2">
          <div class="l-grid l-grid--col2 l-grid--gap2">
            <div>
              <label for="tel">電話番号</label>
              <input type="tel" id="tel" name="tel" value="{{old('tel', $reserve->tel)}}" class="u-mb0">
            </div>
            <div>
              <label for="kana">ふりがな</label>
              <input type="text" id="kana" name="kana" value="{{old('kana', $reserve->kana)}}" class="u-mb0">
            </div>
          </div>

          <button id="search_btn" type="button" class="c-button--light-deep-gray u-w160 is-block">検索</button>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX2 顧客情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">顧客情報</div>

        <div>
          <div>
            <label for="name">氏名</label>
            <input type="text" id="name" name="name" value="{{old('name', $reserve->name)}}" class="c-form-input--w100">
          </div>
          <div>
            <label for="zip">郵便番号</label>
            <input type="text" id="zip" name="zip" value="{{old('zip', $reserve->zip)}}">
          </div>
          <div>
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" class="c-form-input--w100" value="{{old('email', $reserve->email)}}">
          </div>
          <!-- 領収書のあて名 -->
          <div>
            <label for="receipt_address">領収書のあて名</label>
            <input type="text" id="receipt_address" name="receipt_address" class="c-form-input--w100 u-mb0" value="{{old('receipt_address', $reserve->receipt_address)}}">
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right" id="member_infos">
          <div>顧客コード: </div>
          <div class="text-right" id="disp_member_code"></div>
          <div>予約コード: </div>
          <div class="text-right" id="disp_reserve_code"></div>
          <div>利用回数: </div>
          <div class="text-right" id="disp_used_num"></div>
          <div class="disp_tag_member">会員ランク: </div>
          <div class="text-right disp_tag_member"></div>
          {{--  <div>トラブル: </div>
          <div class="text-right">あり</div>
          <div>ラベル3: </div>
          <div class="text-right">ラベル3</div>
          <div>ラベル4: </div>
          <div class="text-right">ラベル4</div>  --}}
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX3 車両情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">車両情報</div>

        <div>
          <div class="l-grid--col2 l-grid--gap1">
            <div>
              <label for="maker">メーカー</label>
              <div class="c-form-select-color c-form-input--w100">
                <select name="car_maker_id" id="car_maker_id">
                  @foreach ($carMakers as $carMaker)
                    <option value="{{ $carMaker->id }}"
                      {{old('car_maker_id', $reserve->car_maker_id)==$carMaker->id ? 'selected':''}}>
                      {{$carMaker->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div>
              <label for="car">車種</label>
              <div class="c-form-select-color c-form-input--w100">
                <select id="car_id" name="car_id">
                  <option value="選択してください" disabled>選択してください</option>
                  @if (!empty(old('car_id', $reserve->car_id)))
                    @foreach ($cars as $car)
                      <option value="{{ $car->id }}"
                        data-size="{{$car->size_type}}"
                        {{old('car_id', $reserve->car_id)==$car->id ? 'selected':''}}>
                        {{$car->name }}
                      </option>
                    @endforeach
                  @else
                    <option value="" disabled></option>
                  @endif
                </select>
              </div>
            </div>
            <div>
              <label for="color">色</label>
              <div class="c-form-select-color c-form-input--w100">
                <select id="car_color_id" name="car_color_id">
                  <option value="選択してください" disabled>選択してください</option>
                  @foreach ($carColors as $carColor)
                    <option value="{{ $carColor->id }}"
                      {{old('car_color_id', $reserve->car_color_id)==$carColor->id ? 'selected':''}}>
                      {{$carColor->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div>
              <label for="car_number">ナンバー（※４桁の数字）</label>
              <input type="text" id="car_number" name="car_number" maxlength="4" minlength="4" class="c-form-input--w100" value="{{old('car_number', $reserve->car_number)}}">
            </div>
            <div>
              <!-- セレクト（取扱注意メモ） -->
              <label for="car_caution_ids">取扱注意メモ</label>
              <div class="c-form-select-color">
                <select name="car_caution_ids[]" id="car_caution_ids" class="u-mb0" multiple>
                  <option value="" disabled></option>
                  @foreach ($carCautions as $carCaution)
                    <option value="{{ $carCaution->id }}"
                      {{in_array($carCaution->id, old('car_caution_ids', $reserve->car_caution_ids)) ? 'selected':''}}>
                      {{$carCaution->name }}
                    </option>
                  @endforeach
                  {{--  <option value="1">MT車</option>
                  <option value="2">取扱注意メモ2</option>
                  <option value="3">取扱注意メモ3</option>  --}}
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">
          <div>車両サイズ: </div>
          <div class="text-right" id="disp_car_size"></div>
          {{--  <div>予約コード: </div>
          <div class="text-right">1234567890</div>
          <div>利用回数: </div>
          <div class="text-right">10</div>
          <div>会員ランク: </div>
          <div class="text-right">S</div>
          <div>トラブル: </div>
          <div class="text-right">あり</div>  --}}
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX4 予約情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">予約情報</div>
        <div class="l-grid--col2 l-grid--gap1">
          <div>
            <!-- ご利用人数（ご本人含む） -->
            <label for="num_members">ご利用人数（ご本人含む）</label>
            <input type="number" id="num_members" name="num_members" value="{{old('num_members', $reserve->num_members)}}"  class="c-form-input--w100">
          </div>
          <div>
          <label for="car_color_id">到着便航空会社</label>
          <div class="c-form-select-color">
            <select id="airline_id" name="airline_id">
              <option value="選択してください" disabled>選択してください</option>
              @foreach ($airlines as $airline)
                <option value="{{ $airline->id }}"
                  {{old('airline_id', $reserve->airline_id)==$airline->id ? 'selected':''}}>
                  {{$airline->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
          <div>
            <!-- 到着便 -->
            <label for="flight_no">到着便名</label>
            <input type="text" id="flight_no" name="flight_no" class="c-form-input--w100 u-mb025" value="{{old('flight_no', $reserve->flight_no)}}">
            <p id="flight_no_not_found" class="text-center hidden"></p>
          </div>
          <div>
            <!-- 到着日 -->
            <label for="arrive_date">到着日</label>
            <input type="hidden" id="unload_date_plan" value="{{old('unload_date_plan', $reserve->unload_date_plan)}}">
            <input type="date" id="arrive_date" name="arrive_date" class="c-form-input--w100 u-mb05" value="{{old('arrive_date', $reserve->arrive_date ?  $reserve->arrive_date->format('Y-m-d'): $reserve->unload_date_plan?->format('Y-m-d'))}}">
            <div class="c-label arrival_flg hidden">到着日とお迎え日が異なる</div>
          </div>
          <div>
            <!-- 到着時間 -->
            <label for="arrive_time">到着時間</label>
            <input type="time" id="arrive_time" name="arrive_time" class="c-form-input--w100"
             value="{{old('arrive_time', $reserve->arrive_time? \Carbon\Carbon::parse($reserve->arrive_time)->format('H:i') : null)}}"
            >
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">
          <div>航空会社名: </div>
          <div class="text-right" id="airline_name"></div>
          <div>出発空港: </div>
          <div class="text-right" id="dep_airport_name"></div>
          <div>到着空港: </div>
          <div class="text-right" id="arr_airport_name"></div>
          <div>到着予定時間: </div>
          <div class="text-right" id="arrive_time_flg"></div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX5 オプション選択 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">オプション選択</div>

        <div class="p-input-user-option__select c-button_optionSelect">

          <!-- select ボタン -->
          <div class="l-grid--col3 l-grid--gap1">

            <!-- オプションをクリックしたら出てくるmodal -->
            @foreach ($goodCategories as $goodCategory)
              <div class="c-button__select--gray button_select" onclick="openOptionModal({{$goodCategory->id}})">{{$goodCategory->name}}</div>
              @include('include.option.option', [
                'modalId' => $goodCategory->id,
                'goods' => $goodCategory->goods,
                'goodCategory' => $goodCategory,
                ]
              )
            @endforeach
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">
          <div id="checked-option-list">
            @if ($reserve->good_ids)
              @foreach ($reserve->good_ids as $good_id)
                @php
                  $good = $goodsMap[$good_id];
                @endphp
                <div class="c-button__remove item-container remove_good">
                  <img src="{{ asset('images/icon/removeButton.svg') }}" value="{{$good->id}}" width="16" height="16" class="button_remove">
                  {{$good->name}} ¥{{number_format($good->price)}}
                </div>
              @endforeach
            @endif
            {{--  <div class="c-button__remove item-container"><img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">手洗いWAX洗車 ¥2,500</div>
            <div class="c-button__remove item-container"><img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">iPhone充電ケーブル ¥1,200</div>  --}}
          </div>
          <input type="hidden" id="good_ids" name="good_ids" value="{{old('good_ids', implode(',', $reserve->good_ids))}}">
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX6 予約メモ -->
      <div class="p-input-user-option__box">
        <div class="u-font--20 u-font--medium u-font--green">予約メモ</div>
        <textarea name="reserve_memo" id="reserve_memo" cols="30" rows="5">{{old('reserve_memo', $reserve->reserve_memo)}}</textarea>
      </div>

      <div class="l-flex--center l-grid--gap2">
        <button type="button" id="returnButton" onclick="location.href='{{route('manage.reserves.entry_date')}}';" class="c-button__pagination--return">前のページに戻る</button>
        <button type="submit" class="c-button__pagination--next">予約内容確認</button>
        <button type="button" class="c-button__pagination--next">事前決済に進む</button>
        <img src="{{ asset('images/card_5brand.png') }}" height="16">
      </div>

    </form>

  </div><!-- /.l-container__inner -->
</main><!-- /.l-container__main -->

<!-- オプションをクリックしたら出てくるmodal -->
{{--  @include('include.option.option')  --}}

@endsection
@push("scripts")
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/ja.js') }}"></script>
<script src="{{ asset('js/commons/cars.js') }}"></script>
<script src="{{ asset('js/pages/manage/entry_info.js') }}"></script>
{{--  <script src="{{ asset('js/modalOption.js') }}"></script>
<script src="{{ asset('js/removeButton.js') }}"></script>  --}}
<script>
  const goodsMap = @js($goodsMap);
  let goodIds = @js($reserve->good_ids);
  let checkedOptionList = null;
  let goodIdsElem = null;
  function openOptionModal(modalId) {
    const modalAreaOption = document.getElementById('modalAreaOption' + modalId);
    modalAreaOption.classList.add('is-active');
  }
  function closeOptionModal(modalId) {
    const modalAreaOption = document.getElementById('modalAreaOption' + modalId);
    modalAreaOption.classList.remove('is-active');
  }

  function initOptionList()
  {
    if(goodIdsElem.value != '') {
        goodIds = goodIdsElem.value.split(',');
    } else {
        goodIdsElem.value = goodIds.join(',')
    }
    removeAllChildNodes(checkedOptionList)
    goodIds.forEach((goodId) => {
      const good = goodsMap[goodId];
      const div = document.createElement('div')
      const img = document.createElement('img')
      const span = document.createElement('span')
      div.classList.add("button__remove","item-container", "remove_good")
      // div.value = good.id
      img.src = "{{ asset('images/icon/removeButton.svg') }}"
      img.width = 16
      img.height = 16
      img.classList.add("button_remove")
      img.value = goodId;
      img.addEventListener('click', function() {
        removeOption(img);
      });

      span.textContent = good?.name + formatCurrency(good?.price, ' ¥');
      div.appendChild(img)
      div.appendChild(span)
      checkedOptionList.appendChild(div)
    })

  }

  function updateOptionList()
  {
    removeAllChildNodes(checkedOptionList)
    goodIds.forEach((goodId) => {
      const good = goodsMap[goodId];
      const div = document.createElement('div')
      const img = document.createElement('img')
      const span = document.createElement('span')
      div.classList.add("button__remove","item-container", "remove_good")
      // div.value = good.id
      img.src = "{{ asset('images/icon/removeButton.svg') }}"
      img.width = 16
      img.height = 16
      img.classList.add("button_remove")
      img.value = goodId;
      img.addEventListener('click', function() {
        removeOption(img);
      });

      span.textContent = good?.name + formatCurrency(good?.price, ' ¥');
      div.appendChild(img)
      div.appendChild(span)
      checkedOptionList.appendChild(div)
    })

    goodIdsElem.value = goodIds.join(',')
  }

  function addRemoveList(list, addingList, removingList = [])
  {
    list = Array.from(new Set([...list, ...addingList]));
    return list.filter(x => !removingList.includes(x));
  }

  function addOptions(modalId) {
    const modalAreaOption = document.getElementById('modalAreaOption' + modalId);
    const checkBoxList = modalAreaOption.querySelectorAll('input[type="checkbox"]');

    let addingIds = [];
    let removingIds = [];
    // オプション選択項目を更新する。
    checkBoxList.forEach((checkbox) => {
      const goodId = checkbox.value;
      if(checkbox.checked) {
        addingIds.push(goodId);
      } else {
        removingIds.push(goodId);
      }
    });

    goodIds = addRemoveList(goodIds, addingIds, removingIds);
    updateOptionList()
  }

  function removeOption(btnElem) {
    const removingId = btnElem.value
    goodIds = addRemoveList(goodIds, [], [removingId]);
    updateOptionList()

    const parent = btnElem.closest('.item-container');
    if (parent) {
      parent.remove();
    }
    document.getElementById('modal_good_ids_' + removingId).checked = false;
  }

  window.addEventListener('DOMContentLoaded', function() {
    checkedOptionList = document.getElementById('checked-option-list');
    goodIdsElem = document.getElementById('good_ids');
    const removeGoodBtnElems = Array.from(document.getElementsByClassName('button_remove'));

    removeGoodBtnElems.forEach((btnElem) => btnElem.addEventListener('click', function() {
      removeOption(btnElem);
    }))

    // 初期表示
    initOptionList()
  })
</script>
@endpush
@push('css')
<link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
<style>
</style>
@endpush
