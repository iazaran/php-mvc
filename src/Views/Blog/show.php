<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>
    
    <div class="row">
        <div class="col-12 bg-light px-0">
            <h1 class="display-2 text-center mx-4"><?= $data['post']['title']; ?></h1>
            <small class="text-secondary border-left border-right border-secondary mx-2 px-2"><span class="badge badge-secondary mr-2"><?= $data['post']['category']; ?></span> 📅 <?= date("Y/m/d H:i", strtotime($data['post']['updated_at'])); ?></small>
            <h6 class="float-right mt-2 mx-2">
                <a href="mailto:<?= userInfo($data['post']['user_id'])['email']; ?>" class="text-dark" data-toggle="tooltip" data-placement="left" title="<?= userInfo($data['post']['user_id'])['tagline']; ?>">😊 <?= substr(userInfo($data['post']['user_id'])['email'], 0, strpos(userInfo($data['post']['user_id'])['email'], '@')); ?></a>
                <?php
                if (currentUser()['id'] === $data['post']['user_id']) {
                ?>
                <a href="<?= URL_ROOT . '/blog/update/' . $data['post']['slug'] ?>" class="badge badge-light">✍️</a>
                <?php
                }
                ?>
            </h6>
            <div class="bodyContent py-2 px-2 px-sm-5"><?= $data['post']['body']; ?></div>
        </div>
    </div>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>