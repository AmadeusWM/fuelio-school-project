<div id="orders-page">
    <h2 class="m-2"><?= esc($title) ?></h2>
    <div id="orders-container">
        <?php foreach ($orders as $order) {
            $totalPrice = 0;
        ?>
            <div class="order-container">
                <?php foreach ($order["order_products"] as $orderProduct) {
                    $price = $orderProduct["price_product"] * $orderProduct["quantity"];
                    $totalPrice += $price;
                ?>
                    <div class="order-product-container">
                        <a class="thumbnail-container" href='<?= base_url("/store/product" . "/" . esc($orderProduct["product_id"])) ?>'>
                            <?php
                            $files = $orderProduct["files"];
                            if (isset($files) && isset($files[0])) {
                                $file = $files[0];
                                if ($file["file_type"] == "image") {
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
                                    <div class="top-wrapper">
                                        <a class="webshop" href="<?= base_url("/store/webshop") . "/" . esc($orderProduct["seller_id"]) ?>"><?= esc($orderProduct["webshop_name"]) ?></a>
                                        <a class="product-name" href='<?= base_url("/store/product" . "/" . esc($orderProduct["product_id"])) ?>'>
                                            <?= esc($orderProduct["name_product"]) ?>
                                        </a>
                                    </div>
                                    <div class="bottom-wrapper">
                                        <p>Origin: <?= esc($orderProduct["origin_product"]) ?></p>
                                    </div>
                                </div>
                                <div class="right">
                                    <p><?= esc($orderProduct["quantity"]) ?> x <b>€<?= number_format(esc($orderProduct["price_product"]), 2)?></b></p>
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
                        <p id="total">€<?= number_format(esc($totalPrice), 2) ?></p>
                    </div>
                    <hr />
                    <b>Delivery/Billing Address</b>
                    <div class="container-delivery">
                        <p><?= esc($order["first_name"]) ?> <?= esc($order["last_name"]) ?></p>
                        <p><?= esc($order["street"]) ?> <?= esc($order["house_number"]) ?></p>
                        <p><?= esc($order["postal_code"]) ?></p>
                        <p><?= esc($order["house_number"]) ?></p>
                        <p><?= esc($order["city"]) ?></p>
                        <p><?= esc($order["country"]) ?></p>
                    </div>
                    <hr />
                    <b>Delivery Date</b>
                    <p><?= esc($order["delivery_date"]) ?></p>
                    <b>Delivery Status</b>
                    <?= ($order["status"] == "delivered" ? "<p class='green'>" : "<p class='red'>")
                        . ucfirst(esc($order["status"])) . "</p>" ?>
                    <?php
                        if($order["status"] == "sent"){
                        ?>
                    <div class="button-row">
                        <form method="post" action="<?= base_url("account/OrdersController/orderDelivered") . "/" . esc($order["id"]) ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline-success">
                                Order Delivered
                            </button>
                        </form>
                        <form method="post" action="<?= base_url("account/OrdersController/orderCanceled") . "/" . esc($order["id"]) ?>">
                            <?= csrf_field() ?>
                            <button class="btn btn-outline-danger">
                                Cancel Order
                            </button>
                        </form>
                    </div>
                    <?php }?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>