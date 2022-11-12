<div>
    <h2 class="m-1">Your Profile</h2>
    <?= form_open_multipart('/account/updateProfile') ?>
    <form method="post" action="<?php echo base_url(); ?>/account/updateProfile">
        <?= csrf_field() ?>
        <input id="input-webshop-name" type="text" name="webshop_name" value="<?= $webshop_name ?>" class="form-control profile-information-input" placeholder="Webshop Name">
        <textarea id="input-description" type="textarea" name="description" class="form-control profile-information-input" placeholder="Your Description"><?= $description ?></textarea>
        <input id="input-business-email" type="text" name="business_email" value="<?= $business_email ?>" class="form-control profile-information-input" placeholder="Business Email">
        <input id="input-telephone" type="text" name="telephone" value="<?= $telephone ?>" class="form-control profile-information-input" placeholder="Telephone">
        <input id="input-mobile" type="text" name="mobile" value="<?= $mobile ?>" class="form-control profile-information-input" placeholder="Mobile">
        <input id="input-website" type="text" name="website" value="<?= $website ?>" class="form-control profile-information-input" placeholder="Your Website">
        <textarea id="input-other" type="text" name="other" class="form-control profile-information-input" placeholder="Other Information"><?= $other ?></textarea>
        <input multiple type="file" name="img_files[]" size="20" class="form-control profile-information-input" />
        <!-- report csrf protection errors -->
        <?= session()->getFlashdata('error') ?>
        <!-- report validation errors -->
        <?= service('validation')->listErrors() ?>
        <button type="submit" class="btn btn-primary m-2">Update</button>
    </form>
    <hr />
    <h2 class="mb-3">Your Account Images</h2>
    <div id="profile-images">
        <?php
        if ($images) {
            foreach ($images as $image) { ?>
                <div id="<?=$image['id']?>" class="profile-image-container">
                    <button name="image-remove-button" id="<?=$image['id']?>" class="profile-trash-button">
                        <i class="bi bi-trash profile-trash-icon" aria-label="Remove Image"></i>
                    </button>
                    <img src="<?= $image['image_location'] ?>" class="profile-image" />
                </div>
        <?php }
        } ?>
    </div>
</div>
<script>
    let buttons = document.getElementsByName("image-remove-button");
    for(button of buttons){
        let imageId = button.getAttribute("id");
        button.addEventListener('click', () => removeImage(imageId), false);
    }

    function removeImage(imageId){
        
    }
</script>