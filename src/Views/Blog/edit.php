<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

    <div class="card mx-auto my-3 maxWidth992">
        <div class="card-header font-weight-bold text-uppercase">
            Update post
        </div>
        <div class="card-body">
            <form id="blog-update" data-ajax="false" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                <input type="hidden" name="id" value="<?= $data['post']['id']; ?>">
                <div class="form-group row">
                    <label for="category" class="col-sm-3 col-form-label">Category</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="category" id="category"
                               value="<?= $data['post']['category'] ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-sm-3 col-form-label">Title</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="title" id="title"
                               value="<?= $data['post']['title'] ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subtitle" class="col-sm-3 col-form-label">Subtitle</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="subtitle" id="subtitle"
                               value="<?= $data['post']['subtitle'] ?>" required>
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
                        <textarea class="form-control" name="body" id="body" rows="13" required><?= str_replace('&', '&amp;', $data['post']['body']) ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="position" class="col-sm-3 col-form-label">Position</label>
                    <div class="col-sm-9">
                        <select class="custom-select" name="position" id="position" aria-describedby="positionHelpBlock"
                                required>
                            <option value="1" <?php if ($data['post']['position'] == 1) echo 'selected'; ?>>One</option>
                            <option value="2" <?php if ($data['post']['position'] == 2) echo 'selected'; ?>>Two</option>
                            <option value="3" <?php if ($data['post']['position'] == 3) echo 'selected'; ?>>Three
                            </option>
                        </select>
                        <small id="positionHelpBlock" class="form-text text-muted">
                            Set the position of post in home page. `One` has a higher priority.
                        </small>
                    </div>
                </div>
            </form>
            <div class="text-right">
                <a class="btn btn-secondary text-light form-button" id="blog-update-submit">Update</a>
                <a class="btn btn-danger text-light form-delete-button" id="<?= $data['post']['slug'] ?>">Delete</a>
            </div>
        </div>
    </div>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>