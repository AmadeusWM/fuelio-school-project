class AjaxHandler {
    static CSRF_token = "";
    static CSRF_value = "";

    static setToken(token) {
        this.CSRF_token = token;
    }

    /**
     * do an ajax post
     * @param {string} url 
     * @param {object} body 
     * @param {function handleResponse(data)} callback 
     */
    static ajaxPost(url, body, callback) {
        fetch(url, {
            method: "post",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(this.appendCSRF(body)),
        })
            .then(response => response.json())
            .then(data => {
                this.updateCSRF(data);
                callback(data);
            }).catch(error => {
                alert(error, "Refresh the page");
            })
    }

    /**
     * 
     * @param {string} url 
     * @param {function handleResponse(data)} callback 
     */
    static ajaxGet(url, callback) {
        fetch(url, {
            method: "get",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then(response => response.json())
            .then(data => { callback(data) })
            .catch(error => {
                alert(error, "Refresh the page");
            })
    }

    /**
     * 
     * @param {string} url 
     * @param {function handleResponse(data)} callback 
     */
    static ajaxGetView(url, callback) {
        fetch(url, {
            method: "get",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then(response => response.json())
            .then(data => { callback(data["view"]); })
            .catch(error => {
                alert(error, "Refresh the page");
            })
    }

    /**
     * 
     * @param {string} url 
     * @param {object} body 
     * @param {function} callback 
     */
    static ajaxDelete(url, body, callback) {
        fetch(url, {
            method: "post",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(this.appendCSRF(body))
        })
            .then(response => response.json())
            .then(data => {
                this.updateCSRF(data);
                callback(data);
            })
            .catch(error => {
                alert(error, "Refresh the page");
            })
    }

    /**
     * 
     * @param {object} data : data resposne from a controller which includes the csrf token and csrf value 
     */
    static updateCSRF(data) {
        // get the hidden field to reset the csrf field
        document.getElementsByName(data["csrf_token"]).forEach((hiddenField) => {
            hiddenField.setAttribute("name", data["csrf_token"]);
            hiddenField.setAttribute("value", data["csrf_value"]);
        });
    }

    /**
     * 
     * @returns the CSRF hidden field key-value, use `...` to unpack it
     */
    static getCSRFHiddenFieldValue() {
        let hiddenField = document.getElementsByName(this.CSRF_token)[0];
        let hiddenFieldValue = hiddenField.getAttribute("value");

        let hiddenFieldParams = {};

        hiddenFieldParams[this.CSRF_token] = hiddenFieldValue;

        return hiddenFieldParams;
    }


    /**
     * 
     * @param {*} body : body to which the csrf hidden field values will be added
     * @returns body : body with csrf hidden field values added
     */
    static appendCSRF(body) {
        return { ...body, ...this.getCSRFHiddenFieldValue() };
    }
}