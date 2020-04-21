<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

    <div class="card mx-auto my-3 maxWidth540">
        <div class="card-header font-weight-bold text-uppercase">
            Update post
        </div>
        <div class="card-body">
            <form id="blog-update" data-ajax="false">
                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                <input type="hidden" name="id" value="<?= $data['post']['id']; ?>">
                <div class="form-group row">
                    <label for="category" class="col-sm-4 col-form-label">Category</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="category" id="category"
                               value="<?= $data['post']['category'] ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="title" class="col-sm-4 col-form-label">Title</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="title" id="title"
                               value="<?= $data['post']['title'] ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subtitle" class="col-sm-4 col-form-label">Subtitle</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="subtitle" id="subtitle"
                               value="<?= $data['post']['subtitle'] ?>" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="body" class="col-sm-4 col-form-label">Body</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" name="body" id="body" rows="13" aria-describedby="bodyHelpBlock"
                                  required><?= $data['post']['body'] ?></textarea>
                        <small id="bodyHelpBlock" class="form-text text-muted">
                            It is better to use HTML codes. You can generate it by online editors like <a
                                    href="https://htmlg.com/html-editor/" target="_blank" rel="noreferrer">HTML
                                Editor</a>
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="position" class="col-sm-4 col-form-label">Position</label>
                    <div class="col-sm-8">
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