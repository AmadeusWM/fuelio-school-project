<div id="add-product-container">
    <h2 class="m-2"><?= $title ?></h2>
    <?= form_open_multipart("/account/ProductController/addProduct") ?>
    <form method="post" action="<?php echo base_url("/account/ProductController"); ?>/addProduct">
        <?= csrf_field() ?>
        <input type="text" name="name" class="form-control mb-2" placeholder="Product Name">
        <input type="text" name="price" class="form-control mb-2" placeholder="Price">
        <input type="text" name="description" class="form-control mb-2" placeholder="Description">
        <input type="text" name="origin" class="form-control mb-2" placeholder="Origin">
        <input type="text" name="quantity" class="form-control mb-2" placeholder="Quantity">
        <input type="text" name="product_category" class="form-control mb-2" placeholder="Product Category">
        <input multiple type="file" name="img_files[]" size="20" class="form-control mb-2" />
        <ul class="errors-validation">
            <!-- report csrf protection errors -->
            <?= session()->getFlashdata('error') ?>
            <!-- report validation errors 🤌 -->
            <?= service('validation')->listErrors() ?>
        </ul>
        <button type="submit" class="btn btn-primary m-2">Add Product</button>
    </form>
</div>