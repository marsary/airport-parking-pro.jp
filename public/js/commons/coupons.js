const DiscountTypes = Object.freeze({
    YEN:1,
    PERCENT:2,
});

function calcActualYen(discounType, discountAmount, price = null) {
    if(discounType == DiscountTypes.YEN) {
        return discountAmount;
    } else if(discounType == DiscountTypes.PERCENT) {
        return parseInt(price * discountAmount / 100);
    }
}
