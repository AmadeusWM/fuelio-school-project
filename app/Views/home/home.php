<div>
    <div class="colored-back">
        <h1 id="home-title">Welcome back!</h1>
        <p>Please have a look through our catalog.</p>
    </div>
    <div id="home-products-container">
        <?php foreach ($products as $product) { ?>
            <div id="home-product-container" class="hover-box">
                <?php
                $files = $product["files"];
                $file = null;
                if ($files)
                    $file = $product["files"][0];
                if ($file && $file["file_type"] == "image") {
                ?>
                    <img class="product-entry-image" src="/UploadedFiles/products/<?= $file["file_name"] ?>">
                <?php } else{?>
                    <div class="image-placeholder">
                        <i class="bi-card-image gray tx-xl"></i>
                        <p class="gray tx-l">No image found</p>
                    </div>
                <?php }?>
                <h2 class="product-title"><?= $product['name'] ?></h2>
                <div class="product-content-container">
                    <p class="product-category"><?= $product['product_category'] ?></p>
                    <p class="product-price">€<?= $product['price'] ?></p>
                </div>
                <button class="btn btn-primary m-3">Add To Cart</button>
            </div>
        <?php } ?>
    </div>
</div>