<div class="feedback-page">
    <div class="message-container failure">
        <h2>Something went wrong!</h2>
        <div class='errors'>
            <?= session()->getFlashdata('errors') ?>
        </div>
    </div>
</div>