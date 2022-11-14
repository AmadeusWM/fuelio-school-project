<div>
    <h2 class="m-2">Your <?= $title ?></h2>
    <a href="<?= base_url("account//overview/products") ?>/addProduct" class="btn btn-primary m-2">Add Product</a>

    <div id="inventory-products">
        <?php
        if ($products) {
            foreach ($products as $product) { ?>
                <div class="inventory-product-container">
                    <div class="inventory-product-content">
                        <ul>
                            <h3><?= $product["name"] ?></h3>
                            <li><i class="bi bi-currency-dollar inventory-icon"></i><?= $product["price"] ?></li>
                            <li><i class="bi bi-123 inventory-icon"></i><?= $product["quantity"] ?></li>
                            <li><i class="bi bi-tag inventory-icon"></i><?= $product["product_category"] ?></li>
                        </ul>
                        <?php
                        $images = $product['images'];
                        if (isset($images) && isset($images[0])) {
                        ?>
                            <img class="inventory-image" src="/UploadedFiles/productImage/<?= $images[0] ?>">
                        <?php } ?>
                    </div>
                    <ul class="inventory-product-buttons">
                        <li class="inventory-product-button">
                            <button name="product-remove-button" id="<?= $product['id'] ?>" class="scaling-button">
                                <i class="bi bi-trash  icon-button red"></i>
                            </button>
                        </li>
                        <li class="inventory-product-button scaling-button"><i class="bi bi-pencil icon-button"></i></li>
                    </ul>
                </div>
        <?php }
        } ?>
    </div>
</div>