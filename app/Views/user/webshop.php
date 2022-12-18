<div id="webshop-page">
    <div id="webshop-display">
        <div id="images-container">
            <?php foreach ($webshop["images"] as $image) { ?>
                <div class="image-container">
                    <img src="/UploadedFiles/userImages/<?= $image["image_name"] ?>">
                </div>
            <?php } ?>
        </div>
    </div>
    <h1 class="webshop-title"><?= $webshop["webshop_name"] ?></h1>
    <div id="webshop-description-container">
        <p id="webshop-description"><?= $webshop["description"] ?></p>
    </div>
    <hr>

    <div class="bottom-page-container">
        <ul class="extra-information">
            <?php if ($webshop["business_email"]) { ?>
                <li>
                    <b>Email: </b><span><?= $webshop["business_email"] ?></span>
                </li>
            <?php } ?>
            <?php if ($webshop["telephone"]) { ?>
                <li>
                    <b>Telephone: </b><span><?= $webshop["telephone"] ?></span>
                </li>
            <?php } ?>
            <?php if ($webshop["mobile"]) { ?>
                <li>
                    <b>Mobile: </b><span><?= $webshop["mobile"] ?></span>
                </li>
            <?php } ?>
            <?php if ($webshop["website"]) { ?>
                <li>
                    <b>Website: </b><span><?= $webshop["website"] ?></span>
                </li>
            <?php } ?>
            <?php if ($webshop["other"]) { ?>
                <li>
                    <b>Other: </b><span><?= $webshop["other"] ?></span>
                </li>
            <?php } ?>
        </ul>
        <a class="btn btn-outline-primary" href="<?= base_url("/message") . "/" . $webshop["id"] ?>">
            <i class="bi bi-envelope"></i> Send Message
        </a>
    </div>
</div>