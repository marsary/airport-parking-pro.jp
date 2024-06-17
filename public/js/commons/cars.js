// 1:普通車, 2:大型車
const SizeTypes = Object.freeze({
    MEDIUM:1,
    LARGE:2,
});


function carSizeLabel(sizeType) {
    if(sizeType == SizeTypes.MEDIUM) {
        return '普通車';
    } else if(sizeType == SizeTypes.LARGE) {
        return '大型車';
    }
}
