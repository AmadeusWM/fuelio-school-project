<div class="d-flex justify-content-center">
    <div id="registration-container" class="hover-box">
        <!-- report csrf protection errors -->
        <!-- <= session()->getFlashdata('error') ?> -->
        <!-- report validation errors -->
        <!-- <= service('validation')->listErrors() ?> -->


        <h1>Sign Up!</h1>
        <form id="registration-form-container">
            <!-- hidden csrf field to protect against common attacks (https://www.codeigniter.com/user_guide/tutorial/create_news_items.html)-->
            <?= csrf_field() ?>
            <input id="input-email" type="email" name="email" class="form-control registration-input" placeholder="name@example.com">
            <input id="input-username" type="text" name="username" class="form-control registration-input" placeholder="Username">
            <input id="input-password" type="password" name="password" class="form-control registration-input" placeholder="Password">
            <input id="input-confirmpassword" type="password" name="confirmpassword" class="form-control registration-input" placeholder="Confirm Password">
        </form>
        <ul id="errors-validation"></ul>
        <ul class="registration-buttons">
            <button onclick="location.href='/login'" class="btn btn-outline-primary w-100 registration-button">Log In</button>
            <button id="sign-up-button" class="btn btn-primary w-100 registration-button" type="submit">Sign Up</button>
        </ul>
    </div>
</div>

<script>
    let button = document.getElementById("sign-up-button");
    button.addEventListener('click', register, false);

    function register() {
        fetch("<?= base_url('/SignUpController/register') ?>", {
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
        let inputUsername = document.getElementById("input-username");
        let inputPassword = document.getElementById("input-password");
        let inputConfirmpassword = document.getElementById("input-confirmpassword");
        // get the value of the hidden field for csrf
        let hiddenField = document.getElementsByName("<?= csrf_token() ?>")[0];
        let hiddenFieldValue = hiddenField.getAttribute("value");

        let input = {
            email: inputEmail.value,
            username: inputUsername.value,
            password: inputPassword.value,
            confirmpassword: inputConfirmpassword.value,
            "<?= csrf_token() ?>": hiddenFieldValue
        }

        return input;
    }

    /**
     * if registration was successful: go to login page
     *      otherwise: 
     */
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