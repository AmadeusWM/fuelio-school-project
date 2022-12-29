<div class="d-flex justify-content-center">
    <div id="product-form-container" class="hover-box">
        <h2 class="m-2"><?= $title ?></h2>
        <?= form_open_multipart("/account/ProductsController/addProduct") ?>
        <form method="post" action="<?php echo base_url("/account/ProductsController"); ?>/addProduct">
            <?= csrf_field() ?>
            <input type="text" name="name" min="1" max="256" class="form-control mb-2" placeholder="Product Name" required>
            <input type="number" name="price" class="form-control mb-2" placeholder="Price" min="1" step="any" required>
            <textarea type="text" name="description" min="1" max="2048" class="form-control mb-2" placeholder="Description" required></textarea>
            <input type="text" name="origin" min="1" max="256" class="form-control mb-2" placeholder="Origin" required>
            <input type="number" name="quantity" class="form-control mb-2" placeholder="Quantity" required>
            <select name="product_category" class="form-select mb-2" required>
                <?php foreach ($product_categories as $product_category) { ?>
                    <option>
                        <?= $product_category["name"] ?>
                    </option>
                <?php } ?>
            </select>
            <!-- TODO: https://mdbootstrap.com/docs/b4/jquery/forms/multiselect/ -->
            <input multiple type="file" name="files[]" size="10" class="form-control mb-2" />
            <ul class="errors-validation">
                <!-- report csrf protection errors -->
                <?= session()->getFlashdata('error') ?>
                <!-- report validation errors 🤌 -->
                <?= service('validation')->listErrors() ?>
            </ul>
            <button type="submit" class="btn btn-primary m-2">Add Product</button>
        </form>
    </div>
</div>