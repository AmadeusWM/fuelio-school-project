<div id="account-overview-container">
    <nav>
        <ul id="account-left-sidebar" class="hover-box">
            <li><a href="<?=base_url("/account/overview")?>/profile" class="link account-tab-button active">My Profile</a></li>
            <li><a href="<?=base_url("/account/overview")?>/orders" class="link account-tab-button">My Orders</a></li>
            <li><a href="<?=base_url("/account/overview")?>/products" class="link account-tab-button">My Products</a></li>
            <li><a href="<?=base_url("/account/overview")?>/analytics" class="link account-tab-button">Analytics</a></li>
        </ul>
    </nav>
    <div id="account-tab-container" class="hover-box">
        <?= $page ?>
    </div>
</div>