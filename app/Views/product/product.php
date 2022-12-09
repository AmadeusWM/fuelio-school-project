<div id="product-page">
    <div id="page-content">
        <div id="product-overall">
            <div id="product-left">
                <div id="image-slider">
                    <i id="image-up-arrow" class="bi bi-caret-up-fill" onclick="plusSlides(-1)"></i>
                    <!-- images in the slider -->
                    <div id="image-slider-content">
                        <?php
                        $files = $product["files"];
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
                </div>
                <div id="image-thumbnail">
                    <!-- images to show (shows only active) -->
                    <?php
                    $files = $product["files"];
                    $index = 0;
                    if (isset($files))
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
                <a id="webshop_name"><?= $product["webshop_name"]?></a>
                <p id="origin">Origin: <?= $product["origin"] ?></p>
                <h1 class="product-title"><?= $title ?></h1>
                <hr />
                <p class="price red">
                    €<?php $priceSpl = explode(".", $product["price"]);
                        echo $priceSpl[0] . "<sup>$priceSpl[1]</sup>" ?>
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
                    <input type="number" class="form-control quantity" value="1" />
                    <button class="btn btn-primary">Add To Shopping Cart</button>
                </div>
            </div>
        </div>
        <div id="product-details">
            <h2 class="sub-title">Details</h2>
            <p id="description"><?= $product["description"] ?></p>
        </div>
        <hr/>
        <div id="reviews">
            <h2 class="sub-title">Reviews</h2>
        </div>
    </div>
</div>
<script>
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