luxon.Settings.defaultLocale = 'ja';

function formatCurrency(number, prefix, suffix) {
    if(isNaN(number)) {
        return prefix + 0 + suffix;
    }
    return (prefix ?? '') + Number(number).toLocaleString() + (suffix ?? '');
}

function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}
