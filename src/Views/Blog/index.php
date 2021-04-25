<?php
    use App\Helper;
    use App\UserInfo;

    require_once APP_ROOT . '/src/Views/Include/header.php';

    $counter = 0;
    $page = 1;
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }

    $slug = '';
    foreach ($data['posts'] as $post) {
        if ($counter >= ($page - 1) * 5 && $counter < $page * 5) {
?>
        <div class="row">
            <div class="col-12 px-0 border-bottom border-dark pb-2 <?php if ($post['position'] == 1) { echo 'bg1'; } elseif ($post['position'] == 2) { echo 'bg2'; } else { echo 'bg3'; } ?>">
                <small class="text-secondary border-left border-right border-secondary px-2 position-absolute rotate90 topRightOuter">
                    📅 <?= date("Y/m/d H:i", strtotime($post['updated_at'])); ?>
                </small>
                <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-body">
                    <h1 class="display-3 text-center mx-5"><?= $post['title']; ?></h1>
                </a>
                <div class="media">
                <?php
                    if (file_exists('./assets/images/' . Helper::slug($post['title'], '-', false) . '.jpg')) {
                ?>
                        <img src="assets/images/<?= Helper::slug($post['title'], '-', false)?>.jpg" class="mr-2 leftMediaBlog" alt="<?= $post['title']; ?>">
                <?php
                    }
                ?>
                    <div class="media-body">
                        <hr class="mb-1 mt-0 ml-2 mr-2 mr-sm-5">
                        <p class="my-4 ml-2 mr-2 mr-sm-5"><?= $post['subtitle']; ?>...</p>
                        <a href="<?= URL_ROOT . '/blog/' . $post['slug']; ?>" class="text-dark border border-dark rounded-pill px-2 pr-0 m-2 linkButton">Read More 〉</a>
                        <h6 class="float-sm-right mt-2 mt-sm-0 mx-2">
                            <a href="mailto:<?= UserInfo::info($post['user_id'])['email']; ?>" class="text-dark" data-toggle="tooltip" data-placement="left" title="<?= UserInfo::info($post['user_id'])['tagline']; ?>">
                                😊 <?= substr(UserInfo::info($post['user_id'])['email'], 0, strpos(UserInfo::info($post['user_id'])['email'], '@')); ?>
                            </a>
                            <?php
                                if (UserInfo::current()['id'] === $post['user_id']) {
                            ?>
                                <a href="<?= URL_ROOT . '/blog/update/' . $post['slug'] ?>"
                                   class="badge badge-light">✍️</a>
                            <?php
                                }
                            ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
    $counter++;
}
?>
    <nav aria-label="Page navigation" class="custom-pagination mt-3">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($page == 1) echo 'disabled'; ?>">
                <a class="page-link" href="<?= URL_ROOT . '/blog?page=' . ($page - 1) ?>" tabindex="-1" <?php if ($page == 1) echo 'aria-disabled="true"'; ?>>Previous</a>
            </li>
            <?php
                for ($i = max(1, $page - 2); $i <= floor(count($data['posts']) / 5) + 1; $i++) {
                    if ($i == $page) echo '<li class="page-item active" aria-current="page"><a class="page-link">' .
                        $i . '</a></li>';
                    else echo '<li class="page-item"><a class="page-link" href="' . URL_ROOT . '/blog?page=' . $i . '">' . $i . '</a></li>';
                }
            ?>
            <li class="page-item <?php if ($page * 5 > count($data['posts'])) echo 'disabled'; ?>">
                <a class="page-link" href="<?= URL_ROOT . '/blog?page=' . ($page + 1) ?>" <?php if ($page * 5 > count($data['posts'])) echo 'aria-disabled="true"'; ?>>Next</a>
            </li>
        </ul>
    </nav>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>