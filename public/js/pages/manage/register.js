
window.addEventListener('DOMContentLoaded', function() {
    const BASE_PATH = document.getElementById('base_path').value;
    const couponData = JSON.parse(document.getElementById('couponData').value);
    const calculator = new Calculator(document.querySelector('input[name="symbol"]:checked').value);
    const registerSubtotalsSection = document.getElementById('register_subtotals');
    const registerReceivedItemsSection = document.getElementById('register_received_items');
    const registerTotalAmountDisp = document.getElementById('register_total_amount');
    const registerTotalChangeDisp = document.getElementById('register_total_change');
    const couponApplyButton = document.querySelector('.apply_button');
    const couponSelect = document.getElementById('coupon');
    const discountInput = document.getElementById('discount');
    const adjustmentInput = document.getElementById('adjustment');
    const paymentMethodDiscountInput = document.getElementById('paymentMethod_discount');
    const paymentMethodAdjustmentInput = document.getElementById('paymentMethod_adjustment');
    const paymentMethodCashInput = document.getElementById('paymentMethod_cash');
    const paymentMethodCreditInput = document.getElementById('paymentMethod_credit');
    const paymentMethodEmoneyInput = document.getElementById('paymentMethod_emoney');
    const paymentMethodQrcodeInput = document.getElementById('paymentMethod_qrcode');
    const paymentMethodCertificateInput = document.getElementById('paymentMethod_certificate');
    const paymentMethodTravelInput = document.getElementById('paymentMethod_travel');
    const paymentMethodVoucherInput = document.getElementById('paymentMethod_voucher');
    const paymentMethodOtherInput = document.getElementById('paymentMethod_other');
    const enterButton = document.getElementById('enterButton');
    const paymentSubmitButton = document.getElementById('paymentSubmitButton');
    const paymentSubmitForm = document.getElementById('payment_submit_form');
    const entryTypeInputList = document.querySelectorAll('.entryType');

    /**
     * @type  {PaymentData} paymentData
     */
    let paymentData;
    document.querySelectorAll('input[name=symbol]').forEach(elem => {
        elem.addEventListener('click', () => {
            calculator.inputNumPad(elem.value);
            paymentData.isDirty = true
            updateItemAndRender()
        })
    })

    document.querySelectorAll('.numpad_key').forEach(elem => {
        elem.addEventListener('click', () => {
            calculator.inputNumPad(elem.dataset.key);
            paymentData.isDirty = true
            updateItemAndRender()
        })
    })

    document.getElementById('modal_open').addEventListener('click', () => {
        paymentData = initPaymentData();
        initpaymentMethodInputs(paymentData);
        togglePaymentSubmitButton();
        // 初期表示
        renderPaymentTable();
    })

    document.getElementById('optionInfosSaved').addEventListener('change', () => {
        togglePaymentSubmitButton();
    })

    couponApplyButton.addEventListener('click', async() => {
        const couponId = couponSelect.value
        paymentData.updateCouponItem(couponId)
        renderPaymentTable();
        togglePaymentSubmitButton();
    })

    enterButton.addEventListener('click', () => {
        paymentData.confirmInput();
        togglePaymentSubmitButton();
        // 初期表示
        renderPaymentTable();
    })

    paymentSubmitButton.addEventListener('click', (e) => {
        e.preventDefault();
        handlePaymentSubmit();

    })

    function handlePaymentSubmit() {
        if(paymentData.canSubmit()) {
            appendDataToFormElem(paymentSubmitForm, paymentData.toFormParams())
            paymentSubmitForm.submit();
        } else {
            return false;
        }
    }

    function togglePaymentSubmitButton() {
        if(paymentData == null) {
            return;
        }
        if(paymentData.canSubmit()) {
            paymentSubmitButton.disabled = false;
            paymentSubmitButton.classList.remove('--disabled2');
        } else {
            paymentSubmitButton.classList.add('--disabled2');
            paymentSubmitButton.disabled = true;
        }
    }

    /**
     *
     * @param {PaymentData} paymentData
     */
    function initpaymentMethodInputs(paymentData) {
        adjustmentInput.addEventListener('click', () => {
            initpaymentMethodInput(adjustmentInput,paymentData, null, null)
        })
        discountInput.addEventListener('click', () => {
            initpaymentMethodInput(discountInput,paymentData, null, null)
        })
        paymentMethodDiscountInput.addEventListener('change', () => {
            discountInput.checked = true;
            initpaymentMethodDiscountInput(discountInput, paymentMethodDiscountInput, paymentData, PaymentMethodTypes.discount, paymentMethodDiscountInput.options[paymentMethodDiscountInput.selectedIndex].text)
        })
        paymentMethodCashInput.addEventListener('click', () => {
            initpaymentMethodInput(paymentMethodCashInput,paymentData, PaymentMethodTypes.cash,'cash')
        })
        paymentMethodCreditInput.addEventListener('change', () => {
            initpaymentMethodInput(paymentMethodCreditInput,paymentData, PaymentMethodTypes.credit,paymentMethodCreditInput.options[paymentMethodCreditInput.selectedIndex].text)
        })
        paymentMethodEmoneyInput.addEventListener('change', () => {
            initpaymentMethodInput(paymentMethodEmoneyInput,paymentData, PaymentMethodTypes.electronicMoney,paymentMethodEmoneyInput.options[paymentMethodEmoneyInput.selectedIndex].text)
        })
        paymentMethodQrcodeInput.addEventListener('change', () => {
            initpaymentMethodInput(paymentMethodQrcodeInput,paymentData, PaymentMethodTypes.qrCode,paymentMethodQrcodeInput.options[paymentMethodQrcodeInput.selectedIndex].text)
        })
        paymentMethodCertificateInput.addEventListener('click', () => {
            initpaymentMethodInput(paymentMethodCertificateInput,paymentData, PaymentMethodTypes.giftCertificates,'giftCertificates')
        })
        paymentMethodTravelInput.addEventListener('change', () => {
            initpaymentMethodInput(paymentMethodTravelInput,paymentData, PaymentMethodTypes.travelAssistance,paymentMethodTravelInput.options[paymentMethodTravelInput.selectedIndex].text)
        })
        paymentMethodVoucherInput.addEventListener('change', () => {
            initpaymentMethodInput(paymentMethodVoucherInput,paymentData, PaymentMethodTypes.voucher,paymentMethodVoucherInput.options[paymentMethodVoucherInput.selectedIndex].text)
        })
        paymentMethodOtherInput.addEventListener('change', () => {
            initpaymentMethodInput(paymentMethodOtherInput,paymentData, PaymentMethodTypes.others,paymentMethodOtherInput.options[paymentMethodOtherInput.selectedIndex].text)
        })
    }

    /**
     *
     * @param {HTMLElement} elem
     * @param {PaymentData} paymentData
     * @param {string|null} selectedType
     * @param {string|null} selectedItemName
     */
    function initpaymentMethodInput(elem, paymentData, selectedType,selectedItemName) {
        uncheckSelectedMethods (elem);
        if(paymentMethodTypeIsChecked(elem)) {
            paymentData.setSelectedItem(selectedType, selectedItemName)
            // updateItemAndRender()
            renderPaymentTable(true);
        } else {
            if(paymentData.selectedItemName == selectedItemName) {
                paymentData.setSelectedItem(null, null);
                // paymentData.removeRegisterItem(selectedItemName);
                paymentData.confirmInput();
                renderPaymentTable();
            }
        }
    }

    /**
     *
     * @param {HTMLElement} checkElem
     * @param {HTMLElement} selectElem
     * @param {PaymentData} paymentData
     * @param {string|null} selectedType
     * @param {string|null} selectedItemName
     */
    function initpaymentMethodDiscountInput(checkElem, selectElem, paymentData, selectedType,selectedItemName) {
        uncheckSelectedMethods (checkElem, selectElem);
        if(paymentMethodTypeIsChecked(checkElem) && paymentMethodTypeIsChecked(selectElem)) {
            paymentData.setSelectedItem(selectedType, selectedItemName)
            renderPaymentTable(true);
        } else {
            if(paymentData.selectedItemName == selectedItemName) {
                paymentData.setSelectedItem(null, null);
                paymentData.confirmInput();
                renderPaymentTable();
            }
        }
    }

    function uncheckSelectedMethods (...elems) {
        entryTypeInputList.forEach(entryTypeInput => {
            if (elems.some(elem => elem.id == entryTypeInput.id)) {
            }else if(entryTypeInput.checked) {
                entryTypeInput.checked = false
            }else if(entryTypeInput.value != '') {
                entryTypeInput.value = ''
            }
        })
    }

    function paymentMethodTypeIsChecked(elem) {
        if(elem.tagName === 'SELECT') {
            if(elem.value != '') {
                return true;
            } else {
                return false;
            }
        }
        else if(elem.tagName === 'INPUT' && elem.type === 'checkbox') {
            if(elem.checked) {
                return true;
            } else {
                return false;
            }
        }
    }

    function getSelectedPaymentMethod() {
        let paymentMethodName;
        let paymentMethodType;
        entryTypeInputList.forEach(elem => {
            if(elem.tagName === 'SELECT' && elem.value != '') {
                paymentMethodName = elem.options[elem.selectedIndex].text;
                paymentMethodType = getPaymentMethodTypeFromId(elem.id)
                return;
            }
            if(elem.tagName === 'INPUT' && elem.type === 'checkbox' && elem.checked) {
                paymentMethodName = getPaymentMethodTypeFromId(elem.id)
                paymentMethodType = getPaymentMethodTypeFromId(elem.id)
                return;
            }
        })
        return { paymentMethodName:paymentMethodName, paymentMethodType:paymentMethodType }
    }

    function initPaymentData() {
        const categoryPaymentDetailMap = JSON.parse(document.getElementById('categoryPaymentDetailMap').value);
        let appliedCoupons = {}
        let appliedDiscounts = {}
        let appliedAdjustments = {}
        if(document.getElementById('appliedCoupons').value != '') {
            appliedCoupons = JSON.parse(document.getElementById('appliedCoupons').value);
        }
        if(document.getElementById('appliedDiscounts').value != '') {
            appliedDiscounts = JSON.parse(document.getElementById('appliedDiscounts').value);
        }
        if(document.getElementById('appliedAdjustments').value != '') {
            appliedAdjustments = JSON.parse(document.getElementById('appliedAdjustments').value);
        }
        const reducedSubTotal = parseInt(document.getElementById('reducedSubTotalInput').value) || 0;
        const reducedTax = parseInt(document.getElementById('reducedTaxInput').value) || 0;
        const subTotal = parseInt(document.getElementById('subtotalInput').value) || 0;
        const tenPercentTax = parseInt(document.getElementById('taxInput').value) || 0;
        const taxExempt = parseInt(document.getElementById('taxExemptInput').value) || 0;
        const originalTotalChange = parseInt(document.getElementById('originalTotalChange').value) || 0;
        const originalTotalPayInput = parseInt(document.getElementById('originalTotalPay').value) || 0;

        //小計(税抜)
        const subtotal = reducedSubTotal + subTotal + taxExempt
        // 消費税
        const tax = reducedTax + tenPercentTax
        // お支払い合計（税込）
        const totalAmount = originalTotalPayInput ?? subtotal + tax;
        const {paymentMethodName, paymentMethodType} = getSelectedPaymentMethod();

        return new PaymentData(
            BASE_PATH,
            subtotal,
            tax,
            originalTotalChange,
            totalAmount,
            couponData,
            calculator,
            paymentMethodType,
            paymentMethodName,
            renderPaymentTable,
            categoryPaymentDetailMap,
            appliedDiscounts,
            appliedAdjustments,
            appliedCoupons,
        );
    }

    function renderPaymentTable(updating = false) {
        removeAllChildNodes(registerSubtotalsSection)
        removeAllChildNodes(registerReceivedItemsSection)
        registerSubtotalsSection
        const subtotalItems = paymentData.makeSubtotalItems();
        subtotalItems.forEach(item => {
            registerSubtotalsSection.appendChild(item)
        })
        const receivedItems = paymentData.makeReceivedItems();
        receivedItems.forEach(item => {
            registerReceivedItemsSection.appendChild(item)
        })
        if(!updating) {
            registerTotalAmountDisp.innerHTML = formatCurrency(paymentData.totalAmount) + '<span class="u-font-yen">円</span>';
            registerTotalChangeDisp.innerHTML = formatCurrency(paymentData.totalChange) + '<span class="u-font-yen">円</span>';
        }
    }

    function updateItemAndRender() {
        paymentData.updateItem();
        renderPaymentTable(true);
    }


});


