<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

    <div class="card mx-auto my-3 maxWidth992">
        <div class="card-header font-weight-bold text-uppercase">
            Create a post
        </div>
        <div class="card-body">
            <form id="blog-create" data-ajax="false" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                <div class="form-group row">
                    <label for="category" class="col-sm-3 col-form-label">Category</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="category" id="category" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-sm-3 col-form-label">Title</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subtitle" class="col-sm-3 col-form-label">Subtitle</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="subtitle" id="subtitle" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-sm-3 col-sm-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" id="image">
                            <label class="custom-file-label" for="image">Choose feature image</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="body" class="col-sm-3 col-form-label">Body</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="body" id="body" rows="13" required></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="position" class="col-sm-3 col-form-label">Position</label>
                    <div class="col-sm-9">
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