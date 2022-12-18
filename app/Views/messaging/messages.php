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