class PaymentData {

    isDirty = false;
    calculator
    BASE_PATH
    // 利用可能な割引クーポンデータ
    couponData = {}
    // 適用クーポン couponId => 割引額
    appliedCoupons = {}
    // 値引き itemName => 値引き額
    discount = {}
    // 調整 itemName => 調整額
    adjustment = {}

    //小計(税抜)
    subtotal = 0
    // 消費税 調整前
    originalTax = 0
    // 消費税 調整後
    tax = 0

    // お支払い合計（税込）
    totalAmount = 0
    // お釣り
    totalChange = 0
    // 実際の支払額合計
    totalPay = 0


    // お預かり
    // 現金
    cash
    // クレジット
    credit = {}
    // 電子マネー
    electronicMoney = {}
    // QRコード
    qrCode = {}
    // 商品券
    giftCertificates
    // 旅行支援
    travelAssistance = {}
    // バウチャー
    voucher = {}
    // その他
    others = {}

    // 支払方法のうちリスト形式のデータ
    listPaymentTypes = [
        'credit',
        'electronicMoney',
        'qrCode',
        'travelAssistance',
        'voucher',
        'others',
    ]

    renderPaymentTable

    // 入力中データ
    selectedType
    selectedItemName

    constructor(
        BASE_PATH,
        subtotal,
        tax,
        totalChange,
        totalAmount,
        couponData,
        calculator,
        selectedType,
        selectedItemName,
        renderPaymentTable,
        categoryPaymentDetailMap = {},
        appliedDiscounts,
        appliedAdjustments,
        appliedCoupons = {},
    ) {
        this.BASE_PATH = BASE_PATH
        this.subtotal = subtotal
        this.originalTax = tax
        this.tax = tax
        this.totalChange = totalChange
        this.totalAmount = totalAmount
        this.couponData = couponData
        this.appliedCoupons = appliedCoupons
        this.discount = appliedDiscounts
        this.adjustment = appliedAdjustments
        this.calculator = calculator
        this.selectedType = selectedType
        this.selectedItemName = selectedItemName
        this.renderPaymentTable = renderPaymentTable

        if(Object.keys(categoryPaymentDetailMap).length > 0) {
            Object.keys(categoryPaymentDetailMap).forEach((category) => {
                const detail = categoryPaymentDetailMap[category];
                if(this.listPaymentTypes.includes(category)) {
                    Object.keys(detail).forEach((itemName) => {
                        this[category][itemName] = detail[itemName];
                    });
                } else {
                    this[category] = detail;
                }
            })
        }
        this.sumTotals();
    }

