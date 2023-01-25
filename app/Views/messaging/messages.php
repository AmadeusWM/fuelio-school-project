<button id="notifications-button" class="position-relative">
    <i class="bi bi-envelope header-icon" aria-label="Notifications"></i>
    <span class="position-absolute translate-middle badge rounded-pill bg-danger"><?= esc($notifications_amount) ?></span>
</button>
<ul id="notifications-popup" class="header-popup">
    <div id="notifications-wrapper">
        <?php foreach ($notifications as $notification) { ?>
            <li class="notification">
                <span class="sender-name">by
                    <a href="<?= base_url("/message") . "/" . esc($notification["sender_id"]) ?>">
                        <?= esc($notification["sender_username"]) ?>
                    </a>
                    <?php
                    if ($notification["sender_webshop"] != "") { ?>
                        from <a href="<?= base_url("/store/webshop") . "/" . esc($notification["sender_id"]) ?>">
                            <?= esc($notification["sender_webshop"]) ?>
                        </a>
                    <?php } ?>
                </span>
                <b class="message-title"><?= esc($notification["title"]) ?></b>
                <span class="message-content"><?= esc($notification["content"]) ?></span>
                <?php if ($notification["type"] == "message"){ ?>
                    <a class="btn btn-primary" href="<?= base_url("/message") . "/" . esc($notification["sender_id"]) ?>"><i class="bi bi-envelope" aria-label="Send Response"></i></a>
                <?php } else{ ?>
                    <a class="btn btn-primary" href="<?= base_url("/store/product") . "/" . esc($notification["pointer_id"]) ?>"><i class="bi bi-arrow-return-right" aria-label="Visit Product"></i></a>
                <?php } ?>
                <hr />
            </li>
        <?php } ?>
    </div>
</ul>