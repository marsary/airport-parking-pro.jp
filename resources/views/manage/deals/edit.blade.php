<!-- B-3-4 予約編集 -->
@extends('layouts.manage.authenticated')

@section('content')
<main class="l-wrap__main">
  <!-- パンくず -->
  <ul class="l-wrap__breadcrumb l-breadcrumb">
    <li class="l-breadcrumb__list">予約編集</li>
  </ul>

  @include('include.messages.errors')

  <div class="l-container__inner">
    <form id="editForm" action="{{route('manage.deals.update', [$reserve->deal->id])}}" method="POST" class="l-container__button-fixed-parent">
      @method('PUT')
      @csrf
      <input type="hidden" id="deal_id" value="{{$reserve->deal->id}}">
      <!-- BOX1 予約情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">予約情報</div>

        <div class="l-grid l-grid--col2 l-grid--cgap2">
          <div>
            <label for="load_date">入庫日</label>
            <input type="text" id="load_date" name="load_date" value="{{old('load_date', $reserve->load_date?->format('Y-m-d'))}}" class="u-w-full-wide" readonly>
          </div>
          <div>
            <label for="load_time">入庫時間</label>
            <input type="text" id="load_time" name="load_time" value="{{old('load_time', $reserve->load_time? \Carbon\Carbon::parse($reserve->load_time)->format('H:i') : null)}}" class="u-w-full-wide" readonly>
          </div>
          <div>
            <label for="unload_date_plan">出庫日</label>
            <input type="text" id="unload_date_plan" name="unload_date_plan" value="{{old('unload_date_plan', $reserve->unload_date_plan?->format('Y-m-d'))}}" class="u-w-full-wide" readonly>
          </div>
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
          <div class="l-grid--col2-auto l-grid--cgap1">
            <div>
              <label for="kana">ふりがな</label>
              <input type="text" id="kana" name="kana" value="{{old('kana', $reserve->kana)}}" class="c-form-input--w100">
            </div>
            <div>
              <label for="zip">郵便番号</label>
              <input type="text" id="zip" name="zip" value="{{old('zip', $reserve->zip)}}">
            </div>
            <div>
              <label for="tel">携帯番号</label>
              <input type="tel" id="tel" name="tel" value="{{old('tel', $reserve->tel)}}" class="c-form-input--w100">
            </div>
            <div>
              <label for="email">メールアドレス</label>
              <input type="text" id="email" name="email" value="{{old('email', $reserve->email)}}" class="c-form-input--w100">
            </div>
          </div>
          <!-- 領収書のあて名 -->
          <div>
            <label for="receipt_address">領収書のあて名</label>
            <input type="text" id="receipt_address" name="receipt_address" value="{{old('receipt_address', $reserve->receipt_address)}}" class="c-form-input--w100 u-mb0">
          </div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- 到着情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">到着予定</div>

        <div>
          <input type="hidden" name="airline_id" value="{{old('airline_id', $reserve->airline_id)}}">
          <div>
            <label for="arrive_date">到着予定日</label>
            <input type="date" id="arrive_date" name="arrive_date" value="{{old('arrive_date', $reserve->arrive_date?->format('Y-m-d'))}}" class="c-form-input--w100 u-mb05">
          </div>
          <div>
            <label for="arrive_time">到着予定時間</label>
            <input type="time" id="arrive_time" name="arrive_time" value="{{old('arrive_time', $reserve->arrive_time? \Carbon\Carbon::parse($reserve->arrive_time)->format('H:i') : null)}}" class="c-form-input--w100">
          </div>
          <div>
            <label for="flight_no">到着便</label>
            <input type="text" id="flight_no" name="flight_no" value="{{old('flight_no', $reserve->flight_no)}}" class="c-form-input--w100">
          </div>
        </div>
      </div>

      <!-- BOX3 車両情報 -->
      <div class="p-input-user-option__box--col3">
        <div class="u-font--20 u-font--medium u-font--green">車両情報</div>

        <div>
          <div>
            <div class="l-grid--col2 l-grid--gap1">
              <div>
                <label for="car_maker_id">メーカー</label>
                <div class="c-form-select-color">
                  <select name="car_maker_id" id="car_maker_id" class="u-mb0">
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
                <!-- 車種 -->
                <label for="car_id">車種</label>
                <div class="c-form-select-color">
                  <select id="car_id" name="car_id" class="u-mb0">
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
                <label for="car_number">ナンバー（※４桁の数字）</label>
                <input type="text" id="car_number" name="car_number" value="{{old('car_number', $reserve->car_number)}}" class="c-form-input--w100" maxlength="4" minlength="4">
              </div>
              <div>
                <!-- 色 -->
                <label for="car_color_id">色</label>
                <div class="c-form-select-color">
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
                {{--  <input type="text" id="car_color_id" name="car_color_id" value="{{old('car_color_id', $reserve->car_color_id)}}" class="c-form-input--w100">  --}}
              </div>
              <div>
                <label for="num_members">ご利用人数（ご本人含む）</label>
                <input type="number" id="num_members" name="num_members" value="{{old('num_members', $reserve->num_members)}}" class="c-form-input--w100">
              </div>
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
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="p-input-user-option__box--col3__right">

        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- オプション選択 -->
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
                  $goodNum = $reserve->good_nums[$good_id] ?? 1;
                @endphp
                <div class="c-button__remove item-container remove_good">
                  <img src="{{ asset('images/icon/removeButton.svg') }}" value="{{$good->id}}" width="16" height="16" class="button_remove">
                  {{$good->name}} ¥{{number_format($good->price * $goodNum)}}
                </div>
              @endforeach
            @endif
          </div>
          <input type="hidden" id="good_ids" name="good_ids" value="{{old('good_ids', implode(',', $reserve->good_ids))}}">
          <input type="hidden" id="good_nums" name="good_nums" value="{{old('good_nums', json_encode($reserve->good_nums))}}">
          <div>
          </div>
        </div>
      </div><!-- /.l-reception-input__box -->

      <!-- BOX6 予約メモ -->
      <div class="p-input-user-option__box u-border--none">
        <div class="u-font--20 u-font--medium u-font--green">顧客メモ</div>
        <textarea name="member_memo" id="member_memo" cols="30" rows="3">{{old('member_memo', $reserve->member_memo)}}</textarea>
        <div class="u-font--20 u-font--medium u-font--green">予約メモ</div>
        <textarea name="reserve_memo" id="reserve_memo" cols="30" rows="3">{{old('reserve_memo', $reserve->reserve_memo)}}</textarea>
        <div class="u-font--20 u-font--medium u-font--green">受付メモ</div>
        <textarea name="reception_memo" id="reception_memo" cols="30" rows="3">{{old('reception_memo', $reserve->reception_memo)}}</textarea>
      </div>
    </form>

  </div><!-- /.l-container__inner -->

  <div class="l-container__button-fixed">
    <div class="c-button-group__form">
      <button type="submit" form="editForm" name="cancel_btn" value="1" class="c-button__clear">キャンセル</button>
      <button type="submit" form="editForm" class="c-button__submit" onclick="return confirm('本当に予約内容を変更しますか。') ? true:false;">予約内容を更新</button>
    </div>
  </div>

