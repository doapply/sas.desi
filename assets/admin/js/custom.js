
function setFormValue(data, formId, replaceData = null) {
    let form = document.getElementById(formId);
    let elements = form.querySelectorAll("input, select, textarea");

    Array.from(elements).forEach(element => {
        let elementName = element.getAttribute('name');
        let elementType = element.getAttribute('type');
        if (elementType != "hidden" && elementType != 'password' && elementType != 'checkbox') {
            if (replaceData != null && typeof replaceData == 'object' && replaceData[elementName]) {
                elementName = replaceData[elementName]
            }
            element.value = data[elementName];
        }
        if (elementType == 'password') {
            element.closest('.form-group').classList.add('d-none');
        }
    });

};

$('.date, .time-picker').on('keypress keyup change paste', function (e) {
    return false;
});

$('.export-item').on('change', function (e) {
    let value = $(this).val();
    if (value == 'custom') {
        let item = prompt('How many items interested to export');
        item = parseInt(item);
        if (!item) {
            $(this).find(`option[value=10]`).attr('selected', true);
            return false;
        }
        if (isNaN(item)) {
            notify('error', "Only a number is allowed");
            return false;
        }

        let maxItem = parseInt($(this).attr('max-item') || 100);

        if (item >= maxItem) {
            notify('error', `Max export item is ${maxItem}`);
            item = maxItem;
        }

        let items = [
            10,
            50,
            100,
            maxItem
        ];


        if (items.indexOf(item) == -1) {
            items.push(item)
        }

        let option = "";

        items.forEach(element => {
            option += `<option value="${element}" ${element == item ? 'selected' : ''}>${element}</option>`
        });
        option += `<option value="custom">Custom</option>`;
        $(this).html(option)
    }
});

function optionFind(value) {
    let elementLength = $('.export-item').find(`option[value=${value}]`).length;
    if (elementLength > 0) {
        return true;
    } else {
        return false
    }
}

//status value selected when search status or url has status params
if (window.location.href.indexOf('status') != -1) {
    let queryString = window.location.search;
    let urlParams = new URLSearchParams(queryString);
    let status = urlParams.get('status');
    document.querySelector(['select[name=status]']).value = status || '';
}



