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
                                    <video class="product-file-slider" controls>
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
                            <video class="product-file-thumbnail">
                                <source src="/UploadedFiles/products/<?= $file['file_name'] ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                    <?php }
                            $index++;
                        } ?>
                </div>
            </div>
            <div id="product-right">
                <a id="webshop_name" href="<?= base_url("/Store/WebshopController") . "/" . $product["webshop_id"] ?>"><?= $product["webshop_name"] ?></a>
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
                <form class="info-row">
                    <input type="number" name="quantity" class="form-control quantity" min="1" max="<?= $product["quantity"] ?>" value="1" onchange="updatePrice(this.value)" />
                    <button class="btn btn-primary">Add To Shopping Cart</button>
                </form>
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
<script>
    let priceElem = document.getElementById("price");

    updatePrice(1);

    function updatePrice(value) {
        price="" + (<?=$product["price"]?> * value).toFixed(2);
        console.log("updating price", price);
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
        console.log("Yes");
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