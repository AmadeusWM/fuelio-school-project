<div id="results-container">
    <?php foreach ($products as $product) { ?>
        <div id="result-container" class="hover-box">
            <?php
            $files = $product["files"];
            $file = null;
            if ($files)
                $file = $product["files"][0];
            if ($file && $file["file_type"] == "image") {
            ?>
            <a>
                <img class="product-entry-image" src="/UploadedFiles/products/<?= $file["file_name"] ?>">
            </a>
            <?php } else { ?>
                <div class="image-placeholder">
                    <i class="bi-card-image gray tx-xl"></i>
                    <p class="gray tx-l">No image found</p>
                </div>
            <?php } ?>
            <a class="product-title"><?= $product["name"] ?></a>
            <a class="webshop-name"><?= $product["webshop_name"] ?></a>
            <hr style="margin: 5px 0px" />
            <div class="product-content-container">
                <p class="product-category"><?= $product["product_category"] ?></p>
                <p class="product-price">€<?= $product["price"] ?></p>
            </div>
            <button class="btn btn-primary m-2">Add To Cart</button>
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
            </div>
        </div>
    <?php } ?>
</div>