document.addEventListener('DOMContentLoaded', function () {
  const BASE_PATH = document.getElementById('base_path').value;
  const airlineInputElem = document.getElementById('airline_id');
  const depAirportElem = document.getElementById('dep_airport_id');
  const arrAirportElem = document.getElementById('arr_airport_id');
  const carMakersElem = document.getElementById('car_maker_id');
  const carsElem = document.getElementById('car_id');
  const carCautionSelect = document.getElementById('car_caution_ids');

  $(airlineInputElem).select2();
  $(depAirportElem).select2();
  $(arrAirportElem).select2();
  $(carMakersElem).select2();
  $(carsElem).select2();
  $(carCautionSelect).select2();

  $('#car_maker_id').on('change', function(e) {
    loadCars()
  });

  async function loadCars() {
    // 選択メーカーIDをAPIに送信
    // 車種リストの取得
    let carMakerId = carMakersElem.value
    if(carMakerId == '') carMakerId = 'all'
    const carId = carsElem.value
    const json = await apiRequest.get(BASE_PATH + `/car_makers/${carMakerId}/cars?default_all=1`)
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


});
