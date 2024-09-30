luxon.Settings.defaultLocale = 'ja';
luxon.Settings.defaultWeekSettings = { firstDay: 7, minimalDays: 1, weekend: [6, 7] }

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
 * @returns {Array}
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

/**
 *
 * @param {HTMLFormElement} form
 * @param {Object} data
 */
function appendDataToFormElem(form, data) {
    Object.keys(data).forEach(inputName => {
        inputValue = data[inputName];
        if(isObject(inputValue)) {
            Object.keys(inputValue).forEach(objectKey => {
                const input = document.createElement('input');//prepare a new input DOM element
                input.setAttribute('name', inputName + '[' + objectKey + ']');//set the param name
                input.setAttribute('value', inputValue[objectKey]);//set the value
                input.setAttribute('type', 'hidden')//set the type, like "hidden" or other
                form.appendChild(input);
            })
        } else {
            const input = document.createElement('input');//prepare a new input DOM element
            input.setAttribute('name', inputName);//set the param name
            input.setAttribute('value', inputValue);//set the value
            input.setAttribute('type', 'hidden')//set the type, like "hidden" or other
            form.appendChild(input);
        }
    });
}


function isObject(obj)
{
    return obj != null && obj.constructor.name === "Object"
}

function parseDateInput(inputValue) {
    if(inputValue == null) return;

    let dateObj = luxon.DateTime.fromISO(inputValue)
    if(!dateObj.isValid) {
        dateObj = luxon.DateTime.fromFormat(inputValue, 'yyyy-MM-dd HH:mm:ss')
    }
    return dateObj;
}

function asObject(value = []) {
    if(isObject(value)) {
        return value;
    }
    if(Array.isArray(value)) {
        return { ...value }
    }
    return {};
}