    confirmInput() {
        this.sumTotals()
        this.isDirty = false
    }

    discountTotal() {
        const total = Object.keys(this.discount).reduce(
            (accumulator, discountName) => accumulator + parseInt(this.discount[discountName]),
            0,
          );
        return parseInt(total) || 0;
    }

    adjustmentTotal() {
    }

    adjustTax() {
        // クーポンは常に10％消費税に対応する

        let couponDiscount = 0;
        Object.keys(this.appliedCoupons).forEach(couponId => {
            const value = this.appliedCoupons[String(couponId)];
            couponDiscount += (parseInt(value) || 0);
        })

        // 元の消費税額 - (クーポン) * 10%
        this.tax = parseInt(this.originalTax - (parseInt(couponDiscount * 0.1)));

        console.log(this.discount);
        let appliedDiscountAmount = 0;
        // 適用値引き itemName => 値引き額
        Object.keys(this.discount).forEach((discountName) => {
            const value = this.discount[discountName];
            appliedDiscountAmount += (parseInt(value) || 0) * this.#getDiscountRate(discountName);
        })
        let appliedAdjustmentAmount = 0;
        // 適用調整 itemName => 調整額
        Object.keys(this.adjustment).forEach((adjustmentName) => {
            const value = this.adjustment[adjustmentName];
            appliedAdjustmentAmount += (parseInt(value) || 0) * this.#getDiscountRate(adjustmentName);
        })

        // クーポン分控除後の消費税額 - (値引き・調整消費税の合計)
        this.tax = parseInt(this.tax - (parseInt(appliedDiscountAmount) - parseInt(appliedAdjustmentAmount)));
    }

