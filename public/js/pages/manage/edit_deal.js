document.addEventListener('DOMContentLoaded', function () {
  const BASE_PATH = document.getElementById('base_path').value;
  const carCautionSelect = document.getElementById('car_caution_ids');
  const carMakersElem = document.getElementById('car_maker_id');
  const carsElem = document.getElementById('car_id');
  const loadDateInput = document.getElementById('load_date')
  const loadTimeInput = document.getElementById('load_time')
  const unloadDatePlanInput = document.getElementById('unload_date_plan')
  const dealId = document.getElementById('deal_id').value

  $(carMakersElem).select2();
  $(carsElem).select2();
  $(carCautionSelect).select2();

  $('#car_maker_id').on('change', function(e) {
    loadCars()
  });

  loadDateInput.addEventListener('click', function() {
    location.href = BASE_PATH + `/manage/deals/${dealId}/entry_date`
  });
  loadTimeInput.addEventListener('click', function() {
    location.href = BASE_PATH + `/manage/deals/${dealId}/entry_date`
  });
  unloadDatePlanInput.addEventListener('click', function() {
    location.href = BASE_PATH + `/manage/deals/${dealId}/entry_date`
  });

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
        $('car_id').trigger('change')
    }
  }


  // 初期表示
  if(carsElem.value == '') {
    loadCars()
  }
});
