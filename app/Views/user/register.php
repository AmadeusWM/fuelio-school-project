<div class="d-flex justify-content-center">
    <div id="registration-container" class="hover-box">
        <h1>Sign Up!</h1>
        <form id="registration-form-container">
            <!-- hidden csrf field to protect against common attacks (https://www.codeigniter.com/user_guide/tutorial/create_news_items.html)-->
            <?= csrf_field() ?>
            <input id="input-email" type="email" name="email" min="1" max="128" class="form-control registration-input" placeholder="name@example.com" required>
            <input id="input-username" type="text" name="username" min="4" max="128" class="form-control registration-input" placeholder="Username" required>
            <input id="input-password" type="password" name="password" min="4" max="256" class="form-control registration-input" placeholder="Password" required>
            <input id="input-confirmpassword" type="password" name="confirmpassword" min="4" max="256" class="form-control registration-input" placeholder="Confirm Password" required>
        </form>
        <ul id="errors-validation" class="errors-validation"></ul>
        <div class="registration-buttons">
            <button onclick="location.href='/login'" class="btn btn-outline-primary w-100 registration-button">Log In</button>
            <button id="sign-up-button" class="btn btn-primary w-100 registration-button" type="submit">Sign Up</button>
        </div>
    </div>
</div>
<script>
    AjaxHandler.setToken("<?= csrf_token() ?>");

    let button = document.getElementById("sign-up-button");
    button.addEventListener('click', register, false);

    function register() {
        AjaxHandler.ajaxPost("<?= base_url('/SignUpController/register') ?>",
            getJSONInput(),
            handleResponse)
    }

    /**
     * get the input data from the html
     */
    function getJSONInput() {
        let inputEmail = document.getElementById("input-email");
        let inputUsername = document.getElementById("input-username");
        let inputPassword = document.getElementById("input-password");
        let inputConfirmpassword = document.getElementById("input-confirmpassword");

        let input = {
            email: inputEmail.value,
            username: inputUsername.value,
            password: inputPassword.value,
            confirmpassword: inputConfirmpassword.value,
        }

        return input;
    }

    /**
     * if registration was successful: go to login page
     *      otherwise: 
     */
    function handleResponse(data) {
        // register fulfilled => show login
        if (data["fulfilled"] == true) {
            location.assign("<?= base_url("/login") ?>");
            return;
        }
        // when register failed, show errors
        else if (data["validation_errors"]) {
            let errors = data["validation_errors"]
            let html = "";
            for (err in errors) {
                html += "<li class='link-danger m-2'>" + errors[err] + "</li>"
            }

            document.getElementById("errors-validation").innerHTML = html;
        }
    }
</script>