/**
 * do an ajax post
 * @param {string} url 
 * @param {object} body 
 * @param {function handleResponse(data)} callback 
 */
function ajaxPost(url, body, callback) {
    fetch(url, {
        method: "post",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(body),
    })
        .then(response => response.json())
        .then(data => { callback(data) })
}

/**
 * 
 * @param {string} url 
 * @param {function handleResponse(data)} callback 
 */
function ajaxGet(url, callback) {
    fetch(url, {
        method: "get",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then(response => response.json())
        .then(data => { callback(data) })
}

/**
 * 
 * @param {string} url 
 * @param {function handleResponse(data)} callback 
 */
function ajaxGetView(url, callback) {
    fetch(url, {
        method: "get",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then(response => response.json())
        .then(data => { callback(data["view"]) })
}

/**
 * 
 * @param {string} url 
 * @param {object} body 
 * @param {function} callback 
 */
function ajaxDelete(url, body, callback) {
    fetch(url, {
        method: "delete",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(body)
    })
        .then(response => response.json())
        .then(data => { callback(data) })
}

/**
 * 
 * @param {object} data : data resposne from a controller which includes the csrf token and csrf value 
 */
function updateCSRF(data) {
    // get the hidden field to reset the csrf field
    let hiddenField = document.getElementsByName(data["csrf_token"])[0];
    if (hiddenField) {
        hiddenField.setAttribute("name", data["csrf_token"]);
        hiddenField.setAttribute("value", data["csrf_value"]);
    }
}

/**
 * 
 * @param {*} csrf_token : the name of the hidden field
 * @returns the CSRF hidden field key-value
 */
function getCSRFHiddenFieldValue(csrf_token) {
    let hiddenField = document.getElementsByName(csrf_token)[0];
    let hiddenFieldValue = hiddenField.getAttribute("value");

    let returnValue = {};

    returnValue[csrf_token] = hiddenFieldValue;

    return returnValue;
}

