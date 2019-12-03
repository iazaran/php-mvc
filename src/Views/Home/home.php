<?php require_once APP_ROOT . '/src/Views/Include/header.php'; ?>

    <h1>Home page</h1>
    <p>A list of recent posts:</p>
    <ul>
        <?php
        foreach ($data['posts'] as $post) {
            echo '<li><a href="' . URL_ROOT . '/posts/' . $post['slug'] . '">' . $post['title'] . '</a></li>';
        }
        ?>
    </ul>
<?php require_once APP_ROOT . '/src/Views/Include/footer.php'; ?>