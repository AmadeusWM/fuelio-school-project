<div id="analytics-container">
    <h2 class="m-2"><?= esc($title) ?></h2>
    <?php foreach ($stats as $stat) { ?>
        <div class="stat-container">
            <a class="product_title" href="<?= base_url("/store/product") . "/" . esc($stat["id_product"]) ?>">
                <?= esc($stat["name_product"]) ?>
            </a>
            <div class="information-container">
                <p><b>Product price:</b> €<?= esc($stat["price_product"]) ?></p>
                <p><b>Sold:</b> <?= esc($stat["total_sold"]) ?></p>
                <p class="total"><b>Total:</b> €<?= number_format(esc($stat["total_sold"]) * esc($stat["price_product"]), 2) ?></p>
            </div>
        </div>
    <?php } ?>
</div>