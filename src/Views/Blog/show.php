<?php
    use App\Helper;
    use App\UserInfo;

    require_once APP_ROOT . '/src/Views/Include/header.php';
?>

    <div class="row">
        <div class="col-12 bg-light px-0">
            <?php
                if (file_exists('./assets/images/' . Helper::slug($data['post']['title'], '-', false) . '.jpg')) {
            ?>
                <img src="../assets/images/<?= Helper::slug($data['post']['title'], '-', false)?>.jpg" class="featureImage" alt="<?= $data['post']['title']; ?>">
            <?php
                }
            ?>
            <h1 class="display-2 text-center mx-4"><?= $data['post']['title']; ?></h1>
            <small class="text-secondary border-left border-right border-secondary mx-2 px-2">
                <span class="badge badge-secondary mr-2"><?= $data['post']['category']; ?></span>
                📅 <?= date("Y/m/d H:i", strtotime($data['post']['updated_at'])); ?>
            </small>
            <h6 class="float-right mt-2 mx-2">
                <a href="mailto:<?= UserInfo::info($data['post']['user_id'])['email']; ?>" class="text-dark" data-toggle="tooltip" data-placement="left" title="<?= UserInfo::info($data['post']['user_id'])['tagline']; ?>">
                    😊 <?= substr(UserInfo::info($data['post']['user_id'])['email'], 0, strpos(UserInfo::info($data['post']['user_id'])['email'], '@')); ?>
                </a>
                <?php
                    if (UserInfo::current()['id'] === $data['post']['user_id']) {
                ?>
                    <a href="<?= URL_ROOT . '/blog/update/' . $data['post']['slug'] ?>" class="badge badge-light">✍️</a>
                <?php
                    }
                ?>
            </h6>
            <div class="bodyContent py-2 px-2 px-sm-5"><?= $data['post']['body']; ?></div>
            <div class="pt-4 pb-2 text-center">
                <div>
                    <a href="http://www.facebook.com/sharer.php?u=<?= URL_ROOT . '/blog/' . $data['post']['slug']; ?>&title=<?= $data['post']['title']; ?>" target="_blank">
                        <img class="socialIcon" src="../assets/images/social/facebook.png" alt="Facebook">
                    </a>
                    <a href="http://twitter.com/share?url=<?= URL_ROOT . '/blog/' . $data['post']['slug']; ?>&text=<?= $data['post']['title']; ?>&hashtags=<?= $data['post']['category']; ?>" target="_blank">
                        <img class="socialIcon" src="../assets/images/social/twitter.png" alt="Twitter">
                    </a>
                    <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?= URL_ROOT . '/blog/' . $data['post']['slug']; ?>&title=<?= $data['post']['title']; ?>&source=<?= URL_ROOT ?>" target="_blank">
                        <img class="socialIcon" src="../assets/images/social/linkedin.png" alt="LinkedIn">
                    </a>
                    <a href="http://pinterest.com/pin/create/button/?url=<?= URL_ROOT . '/blog/' . $data['post']['slug']; ?>&media=<?= URL_ROOT . '/assets/images/' . Helper::slug($data['post']['title'], '-', false) . '.jpg'?>&description=<?= $data['post']['title']; ?>" target="_blank">
                        <img class="socialIcon" src="../assets/images/social/pinterest.png" alt="Pinterest">
                    </a>
                    <a href="http://www.tumblr.com/share?v=3&u=<?= URL_ROOT . '/blog/' . $data['post']['slug']; ?>&t=<?= $data['post']['title']; ?>" target="_blank">
                        <img class="socialIcon" src="../assets/images/social/tumblr.png" alt="Tumblr">
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>