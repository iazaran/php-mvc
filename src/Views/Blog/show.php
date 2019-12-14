<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>
    
    <div class="row">
        <div class="col-12 bg-light px-0">
            <small class="text-secondary border-left border-right border-secondary px-2 position-absolute rotate90 topRightOuter">📅 <?= date("Y/m/d H:i", strtotime($post['updated_at'])); ?></small>
            <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-body"><h1 class="display-1 text-center mx-4"><?= $post['title']; ?></h1></a>
            <div class="media">
                <?php
                if ($imgSrc !== '') {
                ?>
                <img src=<?= $imgSrc ?> class="mr-2 leftMediaFull" alt="<?= $post['title']; ?>">
                <?php
                }
                ?>
                <div class="media-body">
                    <hr class="mb-1 mt-0 mx-2">
                    <p class="mb-2 mx-2"><?= $post['subtitle']; ?>...</p>
                    <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-dark border border-dark rounded-pill pl-2 pr-0 m-2 linkButton">Read More 〉</a>
                    <h6 class="float-sm-right mt-2 mt-sm-0 mx-2">
                        <a href="mailto:<?= userInfo($post['user_id'])['email']; ?>" class="text-dark" data-toggle="tooltip" data-placement="left" title="<?= userInfo($post['user_id'])['tagline']; ?>">😊 <?= substr(userInfo($post['user_id'])['email'], 0, strpos(userInfo($post['user_id'])['email'], '@')); ?></a>
                        <?php
                        if (currentUser()['id'] === $post['user_id']) {
                        ?>
                        <a href="<?= URL_ROOT . '/blog/update/' . $post['slug'] ?>" class="badge badge-light">✍️</a>
                        <?php
                        }
                        ?>
                    </h6>
                </div>
            </div>
        </div>
    </div>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>