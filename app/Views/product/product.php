<div id="product-page">
    <div id="page-content">
        <div id="product-overall">
            <div id="product-left">
                <div id="image-slider">
                    <?php
                    $files = $product["files"];
                    // only show if there are images/videos to show
                    if (isset($files) && sizeof($files) > 0) {
                    ?>
                        <i id="image-up-arrow" class="bi bi-caret-up-fill" onclick="plusSlides(-1)"></i>
                        <!-- images in the slider -->
                        <div id="image-slider-content">
                            <?php
                            $index = 0;
                            if (isset($files))
                                foreach ($files as $file) {
                                    if ($file["file_type"] == "image") {
                            ?>
                                    <img onclick="currentSlide(<?= $index ?>)" class="product-file-slider" src="/UploadedFiles/products/<?= $file["file_name"] ?>">
                                <?php } else { ?>
                                    <video onclick="currentSlide(<?= $index ?>)" class="product-file-slider">
                                        <source src="/UploadedFiles/products/<?= $file['file_name'] ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                            <?php }
                                    $index++;
                                } ?>
                        </div>
                        <i id="image-down-arrow" class="bi bi-caret-down-fill" onclick="plusSlides(1)"></i>
                    <?php } ?>
                </div>
                <div id="image-thumbnail">
                    <!-- image to show (shows only active) -->
                    <?php
                    $files = $product["files"];
                    $index = 0;
                    if (isset($files) && sizeof($files) > 0)
                        foreach ($files as $file) {
                            if ($file["file_type"] == "image") {
                    ?>
                            <img onclick="currentSlide(<?= $index ?>)" class="product-file-thumbnail" src="/UploadedFiles/products/<?= $file["file_name"] ?>">
                        <?php } else { ?>
                            <video class="product-file-thumbnail" controls>
                                <source src="/UploadedFiles/products/<?= $file['file_name'] ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                    <?php }
                            $index++;
                        } ?>
                </div>
            </div>
            <div id="product-right">
                <a id="webshop_name" href="<?= base_url("/store/webshop") . "/" . $product["webshop_id"] ?>"><?= $product["webshop_name"] ?></a>
                <p id="origin">Origin: <?= $product["origin"] ?></p>
                <h1 class="product-title"><?= $title ?></h1>
                <hr />
                <p id="price" class="red">
                </p>
                <?php if ($product["quantity"] > 0) { ?>
                    <p class="product-availability green">
                        <i class="bi bi-check-circle-fill"></i>
                        <?= $product["quantity"] ?> Available
                    </p>
                <?php } else { ?>
                    <p class="product-availability red">
                        <i class="bi bi-x-circle-fill"></i>
                        Unavailable
                    </p>
                <?php } ?>
                <div class="info-row">
                    <?= csrf_field() ?>
                    <input type="number" id="quantity" name="quantity" class="form-control" min="1" max="<?= $product["quantity"] ?>" value="1" onchange="updatePrice(this.value)" />
                    <button id="add-cart-button" class="btn btn-primary" <?= $product['quantity'] == 0 ? 'disabled' : '' ?>>Add To Shopping Cart</button>
                </div>
            </div>
        </div>
        <div id="product-details">
            <h2 class="sub-title">Details</h2>
            <p id="description"><?= $product["description"] ?></p>
        </div>
        <hr />
        <div id="reviews">
            <h2 class="sub-title">Reviews</h2>
        </div>
    </div>
</div>
<script src="/javascript/AjaxHandler.js"></script>
<script>
    AjaxHandler.setToken("<?= csrf_token() ?>");

    // handle adding to cart
    let button = document.getElementById("add-cart-button");
    button.addEventListener('click', () => addToCart("<?= $product["id"] ?>"), false);

    function addToCart(productId) {
        let quantity = document.getElementById("quantity")
        button.classList.add("loading-button");
        button.innerHTML = "<div class='spinner-grow spinner-grow-sm' role='status' style='padding: 0.375rem 0.675rem'>";

        AjaxHandler.ajaxPost("<?= base_url('/cart/addProductToCart') ?>", {
                quantity: quantity.value,
                id: productId
            },
            (data) => {
                handleResponse(data)
            });
    }

    function handleResponse(data) {
        button.classList.remove("loading-button");
        button.classList.remove("failed-button");
        if (data["success"] == true) {
            button.classList.add("activated-button");
            button.innerHTML = "Added to Cart!";
        } else {
            button.classList.add("failed-button");
            button.innerHTML = "Not Enough In Stock";
        }
    }

    // update price when quantity is changed
    let priceElem = document.getElementById("price");

    updatePrice(1);

    function updatePrice(value) {
        price = "" + (<?= $product["price"] ?> * value).toFixed(2);
        priceSplit = price.split(".");
        priceBase = priceSplit[0];
        priceCents = priceSplit[1];
        priceElem.innerHTML = `€${priceBase}<sup>${priceCents}</sup>`;
    }

    // === image slider script ===    
    // source: https://www.w3schools.com/howto/howto_js_slideshow_gallery.asp
    let slideIndex = 0;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        let i;
        let sliderImages = document.getElementsByClassName("product-file-slider");
        let thumbnailImages = document.getElementsByClassName("product-file-thumbnail");
        if (sliderImages.length == 0)
            return;
        if (n >= sliderImages.length) {
            slideIndex = 0;
        }
        if (n < 0) {
            slideIndex = thumbnailImages.length - 1;
        }
        for (i = 0; i < thumbnailImages.length; i++) {
            thumbnailImages[i].classList.remove("active");
        }
        for (i = 0; i < sliderImages.length; i++) {
            sliderImages[i].classList.remove("active");
        }
        sliderImages[slideIndex].classList.add("active");
        thumbnailImages[slideIndex].classList.add("active");
    }
</script>