<div id="account-overview-container">
    <nav>
        <ul id="account-left-sidebar" class="hover-box">
            <li><a id="profile" href="<?=base_url("/account/overview")?>/profile" class="link account-tab-button">PROFILE</a></li>
            <li><a id="orders" href="<?=base_url("/account/overview")?>/orders" class="link account-tab-button">ORDERS</a></li>
            <li><a id="products" href="<?=base_url("/account/overview")?>/products" class="link account-tab-button">PRODUCTS</a></li>
            <li><a id="analytics" href="<?=base_url("/account/overview")?>/analytics" class="link account-tab-button">ANALYTICS</a></li>
        </ul>
    </nav>
    <div id="account-tab-container" class="hover-box">
        <?= $page ?>
    </div>
</div>
<script>
    activeTab = document.getElementById("<?=strtolower($title)?>");
    if (activeTab){
        activeTab.classList.toggle("active");
    }
</script>