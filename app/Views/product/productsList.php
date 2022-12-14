<div id="results-container">
    <?php foreach ($products as $product) { ?>
        <div id="result-container" class="hover-box">
            <a class="image-container" href='<?= base_url("/store/product" . "/" . $product["id"]) ?>'>
                <?php
                $files = $product["files"];
                $file = null;
                if ($files)
                    $file = $product["files"][0];
                if ($file && $file["file_type"] == "image") {
                ?>
                    <img class="product-entry-image" src="/UploadedFiles/products/<?= $file["file_name"] ?>">
                <?php } else { ?>
                    <div class="image-placeholder">
                        <i class="bi-card-image gray tx-xl"></i>
                        <p class="gray tx-l">No image available</p>
                    </div>
                <?php } ?>
            </a>
            <a class="product-title" href='<?= base_url("/store/product" . "/" . $product["id"]) ?>'> <?= $product["name"] ?></a>
            <a class="webshop-name" href="<?= base_url("/store/webshop") . "/" . $product["webshop_id"] ?>"><?= $product["webshop_name"] ?></a>
            <hr style="margin: 5px 0px" />
            <div class="product-content-container">
                <p class="product-category"><?= $product["product_category"] ?></p>
                <p class="product-price">€<?= $product["price"] ?></p>
            </div>
            <div class="product-content-container">
                <?php if ($product["quantity"] > 0) { ?>
                    <p class="product-availability green">
                        <i class="bi bi-check-circle-fill"></i>
                        Available
                    </p>
                <?php } else { ?>
                    <p class="product-availability red">
                        <i class="bi bi-x-circle-fill"></i>
                        Unavailable
                    </p>
                <?php } ?>
                <button data-product-id="<?= $product["id"] ?>" class="btn btn-primary add-product-button" <?= $product['quantity'] == 0 ? 'disabled' : '' ?>>
                    <i class="bi bi-cart-plus"></i>
                </button>
            </div>
        </div>
    <?php } ?>
</div>
<nav aria-label="..." class="pagination-bottom">
    <ul class="pagination">
        <li class="page-item <?= $page <= 0  || $amountPages == 0 ? "disabled" : "" ?>">
            <a class="page-link" href="<?= base_url("/store/search") . "/" . ($page - 1) ?>">Prev</a>
        </li>
        <?php if ($page != 0 && $amountPages != 0) { ?>
            <li class="page-item">
                <a class="page-link" href="<?= base_url("/store/search") . "/" . ($page - 1) ?>">
                    <?= $page ?>
                </a>
            </li>
        <?php } ?>
        <li class="page-item active">
            <span class="page-link">
                <?= $page + 1 ?>
            </span>
        </li>
        <?php if ($page != $amountPages - 1 && $amountPages != 0) { ?>
            <li class="page-item">
                <a class="page-link" href="<?= base_url("/store/search") . "/" . ($page + 1) ?>">
                    <?= $page + 2 ?>
                </a>
            </li>
        <?php } ?>
        <li class="page-item <?= ($page >= $amountPages - 1 || $amountPages == 0) ? "disabled" : "" ?>">
            <a class="page-link" href="<?= base_url("/store/search") . "/" . ($page + 1) ?>">Next</a>
        </li>
    </ul>
</nav>