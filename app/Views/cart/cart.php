<div id="cart-page">
    <?= csrf_field() ?>
    <h1 id="title"><?= esc($title) ?></h1>
    <div id="container-page-content">
        <div id="container-products" class="hover-box">
            <?php
            $totalPrice = 0;
            foreach ($products as $product) {
                if ($totalPrice != 0)  echo "<hr style='margin: 0px' />"; // horizontal line on top, except if first element
                $totalPriceProduct = $product["price"] * $product["orderQuantity"];
                $totalPrice += $totalPriceProduct;
            ?>
                <div class="product-container">
                    <a class="thumbnail-container" href='<?= base_url("/store/product" . "/" . $product["id"]) ?>'>
                        <?php
                        $files = $product["files"];
                        if (isset($files) && isset($files[0])) {
                            $file = $files[0];
                            if (isset($file) && $file["file_type"] == "image") {
                        ?>
                                <img class="thumbnail" src="/UploadedFiles/products/<?= esc($file["file_name"]) ?>" alt="Product thumbnail">
                            <?php } else { ?>
                                <video class="thumbnail" alt="Product video">
                                    <source src="/UploadedFiles/products/<?= esc($file['file_name']) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                        <?php }
                        } ?>
                    </a>
                    <div class="content">
                        <div class="product-details">
                            <div class="left">
                                <a class="webshop" href="<?= base_url("/store/webshop") . "/" . $product["webshop_id"] ?>"><?= esc($product["webshop_name"]) ?></a>
                                <a class="product-name" href='<?= base_url("/store/product" . "/" . $product["id"]) ?>'>
                                    <?= esc($product["name"]) ?>
                                </a>
                            </div>
                            <div class="right">
                                <p>€<?= number_format(esc($totalPriceProduct), 2) ?></p>
                            </div>
                        </div>
                        <div class="button-row">
                            <input type="number" 
                                name="quantity" 
                                id="quantity-<?= esc($product['id']) ?>" 
                                class="form-control quantity" 
                                min="1" 
                                max="<?= esc($product["quantity"]) ?>" 
                                value="<?= esc($product["orderQuantity"]) ?>" 
                                onchange="updateCartProduct(<?= esc($product['id']) ?>,this.value)" />
                            <button class="scaling-button" onclick="removeCartProduct(<?= esc($product['id']) ?>)">
                                <i class="bi bi-trash icon-button red" aria-label="Remove from cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php
            if (count($products) == 0) {
                echo "<div class='empty-cart'><h3>Oh no, such empty... 😶‍🌫️<h3></div>";
            }
            ?>
        </div>
        <div id="container-overview" class="hover-box">
            <h2 id="overview-header">Overview</h2>
            <div class="overview-row">
                <p>Total:</p>
                <p id="total">€<?= number_format(esc($totalPrice), 2) ?></p>
            </div>
            <hr />
            <button class="btn btn-primary order-button" onclick="location.assign('<?= base_url('/cart/checkout') ?>')" <?= count(esc($products)) == 0 ? "disabled" : "" ?>>
                Go to Checkout
            </button>
        </div>
    </div>
</div>
<script>
    AjaxHandler.setToken("<?= csrf_token() ?>");

    function updateCartProduct(id, quantity) {
        let input = document.getElementById("quantity-" + id);
        input.setAttribute("disabled", "");
        AjaxHandler.ajaxPost("<?= base_url('/cart/addProductToCart') ?>", {
                quantity: quantity,
                id: id
            },
            (data) => {
                location.assign("<?= base_url("/cart/cart") ?>");
            })
    }

    function removeCartProduct(id) {
        AjaxHandler.ajaxPost("<?= base_url('/cart/removeProductFromCart') ?>", {
                id: id
            },
            (data) => {
                location.assign("<?= base_url("/cart/cart") ?>");
            })
    }
</script>