<div class="d-flex justify-content-center">
    <div id="registration-container" class="hover-box">
        <!-- report csrf protection errors -->
        <?= session()->getFlashdata('error') ?>
        <!-- report validation errors -->
        <?= service('validation')->listErrors() ?>

        <form id="registration-form-container">
            <!-- hidden csrf field to protect against common attacks (https://www.codeigniter.com/user_guide/tutorial/create_news_items.html)-->
            <?= csrf_field() ?> 
            <h1>Sign Up!</h1>
            <input type="email" class="form-control registration-input" id="emailFloating" placeholder="name@example.com">
            <input type="text" class="form-control registration-input" id="usernameFloating" placeholder="Username">
            <input type="password" class="form-control registration-input" id="passwordFloating" placeholder="Password">
            <input type="password" class="form-control registration-input" id="passwordConfirmFloating" placeholder="Confirm Password">
        </form>
        <ul class="registration-buttons">
            <button onclick="location.href='/login'" class="btn btn-outline-primary w-100 registration-button">Log In</button>
            <button id="sign-up-button" class="btn btn-primary w-100 registration-button" type="submit">Sign Up</button>
        </ul>
    </div>
</div>
<script>
    function register(){
        
    }

    let button = document.getElementById("sign-up-button");
    button.addEventListener('click', register, false);
</script>