<div class="d-flex justify-content-center">
    <div id="registration-container" class="hover-box">
        <h1>Log In</h1>
        <form id="registration-form-container">
            <!-- hidden csrf field to protect against common attacks (https://www.codeigniter.com/user_guide/tutorial/create_news_items.html)-->
            <?= csrf_field() ?>
            <input id="input-email" type="email" name="email" class="form-control registration-input" placeholder="name@example.com">
            <input id="input-password" type="password" class="form-control registration-input" placeholder="Password">
        </form>
        <ul id="errors-validation" class="errors-validation" ></ul>
        <div class="registration-buttons">
            <button onclick="location.href='/register'" class="btn btn-outline-primary w-100 registration-button">Sign Up</button>
            <button id="sign-in-button" class="btn btn-primary w-100 registration-button" type="submit">Log In</button>
        </div>
    </div>
</div>
<script src="/javascript/ajaxRequests.js"></script>
<script>
    let button = document.getElementById("sign-in-button");
    button.addEventListener('click', login, false);

    function login() {
        ajaxPost("<?= base_url('/SignInController/login') ?>",
        getJSONInput(),
        handleResponse);
    }

    /**
     * get the input data from the html
     */
    function getJSONInput() {
        let inputEmail = document.getElementById("input-email");
        let inputPassword = document.getElementById("input-password");

        let input = {
            email: inputEmail.value,
            password: inputPassword.value,
            ...getCSRFHiddenFieldValue("<?= csrf_token() ?>")
        }

        return input;
    }

    function handleResponse(data) {

        let sessionData = data["session"];
        // register fulfilled => show login
        if (sessionData && sessionData["isLoggedIn"]) {
            location.assign("<?= base_url("/") ?>");
            return;
        }
        else if (data["session-flash-data"]){
            // when register failed, show errors
            let sessionFlashData = data["session-flash-data"];
            
            let msg = sessionFlashData["msg"]
            
            let html = "<li class='error-field'>" + msg + "</li>"
            
            document.getElementById("errors-validation").innerHTML = html;
        }
        updateCSRF(data)
    }
</script>