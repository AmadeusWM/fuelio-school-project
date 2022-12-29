<div id="analytics-container">
    <h2 class="m-2"><?= $title ?></h2>
    <?php foreach ($stats as $stat) { ?>
        <div class="stat-container">
            <a class="product_title" href="<?= base_url("/store/product") . "/" . $stat["id_product"] ?>">
                <?= $stat["name_product"] ?>
            </a>
            <div class="information-container">
                <p><b>Product price:</b> €<?= $stat["price_product"] ?></p>
                <p><b>Sold:</b> <?= $stat["total_sold"] ?></p>
                <p class="total"><b>Total:</b> €<?= number_format($stat["total_sold"] * $stat["price_product"], 2) ?></p>
            </div>
        </div>
    <?php } ?>
</div>