const TaxTypes = Object.freeze({
    EIGHT_PERCENT: 1,
    TEN_PERCENT: 2,
    EXEMPT: 3
});

function calcTax(taxType, price = null) {
    switch (Number(taxType)) {
        case TaxTypes.EIGHT_PERCENT:
            return parseInt(price * 0.08);
            break;
        case TaxTypes.TEN_PERCENT:
            return parseInt(price * 0.1);
            break;
        case TaxTypes.EXEMPT:
            return price;
            break;
        default:
            return price
            break;
    }
}
