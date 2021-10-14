<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

    <div class="card mx-auto my-3 maxWidth576">
        <div class="card-header font-weight-bold text-uppercase">
            Login
        </div>
        <div class="card-body">
            <form id="login" data-ajax="false">
                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label">Email</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-4 col-form-label">Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                </div>
            </form>
            <div class="text-right">
                <a class="btn btn-secondary text-light form-button" id="login-submit">Login</a>
            </div>
        </div>
    </div>

<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>
