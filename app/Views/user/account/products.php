<div>
    <h2 class="m-2">Your <?= $title ?></h2>
    <a href="<?= base_url("account//overview/products") ?>/addProduct" class="btn btn-primary m-2">Add Product</a>

    <div id="account-products">
        <?php
        if ($products) {
            foreach ($products as $product) { ?>
                <div class="account-product">
                    <h3><?= $product["name"] ?></h3>
                        <ul>
                            <li><?= $product["price"] ?></li>
                            <li><?= $product["quantity"] ?></li>
                            <li><?= $product["product_category"] ?></li>
                        </ul>
                </div>
        <?php }
        } ?>
    </div>
</div>