    sumTotals() {
        this.adjustTax();

        this.totalAmount = (parseInt(this.subtotal) || 0) + (parseInt(this.tax) || 0) - this.discountTotal() + this.adjustmentTotal();
        this.totalPay = (parseInt(this.cash) || 0) + (parseInt(this.giftCertificates) || 0)
        for(const paymentType of this.listPaymentTypes) {
            Object.keys(this[paymentType]).forEach(key => {
                const value = this[paymentType][key];
                this.totalPay += (parseInt(value) || 0);
            })
        }
        Object.keys(this.appliedCoupons).forEach(couponId => {
            const value = this.appliedCoupons[String(couponId)];
            this.totalAmount -= (parseInt(value) || 0);
        })
        if(this.totalPay - this.totalAmount > 0) {
            this.totalChange = this.totalPay - this.totalAmount;
        } else {
            this.totalChange = 0;
        }
    }

    canSubmit() {
        return !this.isDirty && this.totalPay >= this.totalAmount;
    }

    setSelectedItem(selectedType, selectedItemName, shouldClear = true) {
        this.selectedType = selectedType
        this.selectedItemName = selectedItemName
        if(shouldClear) {
            this.calculator.clear()
        }
        this.isDirty = true
    }

    updateItem() {
        if(this.hasOwnProperty(this.selectedItemName)) {
            return this[this.selectedItemName] = this.calculator.result
        }
        if(this.hasOwnProperty(this.selectedType)) {
            return this[this.selectedType][this.selectedItemName] = this.calculator.result
            // if(this[paymentType].hasOwnProperty(this.selectedItemName)) {
            // }
        }
    }

