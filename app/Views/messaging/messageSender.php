<div id="message-sender-page">
    <h2 class="title">Send message to <?= $receiver["username"] ?></h2>
    <form id="message-container" method="post" action="<?=base_url("message/messageUser") . "/" . $receiver["id"]?>">
        <?= csrf_field() ?>
        <input required name="title" min="1" max="256" class="form-control" placeholder="Title">
        <textarea required name="content" min="1" max="2048" class="form-control" placeholder="Your Message"></textarea>
        <button class="btn btn-primary" type="submit">
            <i class="bi bi-envelope"></i> Send
        </button>
    </form>
</div>