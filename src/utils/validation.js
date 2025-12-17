const validationRules = {
    'nom': {
        regex: /^[a-zA-Z\s'-]{2,50}$/,
        message: "Name must be 2-50 letters only."
    },
    'description': {
        regex: /^[a-zA-Z\s'-]{2,50}$/,
        message: "Role must be 2-50 letters only."
    },
    'email': {
        regex: /^[\w.-]+@youcode.[A-Za-z]{2,}$/,
        message: "Invalid email format."
    },
    'phone': {
        regex: /^\+212[67]\d{8}$/,
        message: "Phone must be 8-15 digits."
    }

};

export function imageUrlValidation(url) {
    return url.match(validationRules['imageUrl'].regex) ? true : false;
}

export function validateDate(dateFrom , dateTo) {
    return dateFrom <= dateTo ? true : false;
}

function toggleError(field, show, message = '') {
    if (show) {
        field.classList.add("border-red-500")
        field.nextElementSibling.classList.remove('hidden');
        field.nextElementSibling.textContent = message;
    } else {
        field.classList.remove("border-red-500")
        field.nextElementSibling.classList.add('hidden');
        field.nextElementSibling.textContent = '';
    }
}

function validateField(field, value) {
    const rule = validationRules[field.name];
    if (value == '') {
        toggleError(field, true, 'field is required');
        return false;
    }
    if (!rule.regex.test(value)) {
        toggleError(field, true, rule.message);
        return false;
    }
    toggleError(field, false);
    return true;
}

function validateForm() {
    let valid = true

    let inputs = document.querySelectorAll('#form-fields input ');

    for (let input of inputs) {
        if (!validateField(input.name, input.value)) {
            valid = false
        }
    }
    return valid
}