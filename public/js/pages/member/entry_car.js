document.addEventListener('DOMContentLoaded', function () {
  const BASE_PATH = document.getElementById('base_path').value;
  const flightNoElem = document.getElementById('flight_no');
  const arriveDateElem = document.getElementById('arrive_date');
  const airlineInputElem = document.getElementById('airline_id');
  const airlineNameElem = document.getElementById('airline_name');
  const depAirportNameElem = document.getElementById('dep_airport_name');
  const arrAirportNameElem = document.getElementById('arr_airport_name');
  const arriveTimeElem = document.getElementById('arrive_time');
  const unloadDateElem = document.getElementById('unload_date_plan');

  const carMakersElem = document.getElementById('car_maker_id');
  const carsElem = document.getElementById('car_id');
  const flightNoNotFoundElem = document.getElementById('flight_no_not_found');
  const arrivalFlgElems = Array.from(document.getElementsByClassName('arrival_flg'));

  $(carMakersElem).select2();
  $(carsElem).select2();
  $(airlineInputElem).select2();

  flightNoElem.addEventListener('change', function() {
    dispArrivalFlight()
  });
  $('#airline_id').on('change', function(e) {
    dispArrivalFlight()
  });
  arriveDateElem.addEventListener('change', function() {
    dispArrivalFlight()
    dispArrivalFlg()
  });
  $('#car_maker_id').on('change', function(e) {
    loadCars()
  });

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
        arriveTimeElem.textContent = loadTime.toFormat("HH:mm")
      } else {
        arriveTimeElem.textContent = '';
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
        arriveTimeElem.textContent = '';
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
            option.textContent = car.name;
            if(carId == car.id) {
              option.selected = true
            }
            carsElem.appendChild(option)
        });
        $('car_id').trigger('change');
    }
  }

  // 初期表示
  if(carsElem.value == '') {
    loadCars()
  }
  dispArrivalFlight()
  dispArrivalFlg()
});
