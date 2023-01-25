<div>
    <?= csrf_field() ?>
    <h2 class="m-2">Your <?= esc($title) ?></h2>
    <a href="<?= base_url("account/overview/products") ?>/addProduct" class="btn btn-primary m-2">Add Product</a>

    <div id="inventory-products">
        <?php
        if ($products) {
            foreach ($products as $product) { ?>
                <div id="container-<?= esc($product["id"]) ?>" class="inventory-product-container">
                    <div class="inventory-product-content">
                        <ul>
                            <h3><?= esc($product["name"]) ?></h3>
                            <li><i class="bi bi-currency-dollar inventory-icon"></i><?= esc($product["price"]) ?></li>
                            <li><i class="bi bi-123 inventory-icon"></i><?= esc($product["quantity"]) ?></li>
                            <li><i class="bi bi-tag inventory-icon"></i><?= esc($product["product_category"]) ?></li>
                        </ul>
                        <?php
                        $files = $product["files"];
                        if (isset($files) && isset($files[0]) && $files[0]["file_type"] == "image") {
                        ?>
                            <img class="inventory-image" src="/UploadedFiles/products/<?= esc($files[0]["file_name"]) ?>">
                        <?php } ?>
                    </div>
                    <ul class="inventory-product-buttons">
                        <li class="inventory-product-button">
                            <button name="product-remove-button" id="<?= esc($product["id"]) ?>" class="scaling-button">
                                <i class="bi bi-trash icon-button red"></i>
                            </button>
                        </li>
                        <li class="inventory-product-button scaling-button">
                            <a class="href-button" href="<?= base_url("account/overview/products/editProduct") . "/" . esc($product["id"]) ?>" class="scaling-button">
                                <i class="bi bi-pencil icon-button"></i>
                            </a>
                        </li>
                    </ul>
                </div>
        <?php }
        } ?>
    </div>
</div>
<script>
    // == csrf properties ==
    AjaxHandler.setToken("<?= csrf_token() ?>");
    // ===
    let buttons = document.getElementsByName("product-remove-button");
    for (button of buttons) {
        let productId = button.getAttribute("id");
        button.addEventListener("click", () => removeProduct(productId), false);
    }

    function removeProduct(productId) {
        AjaxHandler.ajaxPost("<?= base_url("/account/ProductsController/removeProduct") ?>", productBody(productId), handleResponse)
    }

    function productBody(productId) {
        let body = {
            "productId": productId,
        }
        return body;
    }


    function handleResponse(data) {
        if (data["success"]) {
            let removedProductId = data["productId"];
            let productContainer = document.getElementById("container-" + removedProductId);
            productContainer.remove();
        }
    }
</script>