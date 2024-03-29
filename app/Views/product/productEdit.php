<div class="d-flex justify-content-center">
    <div id="product-form-container" class="hover-box">
        <h2 class="m-2"><?= esc($title) ?></h2>
        <?= form_open_multipart("/account/ProductsController/addProduct") ?>
        <form method="post" action="<?php echo base_url("/account/ProductsController"); ?>/addProduct">
            <?= csrf_field() ?>
            <input hidden type="text" name="productId" value="<?= esc($id) ?>">
            <input type="text" name="name" min="1" max="256" value="<?= esc($name) ?>" class="form-control mb-2" placeholder="Product Name" required>
            <input type="number" name="price" value="<?= esc($price) ?>" class="form-control mb-2" placeholder="Price" min="1" step="any" required >
            <textarea type="text" name="description" min="1" max="2048" class="form-control mb-2" placeholder="Description" required><?= esc($description) ?></textarea>
            <input type="text" name="origin" min="1" max="256" value="<?= esc($origin) ?>" class="form-control mb-2" placeholder="Origin" required>
            <input type="number" name="quantity" value="<?= esc($quantity) ?>" class="form-control mb-2" placeholder="Quantity" required>
            <select name="product_category" class="form-select mb-2" required>
                <?php foreach ($product_categories as $category) { ?>
                    <option <?= $category["name"] == $product_category ? "selected" : "" ?>>
                        <?= esc($category["name"]) ?>
                    </option>
                <?php } ?>
            </select>
            <input multiple type="file" name="files[]" size="10" class="form-control mb-2" />
            <ul class="errors-validation">
                <!-- report csrf protection errors -->
                <?= session()->getFlashdata('error') ?>
                <!-- report validation errors 🤌 -->
                <?= service('validation')->listErrors() ?>
            </ul>
            <button type="submit" class="btn btn-primary m-2">Update Product</button>
        </form>
        <div id="product-files">
            <?php
            if ($files) {
                foreach ($files as $file) { ?>
                    <div id="container-<?= esc($file['id']) ?>" class="product-file-container">
                        <button name="file-remove-button" id="<?= esc($file['id']) ?>" class="product-trash-button">
                            <i class="bi bi-trash files-trash-icon" aria-label="Remove File"></i>
                        </button>
                        <?php if ($file['file_type'] == 'image') { ?>
                            <img src="/UploadedFiles/products/<?= esc($file['file_name']) ?>" class="product-file" alt="Product image"/>
                        <?php } else { ?>
                            <video class="product-file" controls alt="Product video">
                                <source src="/UploadedFiles/products/<?= esc($file['file_name']) ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php } ?>
                    </div>
            <?php }
            } ?>
        </div>
    </div>
</div>
<script>
    AjaxHandler.setToken("<?= csrf_token() ?>");

    let buttons = document.getElementsByName("file-remove-button");
    for (button of buttons) {
        let fileId = button.getAttribute("id");
        button.addEventListener('click', () => removeFile(fileId), false);
    }

    function removeFile(fileId) {
        AjaxHandler.ajaxPost("<?= base_url('/account/ProductsController/removeFile') ?>", fileBody(fileId), handleResponse)
    }

    function fileBody(fileId) {
        let body = {
            "fileId": fileId,
        }
        return body;
    }

    function handleResponse(data) {
        if (data["success"]) {
            let removedFileId = data["fileId"];
            let fileContainer = document.getElementById("container-" + removedFileId);
            fileContainer.remove();
        }
    }
</script>