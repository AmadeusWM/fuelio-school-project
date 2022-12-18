<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo esc($title) ?></title>

    <meta name="description" content="An energy product site">

    <!-- dns-prefetch opens connection with domain before fetching (performance) from [here](https://developer.mozilla.org/en-US/docs/Web/Performance/dns-prefetch) -->
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net/">
    <!-- from [here](https://icons.getbootstrap.com) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/styling.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <nav id="navbar-header">
        <a class="no-link-styling" href="/">
            <h1 id="logo-header"><i class="bi bi-lightbulb"></i>Fuelio</h1>
        </a>
        <form class="form-inline my-2 my-lg-0" action="<?= base_url("/store/search") ?>">
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
                    <a href="/SignInController/logout" class="link">Log out</a>
                </div>
                <button id="notifications-button" class="position-relative">
                    <i class="bi bi-envelope header-icon" aria-label="Notifications"></i>
                    <span class="position-absolute translate-middle badge rounded-pill bg-danger"><?= $notifications_amount ?></span>
                </button>
                <ul id="notifications-popup" class="header-popup">
                    <?php foreach ($notifications as $notification) { ?>
                        <li>
                            <span><?= $notification["content"] ?></span>
                        </li>
                    <?php } ?>
                </ul>
                <!-- code when logged out -->
            <?php
            } else {
            ?>
                <a href="/login" class="link">Login</a>
            <?php } ?>
            <a href="/cart/cart">
                <i class="bi bi-bag header-icon" aria-label="Account"></i>
            </a>
        </div>
    </nav>
    <script>
        <?php
        if (session("isLoggedIn") == true) {
        ?>
            let buttonOverview = document.getElementById("account-overview-button");
            buttonOverview.addEventListener('click', () => togglePopup("account-overview-popup"), false);

            let buttonNotifications = document.getElementById("notifications-button");
            buttonNotifications.addEventListener('click', () => togglePopup("notifications-popup"), false);
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