<?php
session_start();
include '../../app/Includes/components/header.php';
include_once '../../app/config/database.php';
include_once '../../app/Includes/functions_post.php';
?>

<head>
    <title>Searched User - Campus Connect</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<main class="container">
    <h2>Searched User</h2>

    <?php include '../../app/Includes/components/user_profile.php'; ?>
    <div class="my-reciver">
        <?php
        include_once __DIR__ . '/../../app/Includes/functions_user.php';

        $posts = receiver_posts($conn);

        foreach ($posts as $post) {
            echo '<div class="card">';
            echo '<p><strong>' . $_SESSION['receiver_username'] . '</strong> shared a post</p>';
            echo '<p>' . htmlspecialchars($post['created_at']) . '</p>';
            echo '<p>' . htmlspecialchars($post['content']) . '</p>';



            if (!empty($post['image_path'])) {

                $mediaPath = '../' . htmlspecialchars($post['image_path']);

                if (str_contains($post['image_path'], 'post_images')) {
                    echo '<img src="' . $mediaPath . '" alt="Post Image" class="post-media">';
                } elseif (str_contains($post['image_path'], 'post_videos')) {
                    echo '<video controls class="post-media" autoplay loop muted>
                        <source src="' . $mediaPath . '" type="video/mp4" >
                        try using a different browser, your current browser does not support the video tag.
                      </video>';
                }

            }
            echo '<hr>';

            echo '</div>';
        }

        ?>
    </div>


</main>

<?php include '../../app/Includes/components/footer.php'; ?>