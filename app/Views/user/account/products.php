<div>
    <h2 class="m-2">Your <?= $title ?></h2>
    <a href="<?= base_url("account//overview/products") ?>/addProduct" class="btn btn-primary m-2">Add Product</a>

    <div id="account-products">
        <?php
        if ($products) {
            foreach ($products as $product) { ?>
                <div class="account-product">
                    <p><?= $product["name"] ?></p>
                    <p><?= $product["price"] ?></p>
                    <p><?= $product["quantity"] ?></p>
                    <p><?= $product["product_category"] ?></p>
                </div>
        <?php }
        } ?>
    </div>
</div>