
window.addEventListener('DOMContentLoaded', function() {
    const BASE_PATH = document.getElementById('base_path').value;
    const couponData = JSON.parse(document.getElementById('couponData').value);
    const calculator = new Calculator(document.querySelector('input[name="symbol"]:checked').value);
    const registerSubtotalsSection = document.getElementById('register_subtotals');
    const registerReceivedItemsSection = document.getElementById('register_received_items');
    const registerTotalAmountDisp = document.getElementById('register_total_amount');
    const registerTotalChangeDisp = document.getElementById('register_total_change');
    const couponApplyButton = document.querySelector('.apply_button');
    const discountInput = document.getElementById('discount');
    const adjustmentInput = document.getElementById('adjustment');
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

    function initpaymentMethodInputs(paymentData) {
        adjustmentInput.addEventListener('click', () => {
            initpaymentMethodInput(adjustmentInput,paymentData, PaymentMethodTypes.adjustment,'adjustment')
        })
        discountInput.addEventListener('click', () => {
            initpaymentMethodInput(discountInput,paymentData, PaymentMethodTypes.discount,'discount')
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

    function uncheckSelectedMethods (elem) {
        entryTypeInputList.forEach(entryTypeInput => {
            if(elem.id == entryTypeInput.id) {
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
            categoryPaymentDetailMap
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
    // 割引クーポン
    couponData = {}
    appliedCoupons = {}

    //小計(税抜)
    subtotal = 0
    // 値引き
    discount
    // 調整
    adjustment
    // 消費税
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
        categoryPaymentDetailMap = {}
    ) {
        this.BASE_PATH = BASE_PATH
        this.subtotal = subtotal
        this.tax = tax
        this.totalChange = totalChange
        this.totalAmount = totalAmount
        this.couponData = couponData
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

    sumTotals() {
        this.totalAmount = (parseInt(this.subtotal) || 0) + (parseInt(this.tax) || 0) - (parseInt(this.discount) || 0) + (parseInt(this.adjustment) || 0);
        this.totalPay = (parseInt(this.cash) || 0) + (parseInt(this.giftCertificates) || 0)
        for(const paymentType of this.listPaymentTypes) {
            Object.keys(this[paymentType]).forEach(key => {
                const value = this[paymentType][key];
                this.totalPay += (parseInt(value) || 0);
            })
        }
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

    makeSubtotalItems() {
        const items = [];
        //小計(税抜)
        items.push(this.makeItemContainer('小計', this.subtotal))
        // 値引き
        if(this.discount != null) {
            items.push(this.makeItemContainerWithRemoveButton('discount', '値引き', this.discount))
        }
        // 調整
        if(this.adjustment != null) {
            items.push(this.makeItemContainerWithRemoveButton('adjustment', '調整', this.adjustment))
        }
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

    }

    toFormParams() {
        return {
            appliedCoupons: this.appliedCoupons,
            subtotal: this.subtotal,
            discount: this.discount ?? '',
            adjustment: this.adjustment ?? '',
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