    updateCouponItem(couponId) {
        const coupon = this.getCoupon(couponId);

        // 併用可否フラグ のチェック
        const invalidated = Object.keys(this.appliedCoupons).some(otherId => {
            const otherCoupon = this.getCoupon(otherId);
            // 併用可否フラグ 0：不可、1：可
            if(otherCoupon.combination_flg == 0) {
                return true;
            }
            if(otherId != couponId && coupon.combination_flg == 0) {
                return true;
            }

        })
        if(invalidated)  return;

        const price = calcActualYen(coupon.discount_type, coupon.discount_amount, this.subtotal);
        this.appliedCoupons[String(couponId)] = price;

        this.sumTotals()
    }

    updatePercentCoupons() {
        Object.keys(this.appliedCoupons).forEach(couponId => {
            const coupon = this.getCoupon(couponId);
            if(coupon.discount_type == DiscountTypes.PERCENT) {
                const price = calcActualYen(coupon.discount_type, coupon.discount_amount, this.subtotal);
                this.appliedCoupons[String(couponId)] = price;
            }
        })
    }

    makeSubtotalItems() {
        const items = [];
        //小計(税抜)
        items.push(this.makeItemContainer('小計', this.subtotal))
        // 値引き
        Object.keys(this.discount).forEach(discountName => {
            const price = this.discount[discountName];
            items.push(this.makeItemContainerWithRemoveButton(discountName, discountName, price))
        })
        // クーポン
        Object.keys(this.appliedCoupons).forEach(couponId => {
            const coupon = this.getCoupon(couponId);
            const price = this.appliedCoupons[String(couponId)];
            let name = coupon.name
            if(coupon.discount_type == DiscountTypes.PERCENT) {
                name += ' (' + coupon.discount_amount + '%)'
            }
            items.push(this.makeItemContainerWithRemoveButton(coupon.name, name, price))
        })
        // 調整
        Object.keys(this.adjustment).forEach(adjustmentName => {
            const price = this.adjustment[adjustmentName];
            items.push(this.makeItemContainerWithRemoveButton(adjustmentName, adjustmentName, price))
        })
        // 消費税
        items.push(this.makeItemContainer('消費税', this.tax))

        return items;
    }

