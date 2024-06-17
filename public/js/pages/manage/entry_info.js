document.addEventListener('DOMContentLoaded', function () {
  const BASE_PATH = document.getElementById('base_path').value;
  const flightNoElem = document.getElementById('flight_no');
  const arriveDateElem = document.getElementById('arrive_date');
  const airlineNameElem = document.getElementById('airline_name');
  const depAirportNameElem = document.getElementById('dep_airport_name');
  const arrAirportNameElem = document.getElementById('arr_airport_name');
  const arriveTimeFlgElem = document.getElementById('arrive_time_flg');
  const arriveTimeInput = document.getElementById('arrive_time');
  const unloadDateElem = document.getElementById('unload_date_plan');

  const carCautionSelect = document.getElementById('car_caution_ids');
  const arrivalFlgElems = Array.from(document.getElementsByClassName('arrival_flg'));

  $(carCautionSelect).select2();

  flightNoElem.addEventListener('change', function() {
    dispArrivalFlight()
  });
  arriveDateElem.addEventListener('change', function() {
    dispArrivalFlight()
    dispArrivalFlg()
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

    if(flightNo == '' || arriveDate == '') {
      return;
    }

    // 到着便・到着日をAPIに送信
    const json = await apiRequest.get(BASE_PATH + "/arrival_flights/get_info",
      {flight_no:flightNo, arrive_date:arriveDate}
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

    }
  }

  dispArrivalFlight()
  dispArrivalFlg()
});
