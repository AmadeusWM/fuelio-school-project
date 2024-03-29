<div id="results-container">
    <?php foreach ($products as $product) { ?>
        <div id="result-container" class="hover-box">
            <a class="image-container" href='<?= base_url("/store/product" . "/" . esc($product["id"])) ?>'>
                <?php
                $files = $product["files"];
                $file = null;
                if ($files){ // find the first image (thumbnail)
                    foreach ($files as $f) {
                        if($f["file_type"] == "image"){
                            $file = $f;
                            break;
                        }
                    }
                }
                if (isset($file)) {
                ?>
                    <img class="product-entry-image" src="/UploadedFiles/products/<?= esc($file["file_name"]) ?>" alt="Product thumbnail">
                <?php } else { ?>
                    <div class="image-placeholder">
                        <i class="bi-card-image gray tx-xl" aria-label="No image"></i>
                        <p class="gray tx-l">No image available</p>
                    </div>
                <?php } ?>
            </a>
            <a class="product-title" href='<?= base_url("/store/product" . "/" . esc($product["id"])) ?>'> <?= esc($product["name"]) ?></a>
            <a class="webshop-name" href="<?= base_url("/store/webshop") . "/" . esc($product["webshop_id"]) ?>"><?= esc($product["webshop_name"]) ?></a>
            <hr style="margin: 5px 0px" />
            <div class="product-content-container">
                <p class="product-category"><?= esc($product["product_category"]) ?></p>
                <p class="product-price">€<?= esc($product["price"]) ?></p>
            </div>
            <div class="product-content-container">
                <?php if ($product["quantity"] > 0) { ?>
                    <p class="product-availability green">
                        <i class="bi bi-check-circle-fill" aria-label="Available"></i>
                        Available
                    </p>
                <?php } else { ?>
                    <p class="product-availability red">
                        <i class="bi bi-x-circle-fill" aria-label="Not available"></i>
                        Unavailable
                    </p>
                <?php } ?>
                <button data-product-id="<?= esc($product["id"]) ?>" class="btn btn-primary add-product-button" <?= $product['quantity'] == 0 ? 'disabled' : '' ?>>
                    <i class="bi bi-cart-plus" aria-label="Add to cart"></i>
                </button>
            </div>
        </div>
    <?php } ?>
</div>
<nav aria-label="..." class="pagination-bottom">
    <ul class="pagination">
        <li class="page-item <?= $page <= 0  || $amountPages == 0 ? "disabled" : "" ?>">
            <a class="page-link" href="<?= base_url("/store/search") . "/" . (esc($page) - 1) ?>">Prev</a>
        </li>
        <?php if ($page != 0 && $amountPages != 0) { ?>
            <li class="page-item">
                <a class="page-link" href="<?= base_url("/store/search") . "/" . (esc($page) - 1) ?>">
                    <?= esc($page) ?>
                </a>
            </li>
        <?php } ?>
        <li class="page-item active">
            <span class="page-link">
                <?= esc($page) + 1 ?>
            </span>
        </li>
        <?php if ($page != $amountPages - 1 && $amountPages != 0) { ?>
            <li class="page-item">
                <a class="page-link" href="<?= base_url("/store/search") . "/" . (esc($page) + 1) ?>">
                    <?= esc($page) + 2 ?>
                </a>
            </li>
        <?php } ?>
        <li class="page-item <?= ($page >= $amountPages - 1 || $amountPages == 0) ? "disabled" : "" ?>">
            <a class="page-link" href="<?= base_url("/store/search") . "/" . (esc($page) + 1) ?>">Next</a>
        </li>
    </ul>
</nav>