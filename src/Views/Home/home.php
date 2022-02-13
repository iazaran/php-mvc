<?php
    use App\Helper;
    use App\UserInfo;

    require_once APP_ROOT . '/src/Views/Include/header.php';

    $counter = 1;
    $slug = '';
    foreach ($data['posts'] as $post) {
        if ($counter === 1 && $post['position'] === 1) {
?>
        <div class="row">
            <div class="col-12 bg-light px-0 pb-0">
                <small class="text-secondary border-left border-right border-secondary px-2 position-absolute rotate90 topRightOuter">
                    📅 <?= date("Y/m/d H:i", strtotime($post['updated_at'])); ?>
                </small>
                <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-body">
                    <h1 class="display-1 text-center mx-4 minHeight160"><?= $post['title']; ?></h1>
                </a>
                <div class="media">
                    <?php
                        if (file_exists('./assets/images/' . Helper::slug($post['title'], '-', false) . '.jpg')) {
                    ?>
                        <img src="assets/images/<?= Helper::slug($post['title'], '-', false)?>.jpg" class="mr-2 leftMediaFull" alt="<?= $post['title']; ?>">
                    <?php
                        }
                    ?>
                    <div class="media-body">
                        <hr class="mb-1 mt-0 mx-2">
                        <p class="mb-2 mx-2"><?= $post['subtitle']; ?>...</p>
                        <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>"
                           class="text-dark border border-dark rounded-pill px-2 pr-0 m-2 linkButton">Read More 〉</a>
                        <h6 class="float-sm-right mt-2 mt-sm-0 mx-2">
                            <a href="mailto:<?= UserInfo::info($post['user_id'])['email']; ?>" class="text-dark" data-toggle="tooltip" data-placement="left" title="<?= UserInfo::info($post['user_id'])['tagline']; ?>">
                                😊 <?= substr(UserInfo::info($post['user_id'])['email'], 0, strpos(UserInfo::info($post['user_id'])['email'], '@')); ?>
                            </a>
                            <?php
                                if (UserInfo::current() && UserInfo::current()['id'] === $post['user_id']) {
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
<?php
            $counter++;
        }
    }
?>

        <div class="row">
        <?php
            foreach ($data['posts'] as $post) {
                if ($post['position'] === 1 && $slug !== $post['slug']) {
        ?>
            <div class="col-12 col-md-6 border pb-2 bg1">
                <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-body">
                    <h2 class="display-3 mx-3 mb-0 minHeight115"><?= $post['title']; ?></h2>
                </a>
                <small class="text-secondary border-left border-right border-secondary px-2">
                    📅 <?= date("Y/m/d H:i", strtotime($post['updated_at'])); ?>
                </small>
                <div class="media">
                <?php
                    if (file_exists('./assets/images/' . Helper::slug($post['title'], '-', false) . '.jpg')) {
                ?>
                        <img src="assets/images/<?= Helper::slug($post['title'], '-', false)?>.jpg" class="mr-3 border-right-0 border-secondary rounded-left leftMediaHalf" alt="<?= $post['title']; ?>">
                <?php
                    }
                ?>
                    <div class="media-body">
                        <hr class="mb-3 mt-1">
                        <p class="mb-2">
                            <?= $post['subtitle']; ?>... <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-dark border border-dark rounded-pill px-2 pr-0 linkButton">Read More 〉</a>
                        </p>
                        <h6 class="position-absolute rotate90 topRightInner truncate">
                            <a href="mailto:<?= UserInfo::info($post['user_id'])['email']; ?>" class="text-dark pl-1" data-toggle="tooltip" data-placement="left" title="<?= UserInfo::info($post['user_id'])['tagline']; ?>">
                                😊 <?= substr(UserInfo::info($post['user_id'])['email'], 0, strpos(UserInfo::info($post['user_id'])['email'], '@')); ?>
                            </a>
                            <?php
                                if (UserInfo::current() && UserInfo::current()['id'] === $post['user_id']) {
                            ?>
                                    <a href="<?= URL_ROOT . '/blog/update/' . $post['slug'] ?>" class="badge badge-light">✍️</a>
                            <?php
                                }
                            ?>
                        </h6>
                    </div>
                </div>
            </div>
            <?php
                } elseif ($post['position'] === 2) {
            ?>
            <div class="col-12 col-md-6 border pb-2 bg2">
                <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-body">
                    <h3 class="display-4 mx-2"><?= $post['title']; ?></h3>
                </a>
                <div class="media">
                <?php
                    if (file_exists('./assets/images/' . Helper::slug($post['title'], '-', false) . '.jpg')) {
                ?>
                        <img src="assets/images/<?= Helper::slug($post['title'], '-', false)?>.jpg" class="mr-3 border-right-0 border-secondary rounded-left leftMediaHalf" alt="<?= $post['title']; ?>">
                <?php
                    }
                ?>
                    <div class="media-body">
                        <hr class="mb-1 mt-0">
                        <p class="mb-2">
                            <small class="text-secondary border-left border-right border-secondary px-2">
                                📅 <?= date("Y/m/d H:i", strtotime($post['updated_at'])); ?>
                            </small> <?= $post['subtitle']; ?>...
                        </p>
                        <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-dark border border-dark rounded-pill px-2 pr-0 mt-2 linkButton">Read More 〉</a>
                        <h6 class="float-sm-right mt-1">
                            <a href="mailto:<?= UserInfo::info($post['user_id'])['email']; ?>" class="text-dark" data-toggle="tooltip" data-placement="left" title="<?= UserInfo::info($post['user_id'])['tagline']; ?>">
                                😊 <?= substr(UserInfo::info($post['user_id'])['email'], 0, strpos(UserInfo::info($post['user_id'])['email'], '@')); ?>
                            </a>
                            <?php
                                if (UserInfo::current() && UserInfo::current()['id'] === $post['user_id']) {
                            ?>
                            <a href="<?= URL_ROOT . '/blog/update/' . $post['slug'] ?>" class="badge badge-light">✍️</a>
                            <?php
                                }
                            ?>
                        </h6>
                    </div>
                </div>
            </div>
            <?php
                } elseif ($post['position'] === 3) {
            ?>
            <div class="col-12 col-md-6 border pb-2 bg3">
                <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-body"><h2 class="text-center mx-2 my-3"><?= $post['title']; ?></h2></a>
                <div class="media">
                    <?php
                        if (file_exists('./assets/images/' . Helper::slug($post['title'], '-', false) . '.jpg')) {
                    ?>
                        <img src="assets/images/<?= Helper::slug($post['title'], '-', false)?>.jpg" class="mr-3 border-right-0 border-secondary
 rounded-left leftMediaHalf" alt="<?= $post['title']; ?>">
                    <?php
                        }
                    ?>
                    <div class="media-body">
                        <hr class="mb-1 mt-0">
                        <p class="mb-2">
                            <?= $post['subtitle']; ?>... <small class="text-secondary border-left border-right border-secondary px-2">📅 <?= date("Y/m/d H:i", strtotime($post['updated_at'])); ?></small>
                        </p>
                        <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-dark border border-dark rounded-pill px-2 pr-0 mt-2 linkButton">Read More 〉</a>
                        <h6 class="float-sm-right mt-1">
                            <a href="mailto:<?= UserInfo::info($post['user_id'])['email']; ?>" class="text-dark" data-toggle="tooltip" data-placement="left" title="<?= UserInfo::info($post['user_id'])['tagline']; ?>">
                                😊 <?= substr(UserInfo::info($post['user_id'])['email'], 0, strpos(UserInfo::info($post['user_id'])['email'], '@')); ?>
                            </a>
                            <?php
                                if (UserInfo::current() && UserInfo::current()['id'] === $post['user_id']) {
                            ?>
                                <a href="<?= URL_ROOT . '/blog/update/' . $post['slug'] ?>" class="badge badge-light">✍️</a>
                            <?php
                                }
                            ?>
                        </h6>
                    </div>
                </div>
            </div>
<?php
    }
}
?>
    </div>

<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>