    makeReceivedItems() {
        const items = [];
        // 現金
        if(this.cash != null) {
            items.push(this.makeItemContainerWithRemoveButton('cash', '現金', this.cash))
        }
        // クレジット
        this.#handleListFormakeReceivedItems(items, this.credit)
        // 電子マネー
        this.#handleListFormakeReceivedItems(items, this.electronicMoney)
        // QRコード
        this.#handleListFormakeReceivedItems(items, this.qrCode)
        // 商品券
        if(this.giftCertificates != null) {
            items.push(this.makeItemContainerWithRemoveButton('giftCertificates', '商品券', this.giftCertificates))
        }
        // 旅行支援
        this.#handleListFormakeReceivedItems(items, this.travelAssistance)
        // バウチャー
        this.#handleListFormakeReceivedItems(items, this.voucher)
        // その他
        this.#handleListFormakeReceivedItems(items, this.others)

        return items;
    }

    #handleListFormakeReceivedItems(items, list = {}) {
        if(Object.keys(list).length > 0) {
            Object.keys(list).forEach(key => {
                items.push(this.makeItemContainerWithRemoveButton(key, key, list[key]))
            })
        }
    }

    makeItemContainer(label, price) {
        // const template = `
        // <div class="p-register-checkout__item item-container">
        //     <div>消費税</div>
        //     <div class="p-register-checkout__price">0,000<span class="u-font-yen">円</span></div>
        // </div>
        // `
        const el = document.createElement('div');
        el.classList.add("item-container","p-register-checkout__item")
        const div = document.createElement('div')
        div.textContent = label;
        const div2 = document.createElement('div')
        div2.classList.add("p-register-checkout__price")
        div2.innerHTML = formatCurrency(price) + '<span class="u-font-yen">円</span>';
        el.appendChild(div)
        el.appendChild(div2)
        return el;
    }

    makeItemContainerWithRemoveButton (itemName, label, price) {
        // const template = `
        // <div class="item-container p-register-checkout__item">
        //     <div class="c-button__remove ">
        //         <img src="{{ asset('images/icon/removeButton.svg') }}" width="16" height="16" class="button_remove">現金
        //     </div>
        //     <div class="p-register-checkout__price">0,000<span class="u-font-yen">円</span></div>
        // </div>
        // `
        const el = document.createElement('div');
        el.classList.add("item-container","p-register-checkout__item")
        const div = document.createElement('div')
        const img = document.createElement('img')
        const span = document.createElement('span')
        div.classList.add("c-button__remove")
        img.src = this.BASE_PATH + "/images/icon/removeButton.svg"
        img.width = 16
        img.height = 16
        img.classList.add("button_remove")
        img.value = itemName;
        img.addEventListener('click', () => {
            this.removeRegisterItem(itemName);
            this.confirmInput();
            this.renderPaymentTable()
            if(itemName == this.selectedItemName) {
                this.calculator.clear()
            }
        });
        span.textContent = label;
        div.appendChild(img)
        div.appendChild(span)
        const div2 = document.createElement('div')
        div2.classList.add("p-register-checkout__price")
        div2.innerHTML = formatCurrency(price) + '<span class="u-font-yen">円</span>';
        el.appendChild(div)
        el.appendChild(div2)
        return el;
    }

    removeRegisterItem(itemName) {
        if(this.hasOwnProperty(itemName)) {
            this[itemName] = null
            return;
        }
        for(const paymentType of this.listPaymentTypes) {
            if(this[paymentType].hasOwnProperty(itemName)) {
                delete this[paymentType][itemName]
                return;
            }
        }
        if(this.discount.hasOwnProperty(itemName)) {
            delete this.discount[itemName]
            return;
        }
        if(this.adjustment.hasOwnProperty(itemName)) {
            delete this.adjustment[itemName]
            return;
        }
        Object.keys(this.appliedCoupons).forEach(couponId => {
            const coupon = this.getCoupon(couponId);
            if(coupon?.name == itemName) {
                delete this.appliedCoupons[String(couponId)];
                return
            }
        })

    }

    /**
     *
     * @param {string} itemName
     */
    #getDiscountRate(itemName) {
        if(itemName.includes('8%')) {
            return 0.08;
        }
        if(itemName.includes('10%')) {
            return 0.1;
        }
        return 0;
    }

    /**
     *
     * @param {number} couponId
     * @returns {{string:any}}
     */
    getCoupon(couponId) {
        return this.couponData[couponId]
    }

    toFormParams() {
        return {
            appliedCoupons: this.appliedCoupons,
            subtotal: this.subtotal,
            discount: this.discount,
            adjustment: this.adjustment,
            tax: this.tax,
            totalAmount: this.totalAmount,
            totalChange: this.totalChange,
            totalPay: this.totalPay,
            cash: this.cash ?? '',
            credit: this.credit,
            electronicMoney: this.electronicMoney,
            qrCode: this.qrCode,
            giftCertificates: this.giftCertificates ?? '',
            travelAssistance: this.travelAssistance,
            voucher: this.voucher,
            others: this.others,
        };
    }
}

