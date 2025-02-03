document.addEventListener('DOMContentLoaded', function () {
  const BASE_PATH = document.getElementById('base_path').value;
  const flightNoElem = document.getElementById('flight_no');
  const arriveDateElem = document.getElementById('arrive_date');
  const airlineInputElem = document.getElementById('airline_id');
  const airlineNameElem = document.getElementById('airline_name');
  const depAirportNameElem = document.getElementById('dep_airport_name');
  const arrAirportNameElem = document.getElementById('arr_airport_name');
  const arriveTimeFlgElem = document.getElementById('arrive_time_flg');
  const arriveTimeInput = document.getElementById('arrive_time');
  const unloadDateElem = document.getElementById('unload_date_plan');

  const searchBtn = document.getElementById('search_btn');
  const telInput = document.querySelector('input[name=tel]')
  const kanaInput = document.querySelector('input[name=kana]')
  const nameInput = document.querySelector('input[name=name]')
  const zipInput = document.querySelector('input[name=zip]')
  const emailInput = document.querySelector('input[name=email]')
  const carColorSelect = document.getElementById('car_color_id');
  const carNumberInput = document.getElementById('car_number');
  const carCautionSelect = document.getElementById('car_caution_ids');
  const dispMemberCodeElem = document.getElementById('disp_member_code');
  const memberInfosElem = document.getElementById('member_infos');
  const dispUsedNumElem = document.getElementById('disp_used_num');
  const dispCarSizeElem = document.getElementById('disp_car_size');
  const carMakersElem = document.getElementById('car_maker_id');
  const carsElem = document.getElementById('car_id');
  const flightNoNotFoundElem = document.getElementById('flight_no_not_found');
  const arrivalFlgElems = Array.from(document.getElementsByClassName('arrival_flg'));

  $(carCautionSelect).select2({width:"calc(100% - 39px)"});
  $(carMakersElem).select2({width:"calc(100% - 39px)"});
  $(carsElem).select2({width:"calc(100% - 39px)"});
  $(airlineInputElem).select2({width:"calc(100% - 39px)"});

  searchBtn.addEventListener('click', function() {
    loadMember()
  });
  $('#airline_id').on('change', function(e) {
    dispArrivalFlight()
  });
  flightNoElem.addEventListener('change', function() {
    dispArrivalFlight()
  });
  arriveDateElem.addEventListener('change', function() {
    dispArrivalFlight()
    dispArrivalFlg()
  });
  $('#car_maker_id').on('change', async function(e) {
    await loadCars()
    setCarSize()
  });
//   carMakersElem.addEventListener('change', async function() {
//     await loadCars()
//     setCarSize()
//   });
$('#car_id').on('change', function(e) {
    setCarSize()
  });
//   carsElem.addEventListener('change', function() {
//     setCarSize()
//   });

  function dispArrivalFlg() {
    const arriveDate = luxon.DateTime.fromSQL(arriveDateElem.value);
    const unloadDate = luxon.DateTime.fromSQL(unloadDateElem.value);
    if(!arriveDate.isValid || !unloadDate.isValid) {
      return;
    }
    const isSameDate = arriveDate.hasSame(unloadDate, 'day')
    arrivalFlgElems.forEach((flgElem) => {
      if(isSameDate) {
        flgElem.classList.add('hidden')
      } else {
        flgElem.classList.remove('hidden')
      }
    })
  }

  async function loadMember(init = false) {
    const tel = telInput.value;
    const kana = kanaInput.value;

    if(tel == '' || kana == '') {
      if(!init) {
        alert('顧客情報を検索するには携帯番号とふりがなの両方を入力してください。')
      }
      return;
    }

    // フリガナ・電話番号をAPIに送信
    const json = await apiRequest.get(BASE_PATH + "/members/load_member",
      {kana:kana, tel:tel}
    )

    console.log(json); // `data.json()` の呼び出しで解釈された JSON データ
    if(json.success){
      console.log(json.data);
      if(json.data.member == null) {
        if(!init) {
          alert('入力された顧客情報が存在しません。')
          dispMemberCodeElem.textContent = ""
          dispUsedNumElem.textContent = ""
          document.querySelectorAll('.disp_tag_member').forEach(e => e.remove());
          dispCarSizeElem.textContent = ""
          carColorSelect.value = ""
          carNumberInput.value = ""
        }
        return;
      }

      if(!init) {
        nameInput.value = json.data.member.name
        zipInput.value = json.data.member.zip
        emailInput.value = json.data.member.email
      }
      dispMemberCodeElem.textContent = json.data.member.member_code
      dispUsedNumElem.textContent = json.data.member.used_num

      document.querySelectorAll('.disp_tag_member').forEach(e => e.remove());
      if(json.data.member.tagMembers != '') {
        json.data.member.tagMembers.forEach(tagMember => {
          const labelDiv = document.createElement('div')
          const tagDiv = document.createElement('div')
          labelDiv.classList.add('disp_tag_member')
          tagDiv.classList.add('disp_tag_member')
          labelDiv.textContent = tagMember.label.name
          tagDiv.textContent = tagMember.tag.name
          memberInfosElem.appendChild(labelDiv)
          memberInfosElem.appendChild(tagDiv)
        })
      }

      if(Array.isArray(json.data.member.member_cars) && json.data.member.member_cars[0] != undefined) {
        dispCarSizeElem.textContent = carSizeLabel(json.data.member.member_cars[0].car.size_type)

        if(!init) {
          carMakersElem.value = json.data.member.member_cars[0].car.car_maker_id
          carColorSelect.value = json.data.member.member_cars[0].car_color_id
          carNumberInput.value = json.data.member.member_cars[0].number
          if(json.data.member.car_caution_ids.length > 0) {
            updateCarCaution(json.data.member.car_caution_ids)
          }
          await loadCars()
          carsElem.value = json.data.member.member_cars[0].car_id
          setCarSize()
        }
      }

    }
  }

  function updateCarCaution(carCuationIds = []) {
    $(carCautionSelect).select2("val", carCuationIds);
  }

  // 到着便情報の表示
  async function dispArrivalFlight() {
    const flightNo = flightNoElem.value;
    const arriveDate = arriveDateElem.value;
    const airlineId = airlineInputElem.value;

    if(flightNo == '' || arriveDate == '' || airlineId == '') {
        return;
      }

    // 到着便・到着日をAPIに送信
    const json = await apiRequest.get(BASE_PATH + "/arrival_flights/get_info",
      {flight_no:flightNo, arrive_date:arriveDate, airline_id:airlineId}
    )

    console.log(json); // `data.json()` の呼び出しで解釈された JSON データ
    if(json.success){
      // 到着便情報の取得
      console.log(json.data);
      // ⑧	航空会社名 を表示
      airlineNameElem.textContent = json.data.arrivalFlight.airlineName
      // ⑨	出発空港 を表示
      depAirportNameElem.textContent = json.data.arrivalFlight.depAirportName
      // ⑩	到着空港 を表示
      arrAirportNameElem.textContent = json.data.arrivalFlight.arrAirportName
      // ⑪	到着予定時間 を表示
      const loadTime = luxon.DateTime.fromISO(json.data.arrivalFlight.arriveTime);
      if(loadTime.isValid) {
        arriveTimeFlgElem.textContent = "あり"
        arriveTimeInput.value = loadTime.toFormat("HH:mm")
      } else {
        arriveTimeFlgElem.textContent = "なし"
      }
      flightNoNotFoundElem.classList.add('hidden')

    } else {
      flightNoNotFoundElem.textContent = '指定の到着便名が見つかりません。'
      flightNoNotFoundElem.classList.remove('hidden')
      // ⑧	航空会社名 を表示
      airlineNameElem.textContent = '';
      // ⑨	出発空港 を表示
      depAirportNameElem.textContent = '';
      // ⑩	到着空港 を表示
      arrAirportNameElem.textContent = '';
      // ⑪	到着予定時間 を表示
      arriveTimeFlgElem.textContent = ""
      arriveTimeInput.value = ""
    }
  }

  async function loadCars() {
    // 選択メーカーIDをAPIに送信
    // 車種リストの取得
    const carMakerId = carMakersElem.value
    const carId = carsElem.value
    const json = await apiRequest.get(BASE_PATH + `/car_makers/${carMakerId}/cars`)
    if(json.success){
      while (carsElem.options.length > 1) carsElem.remove(1);
        // car_id の select のオプションを更新する。
        json.data.cars.forEach((car) => {
            const option = document.createElement('option')
            option.value = car.id;
            option.dataset.size = car.size_type;
            option.textContent = car.name;
            if(carId == car.id) {
              option.selected = true
            }
            carsElem.appendChild(option)
        });
        $('car_id').trigger('change');
    }
  }

  function setCarSize() {
    const sizeType = carsElem.options[carsElem.selectedIndex]?.getAttribute('data-size')
    dispCarSizeElem.textContent = carSizeLabel(sizeType) ?? ''

  }

  // 初期表示
  if(carsElem.value == '') {
    const initCarData = async () => {
        await loadCars()
        setCarSize()
    };
    initCarData()
  }
  dispArrivalFlight()
  dispArrivalFlg()
  loadMember(true)
});
