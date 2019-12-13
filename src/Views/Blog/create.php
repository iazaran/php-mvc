<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

<div class="card mx-auto my-3 maxWidth540">
    <div class="card-header font-weight-bold text-uppercase">
        Create a post
    </div>
    <div class="card-body">
        <form id="blog-create" data-ajax="false">
            <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
            <div class="form-group row">
                <label for="category" class="col-sm-4 col-form-label">Category</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="category" id="category" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="title" class="col-sm-4 col-form-label">Title</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="title" id="title" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="subtitle" class="col-sm-4 col-form-label">Subtitle</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="subtitle" id="subtitle" required>
                </div>
            </div>
            <div class="form-group row">
                <label for="body" class="col-sm-4 col-form-label">Body</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="body" id="body" rows="13" aria-describedby="bodyHelpBlock" required></textarea>
                    <small id="bodyHelpBlock" class="form-text text-muted">
                        It is better to use HTML codes. You can generate it by online editors like <a href="https://htmlg.com/html-editor/" target="_blank" rel="noreferrer">HTML Editor</a>
                    </small>
                </div>
            </div>
            <div class="form-group row">
                <label for="position" class="col-sm-4 col-form-label">Position</label>
                <div class="col-sm-8">
                    <select class="custom-select" name="position" id="position" aria-describedby="positionHelpBlock" required>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3" selected>Three</option>
                    </select>
                    <small id="positionHelpBlock" class="form-text text-muted">
                        Set the position of post in home page. `One` has a higher priority.
                    </small>
                </div>
            </div>
        </form>
        <div class="text-right">
            <a class="btn btn-secondary text-light form-button" id="blog-create-submit">Create</a>
        </div>
    </div>
</div>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>