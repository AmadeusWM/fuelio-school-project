<div class="d-flex justify-content-center">
    <div id="registration-container" class="hover-box">
        <form id="registration-form-container">
            <h1>Log In</h1>
            <input id="input-email" type="email" name="email" class="form-control registration-input" placeholder="name@example.com">
            <input id="input-password" type="password" class="form-control registration-input" placeholder="Password">
        </form>
        <ul class="registration-buttons">
            <button onclick="location.href='/register'" class="btn btn-outline-primary w-100 registration-button">Sign Up</button>
            <button id="sign-in-button" class="btn btn-primary w-100 registration-button" type="submit">Log In</button>
        </ul>
    </div>
</div>
<script>
    let button = document.getElementById("sign-in-button");
    button.addEventListener('click', register, false);

    function register() {
        fetch("<?= base_url('/registration/login') ?>", {
                method: "post",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify(getJSONInput()),
            })
            .then(response => response.json())
            .then(data => {
                handleResponse(data)
            });
    }

    /**
     * get the input data from the html
     */
    function getJSONInput() {
        let inputEmail = document.getElementById("input-email");
        let inputPassword = document.getElementById("input-password");
        // get the value of the hidden field for csrf
        let hiddenField = document.getElementsByName("<?= csrf_token() ?>")[0];
        let hiddenFieldValue = hiddenField.getAttribute("value");

        let input = {
            email: inputEmail.value,
            password: inputPassword.value,
            "<?= csrf_token() ?>": hiddenFieldValue
        }

        return input;
    }

    function handleResponse(data) {
        console.log(data);

        // register fulfilled => show login
        if (data["fulfilled"] == true) {
            location.assign("<?= base_url("/login") ?>");
            return;
        }

        // when register failed, show errors
        let errors = data["validation_errors"]

        let html = "";

        for (err in errors) {
            html += "<li class='link-danger m-2'>" + errors[err] + "</li>"
        }

        document.getElementById("errors-validation").innerHTML = html;
        // get the hidden field to reset the csrf field
        let hiddenField = document.getElementsByName("<?= csrf_token() ?>")[0];
        hiddenField.setAttribute("name", data["csrf_token"]);
        hiddenField.setAttribute("value", data["csrf_value"]);
    }
</script>