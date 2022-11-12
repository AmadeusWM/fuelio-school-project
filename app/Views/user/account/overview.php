<div id="account-overview-container">
    <nav>
        <ul id="account-left-sidebar" class="hover-box">
            <li><button name="profile" id="account-button-profile" class="link account-tab-button active">My Profile</button></li>
            <li><button name="orders" id="account-button-orders" class="link account-tab-button">My Orders</button></li>
            <li><button name="products" id="account-button-products" class="link account-tab-button">My Products</button></li>
            <li><button name="analytics" id="account-button-analytics" class="link account-tab-button">Analytics</button></li>
        </ul>
    </nav>
    <div id="account-tab-container" class="hover-box">
        <?= $page ?>
    </div>
</div>
<script src="/javascript/ajaxRequests.js"></script>
<script>
    let button1 = document.getElementById("account-button-profile");
    button1.addEventListener('click', () => updateHTML("profile"), false);

    let button2 = document.getElementById("account-button-orders");
    button2.addEventListener('click', () => updateHTML("orders"), false);
    
    let button3 = document.getElementById("account-button-products");
    button3.addEventListener('click', () => updateHTML("products"), false);

    let button4 = document.getElementById("account-button-analytics");
    button4.addEventListener('click', () => updateHTML("analytics"), false);

    function updateHTML(pageName){
        ajaxGetView("<?=base_url("/account")?>" + "/" + pageName, handleNewView);
        let allButtons = document.getElementsByClassName("account-tab-button");
        // disable their "active" class
        for (button of allButtons){
            button.classList.remove("active");
        } 
        let buttonActive = document.getElementsByName(pageName)[0]
        if (buttonActive)
            buttonActive.classList.toggle("active");
    }

    function handleNewView(view){
        let container = document.getElementById("account-tab-container");
        if (container)
            container.innerHTML = view;
    }
</script>