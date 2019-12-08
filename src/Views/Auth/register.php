<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

<div class="card mx-auto maxWidth540">
    <div class="card-header">
        Register
    </div>
    <div class="card-body">
        <form id="register" data-ajax="false">
            <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" name="email" id="email" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="password1" class="col-sm-4 col-form-label">Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="password1" id="password1" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="password2" class="col-sm-4 col-form-label">Confirm Password</label>
                <div class="col-sm-8">
                    <input type="password" class="form-control" name="password2" id="password2" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="tagline" class="col-sm-4 col-form-label">Tagline</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="tagline" id="tagline" required rows="2"></textarea>
                </div>
            </div>
        </form>
        <div class="text-right">
            <a class="btn btn-secondary text-light form-button" id="register-submit">Register</a>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>