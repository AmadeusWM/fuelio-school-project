<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc($title) ?></title>

    <meta name="description" content="An energy product site">

    <!-- dns-prefetch opens connection with domain before fetching (performance) from [here](https://developer.mozilla.org/en-US/docs/Web/Performance/dns-prefetch) -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">
    <!-- from [here](https://icons.getbootstrap.com) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/styling.css">
</head>

<body>
    <?= csrf_field() ?>
    <nav id="navbar-header">
        <a class="no-link-styling" href="/">
            <h1 id="logo-header"><i class="bi bi-lightbulb logo-icon"></i>Fuelio</h1>
        </a>
        <form class="search-bar" action="<?= base_url("/store/search") ?>">
            <input class="form-control m-1" name="search_terms" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-primary m-1" type="submit">Search</button>
        </form>
        <div>
            <!-- code when logged in -->
            <?php
            if (session("isLoggedIn") == true) {
            ?>
                <button id="account-overview-button">
                    <i class="bi bi-person header-icon" aria-label="Account"></i>
                </button>
                <div id="account-overview-popup" class="header-popup">
                    <a href="/account/overview" class="link">My Account</a>
                    <hr />
                    <a href="/SignInController/logout" class="link">Log out</a>
                </div>
                <?= $notificationsView ?>
                <!-- code when logged out -->
            <?php
            } else {
            ?>
                <a href="/login" class="link" id="login-button">Login</a>
            <?php } ?>
            <a href="/cart/cart">
                <i class="bi bi-bag header-icon" aria-label="Account"></i>
            </a>
        </div>
    </nav>
    <script src="/javascript/AjaxHandler.js"></script>
    <script>
        AjaxHandler.setToken("<?= csrf_token() ?>");

        <?php
        if (session("isLoggedIn") == true) {
        ?>
            let buttonOverview = document.getElementById("account-overview-button");
            buttonOverview.addEventListener('click', () => togglePopup("account-overview-popup"), false);

            let buttonNotifications = document.getElementById("notifications-button");
            buttonNotifications.addEventListener('click', () => {
                togglePopup("notifications-popup")
                AjaxHandler.ajaxPostUrl("<?= base_url('/message/allMessagesRead') ?>");
            })
        <?php } ?>

        function togglePopup(id) {
            let popup = document.getElementById(id);

            let popupActive = "header-popup header-popup-active"
            let popupInActive = "header-popup"

            if (popup.getAttribute("class") == popupInActive) {
                closeAllPopups();
                popup.setAttribute("class", popupActive)
            } else
                popup.setAttribute("class", popupInActive)
        }

        function closeAllPopups() {
            let popups = document.getElementsByClassName("header-popup");
            for (let popup of popups) {
                popup.setAttribute("class", "header-popup")
            }
        }
    </script>