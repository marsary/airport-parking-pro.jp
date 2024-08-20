const BASE_PATH = document.getElementById('base_path').value;
const loadedUnloaded = document.getElementById('disp_loaded_unloaded_check');
const loadTable = document.getElementById('loadTable');
const unloadTable = document.getElementById('unloadTable');

function removeTableData() {
    const trs = document.querySelectorAll('tr[data-href]');
    trs.forEach((tr, index) => {
        tr.remove()
    })
}
function addEventListnerToTrs() {
    const trs = document.querySelectorAll('tr[data-href]');
    trs.forEach((tr, index) => {
        tr.addEventListener('click', function(e) {
          if (!e.target.closest('a')) {
            window.location = tr.getAttribute('data-href');
          }
        });
    });
}
function getPaidInfo(deal) {
    let paidInfo = null;
    if(deal.payment_count > 0) {
        paidInfo = '<span class="c-label__blue">清算済み</span>'
    } else {
        paidInfo = '<span class="c-label__deep-gray">未清算</span>'
    }
    return paidInfo;
}
function getCarCautions(deal) {
    const carCautionList = []
    deal.car_caution_member_cars.forEach(car_caution_member_car => {
        carCautionList.push(car_caution_member_car.car_caution.name)
    });
    return carCautionList.join('<br />');
}
function getGoodsInfo(deal, senshaCategoryId) {
    let isFirstDispItem = true;
    let goodsInfo = '';
    deal.deal_goods.forEach(dealGood => {
        // good_category_idが1洗車のもの
        if(dealGood.good.good_category_id == senshaCategoryId) {
            if (!isFirstDispItem) {
                goodsInfo += '<br />'
            }
            goodsInfo += dealGood.good.name
            isFirstDispItem = false;
        }
    })
    return goodsInfo;
}

function createTrElem(href) {
    const tableRow = document.createElement('tr');
    tableRow.dataset.href = href
    return tableRow;
}

loadedUnloaded.addEventListener('change', async (e)=> {
    let url = BASE_PATH + `/manage/ledger/inventories`;
    if(loadedUnloaded.checked) {
        url += '?disp_loaded_unloaded=1'
    }
    const json = await apiRequest.get(url)
    if(json.success){
        removeTableData()

        // 入庫一覧
        json.loadDeals.forEach((loadDeal) => {
            const visitDatePlan = parseDateInput(loadDeal.visit_date_plan)
            const visitTimePlan = parseDateInput(loadDeal.visit_time_plan)
            const loadDate = parseDateInput(loadDeal.load_date)
            const unloadDate = parseDateInput(loadDeal.unload_date)
            const arriveTime = parseDateInput(loadDeal.arrival_flight?.arrive_time)
            const href = BASE_PATH + `/manage/deals/${loadDeal.id}`
            let paidInfo = getPaidInfo(loadDeal);
            const tableRow = createTrElem(href);
            tableRow.innerHTML = `
                <td>${paidInfo}</td>
                <td class="text-center">${loadDeal.status_label}</td>
                <td>${loadDeal.reserve_code ?? ''}</td>
                <td>${loadDeal.member_id ?? ''}</td>
                <td>${loadDeal.name ?? ''}</td>
                <td>${visitDatePlan?.toFormat('yyyy-M-dd') ?? ''}</td>
                <td>${visitTimePlan?.toFormat('HH:mm') ?? ''}</td>
                <td>${loadDate?.toFormat('M-dd') ?? ''}</td>
                <td>${unloadDate?.toFormat('M-dd') ?? ''}</td>
                <td>${loadDeal.num_days ?? ''}</td>
                <td>${loadDeal.arrival_flight?.flight_no ?? '-'}</td>
                <td>${arriveTime?.toFormat('HH:mm') ?? ''}</td>
                <td>${loadDeal.arrival_flight?.dep_airport?.name ?? ''}</td>
                <td>${loadDeal.member_car?.car.name ?? ''}</td>
                <td>${loadDeal.member_car?.number ?? ''}</td>
                <td>${loadDeal.member_car?.car_color.name ?? ''}</td>
                <td>${loadDeal.member.member_type?.name ?? ''}</td>
                <td>${loadDeal.dsc_rate ?? ''}</td>
                <td></td>
                <td></td>
                <td>${loadDeal.member.used_num ?? ''}</td>
                <td>${loadDeal.reserve_memo ?? ''}</td>
            `
            loadTable.appendChild(tableRow);
        })
        // 出庫一覧
        json.unloadDeals.forEach((unloadDeal) => {
            const arriveTime = parseDateInput(unloadDeal.arrival_flight?.arrive_time)
            const href = BASE_PATH + `/manage/deals/${unloadDeal.id}`
            let paidInfo = getPaidInfo(unloadDeal);
            const carCautions = getCarCautions(unloadDeal)

            let goodsInfo = getGoodsInfo(unloadDeal, json.senshaCategoryId);
            const tableRow = createTrElem(href);
            tableRow.innerHTML = `
                <td>${paidInfo}</td>
              <td class="text-center">${unloadDeal.status_label}</td>
              <td>${unloadDeal.office.name}</td>
              <td class="text-center"></td>
              <td>${unloadDeal.name ?? ''}</td>
              <td>${unloadDeal.member.used_num ?? ''}</td>
              <td>${unloadDeal.member_car?.number ?? ''}</td>
              <td>${unloadDeal.member_car?.car.name ?? ''}</td>
              <td>${unloadDeal.member_car?.car_color.name ?? ''}</td>
              <td>${unloadDeal.arrival_flight?.airport_terminal.terminal_id ?? ''}</td>
              <td>${unloadDeal.arrival_flight?.name ?? ''}</td>
              <td>${arriveTime?.toFormat('HH:mm') ?? ''}</td>
              <td></td>
              <td>${unloadDeal.arrival_flight?.dep_airport?.name ?? ''}</td>
              <td class="text-center">${unloadDeal.num_members ?? ''}</td>
              <td>
                ${goodsInfo}
              </td>
              <td></td>
              <td></td>
              <td>${unloadDeal.reserve_memo ?? ''}</td>
              <td></td>
              <td>${carCautions}</td>
              <td></td>
              <td></td>
              <td>${unloadDeal.receipt_code ?? ''}</td>
            `
            unloadTable.appendChild(tableRow);
        })

        addEventListnerToTrs();
    }
})
