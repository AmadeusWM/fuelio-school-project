<div id="orders-page">
    <h2 class="m-2"><?= $title ?></h2>
    <div id="orders-container">
        <?php foreach ($orders as $order) {
            $totalPrice = 0;
        ?>
            <div class="order-container">
                <?php foreach ($order["order_products"] as $orderProduct) {
                    $price = $orderProduct["price_product"] * $orderProduct["order_quantity"];
                    $totalPrice += $price;
                ?>
                    <div class="order-product-container">
                        <a href='<?= base_url("/store/product" . "/" . $orderProduct["id"]) ?>'>
                            <?php
                            $files = $orderProduct["files"];
                            if (isset($files) && isset($files[0]))
                                $file = $files[0];
                            if ($file["file_type"] == "image") {
                            ?>
                                <img class="thumbnail" src="/UploadedFiles/products/<?= $file["file_name"] ?>">
                            <?php } else { ?>
                                <video class="thumbnail">
                                    <source src="/UploadedFiles/products/<?= $file['file_name'] ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php } ?>
                        </a>
                        <div class="content">
                            <div class="product-details">
                                <div class="left">
                                    <div class="top-wrapper">
                                        <a class="webshop" href="<?= base_url("/store/webshop") . "/" . $orderProduct["webshop_id"] ?>"><?= $orderProduct["webshop_name"] ?></a>
                                        <a class="product-name" href='<?= base_url("/store/product" . "/" . $orderProduct["id"]) ?>'>
                                            <?= $orderProduct["name"] ?>
                                        </a>
                                    </div>
                                    <div class="bottom-wrapper">
                                        <p>Origin: <?= $orderProduct["origin"] ?></p>
                                    </div>
                                </div>
                                <div class="right">
                                    <p>€<?= number_format($price, 2) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <hr />
                <div class="bottom-order">
                    <b>Cost Overview</b>
                    <div class="container-info">
                        <p>Total: </p>
                        <p id="total">€<?= number_format($totalPrice, 2) ?></p>
                    </div>
                    <hr />
                    <b>Delivery/Billing Address</b>
                    <div class="container-delivery">
                        <p><?= $order["first_name"] ?> <?= $order["last_name"] ?></p>
                        <p><?= $order["street"] ?> <?= $order["house_number"] ?></p>
                        <p><?= $order["postal_code"] ?></p>
                        <p><?= $order["house_number"] ?></p>
                        <p><?= $order["city"] ?></p>
                        <p><?= $order["country"] ?></p>
                    </div>
                    <hr />
                    <b>Delivery Date</b>
                    <p><?= $order["delivery_date"] ?></p>
                    <b>Delivery Status</b>
                    <?= ($order["status"] == "delivered" ? "<p class='green'>" : "<p class='red'>") 
                            . ucfirst($order["status"]) . "</p>"?>
                    <div class="button-row">
                        <button class="btn btn-outline-success">
                            Product Delivered
                        </button>
                        <button class="btn btn-outline-danger">
                            Cancel Order
                        </button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>