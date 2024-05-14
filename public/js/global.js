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

function getToUrl(url = null, params = {}, withQueryStrings = true) {

    url = url ? url + '?' : location.pathname + '?';
    if(withQueryStrings) {
        searchParams = new URLSearchParams(window.location.search);
    } else {
        searchParams = new URLSearchParams();
    }
    Object.keys(params).forEach((key) => {
        if (Array.isArray(params[key])) {
            searchParams.append(key, params[key].join(","));
        } else {
            searchParams.set(key, params[key])
        }
    })

    location.href = url + searchParams.toString();
};

/**
 *
 * @param {HTMLTableElement} tableElem
 * @param {string} checkboxName
 * @returns
 */
function getTableCheckedRowValues(tableElem, checkboxName) {
    let valueArray = [];

    const checkedBoxes = tableElem.querySelectorAll('input[name=' + checkboxName + ']:checked');
    checkedBoxes.forEach(checkbox => {
        valueArray.push(checkbox.value)
    });

    return valueArray;
}

/**
 *
 * @param {HTMLTableElement} tableElem
 * @param {string} checkboxName
 * @param {boolean} shouldCheck
 */
function checkAllCheckboxes(tableElem, checkboxName, shouldCheck) {
    const checkedBoxes = tableElem.querySelectorAll('input[name=' + checkboxName + ']');
    checkedBoxes.forEach(checkbox => {
        checkbox.checked = shouldCheck;
    });
}
