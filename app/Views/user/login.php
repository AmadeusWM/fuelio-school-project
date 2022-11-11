<div class="d-flex justify-content-center">
    <div id="registration-container" class="hover-box">
        <h1>Log In</h1>
        <form id="registration-form-container">
            <!-- hidden csrf field to protect against common attacks (https://www.codeigniter.com/user_guide/tutorial/create_news_items.html)-->
            <?= csrf_field() ?>
            <input id="input-email" type="email" name="email" class="form-control registration-input" placeholder="name@example.com">
            <input id="input-password" type="password" class="form-control registration-input" placeholder="Password">
        </form>
        <ul id="errors-validation"></ul>
        <ul class="registration-buttons">
            <button onclick="location.href='/register'" class="btn btn-outline-primary w-100 registration-button">Sign Up</button>
            <button id="sign-in-button" class="btn btn-primary w-100 registration-button" type="submit">Log In</button>
        </ul>
    </div>
</div>
<script>
    let button = document.getElementById("sign-in-button");
    button.addEventListener('click', login, false);

    function login() {
        fetch("<?= base_url('/SignInController/login') ?>", {
                method: "post",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify(getJSONInput()),
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
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

        console.log(input);
        
        return input;
    }

    function handleResponse(data) {
        console.log(data);

        let sessionData = data["session"];
        // register fulfilled => show login
        if (sessionData == undefined)
            return;
        else if (sessionData["isLoggedIn"]) {
            location.assign("<?= base_url("/") ?>");
            return;
        }
        else{
            // when register failed, show errors
            let sessionFlashData = data["session-flash-data"];
            
            let msg = sessionFlashData["msg"]
            
            let html = "<li class='link-danger m-2'>" + msg + "</li>"
            
            document.getElementById("errors-validation").innerHTML = html;
            // get the hidden field to reset the csrf field
            let hiddenField = document.getElementsByName("<?= csrf_token() ?>")[0];
            hiddenField.setAttribute("name", data["csrf_token"]);
            hiddenField.setAttribute("value", data["csrf_value"]);
        }
    }
</script>