class Calculator {
    inputNumber = 0
    operator = '+'

    constructor(symbol = 'plus') {
        if(symbol == 'plus') {
            this.operator = '+';
        } else {
            this.operator = '-';
        }
    }

    clear() {
        this.inputNumber = 0
    }

    get result() {
        return this.operator + this.inputNumber
    }

    inputNumPad(key) {
        switch (key) {
            case '0':
            case '1':
            case '2':
            case '3':
            case '4':
            case '5':
            case '6':
            case '7':
            case '8':
            case '9':
                this.inputNumber = this.inputNumber * 10 + parseInt(key)
                break;
            case '00':
                this.inputNumber = this.inputNumber * 100
                break;
            case 'Del':
                this.inputNumber = parseInt(this.inputNumber / 10)
                break;
            case 'C':
                this.clear()
                break;
            case 'plus':
                this.operator = '+'
                break;
            case 'minus':
                this.operator = '-'
                break;
            default:
                break;
        }
        console.log(this.result)

    }

}

const PaymentMethodTypes = Object.freeze({
    discount:'discount',
    appliedCoupons:'appliedCoupons',
    adjustment:'adjustment',
    cash:'cash',
    credit:'credit',
    electronicMoney:'electronicMoney',
    qrCode:'qrCode',
    giftCertificates:'giftCertificates',
    travelAssistance:'travelAssistance',
    voucher:'voucher',
    others:'others',
});

function getPaymentMethodTypeFromId(id) {
    switch (id) {
        case 'discount':
            return PaymentMethodTypes.discount;
        case 'appliedCoupons':
            return PaymentMethodTypes.appliedCoupons;
        case 'adjustment':
            return PaymentMethodTypes.adjustment;
        case 'paymentMethod_cash':
            return PaymentMethodTypes.cash;
        case 'paymentMethod_credit':
            return PaymentMethodTypes.credit;
        case 'paymentMethod_emoney':
            return PaymentMethodTypes.electronicMoney;
        case 'paymentMethod_qrcode':
            return PaymentMethodTypes.qrCode;
        case 'paymentMethod_certificate':
            return PaymentMethodTypes.giftCertificates;
        case 'paymentMethod_travel':
            return PaymentMethodTypes.travelAssistance;
        case 'paymentMethod_voucher':
            return PaymentMethodTypes.voucher;
        case 'paymentMethod_other':
            return PaymentMethodTypes.others;
        default:
            break;
    }
}
