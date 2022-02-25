<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

<div class="card mx-auto my-3 maxWidth576">
    <div class="card-header font-weight-bold text-uppercase">
        Chat based on WebSocket
    </div>
    <div class="card-body">
        <div class="card mb-5">
            <div class="card-body">
                <div id="output"></div>
            </div>
        </div>

        <form id="chat">
            <div class="form-group row">
                <label for="client-name" class="col-sm-4 col-form-label">Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="client-name" id="client-name" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="message" class="col-sm-4 col-form-label">Message</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="message" id="message" rows="3" required></textarea>
                </div>
            </div>
        </form>
        <div class="text-right">
            <a class="btn btn-secondary text-light" id="chat-submit">Send</a>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>