</main><!-- /.l-container__main -->

@endsection
@push("scripts")
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/ja.js') }}"></script>
<script src="{{ asset('js/pages/member/option_select.js') }}"></script>
<script src="{{ asset('js/pages/manage/edit_deal.js') }}"></script>
<script>
  const goodsMap = @js($goodsMap);
  let goodIds = @js($reserve->good_ids);
  let goodNums = asObject(@js($reserve->good_nums));
  let checkedOptionList = null;
  let goodIdsElem = null;
  let goodNumsElem = null;

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
      const goodNum = goodNums[goodId] ?? 0;
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

      span.textContent = good?.name + formatCurrency(good?.price * goodNum, ' ¥');
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
      const goodNum = goodNums[goodId] ?? 0;
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

      span.textContent = good?.name + formatCurrency(good?.price * goodNum, ' ¥');
      div.appendChild(img)
      div.appendChild(span)
      checkedOptionList.appendChild(div)
    })

    goodIdsElem.value = goodIds.join(',')
    goodNumsElem.value = JSON.stringify(goodNums);
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
        addGoodNums(goodId)
      } else {
        removingIds.push(goodId);
        removeGoodNums(goodId)
      }
    });

    goodIds = addRemoveList(goodIds, addingIds, removingIds);
    updateOptionList()
  }


  function updateOptionQuantity(goodId) {
    addGoodNums(goodId)
    updateOptionList()
  }

  function addGoodNums(goodId) {
    const modalGoodNumElem = document.getElementById('modal_good_nums_' + goodId);
    goodNums[goodId] = (modalGoodNumElem.value != '') ? modalGoodNumElem.value:0;
  }
  function removeGoodNums(goodId) {
    delete goodNums[goodId];
  }

  function removeOption(btnElem) {
    const removingId = btnElem.value
    goodIds = addRemoveList(goodIds, [], [removingId]);
    removeGoodNums(removingId)
    updateOptionList()

    const parent = btnElem.closest('.item-container');
    if (parent) {
      parent.remove();
    }
    document.getElementById('modal_good_ids_' + removingId).checked = false;
    const modalGoodNumElem = document.getElementById('modal_good_nums_' + removingId);
    modalGoodNumElem.value = 0;
    updateTotalAmount(modalGoodNumElem)
  }

  window.addEventListener('DOMContentLoaded', function() {
    checkedOptionList = document.getElementById('checked-option-list');
    goodIdsElem = document.getElementById('good_ids');
    goodNumsElem = document.getElementById('good_nums');
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
<link rel="stylesheet" href="{{ asset('css/custom-select2.css') }}">
<style>
</style>
@endpush
