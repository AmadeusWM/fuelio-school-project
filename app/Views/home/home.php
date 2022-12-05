<div>
    <div class="colored-back">
        <h1 id="home-title">Welcome back!</h1>
        <p>Please have a look through our catalog.</p>
    </div>
    <div id="home-products-container">
        <?php foreach ($products as $product) { ?>
            <div id="home-product-container" class="hover-box">
                <h2><?= $product['name'] ?></h2>
            </div>
        <?php } ?>
    </div>
</div>