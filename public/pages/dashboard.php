<?php
session_start();

include '../../app/Includes/components/header.php';
include_once '../../app/config/database.php';
include_once '../../app/Includes/functions_post.php';
include_once '../../app/Includes/components/validation.php';
include __DIR__ . "/../../app/Includes/search.php";
?>

<head>
    <title>Home - Campus Connect</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>

<main class="container">


    <section class="create-post-section">
        <form action="" method="post" enctype="multipart/form-data" class="create-post">
            <h2>
                Create Post
            </h2>
            <textarea placeholder="Share something..." maxlength="100" name="content"></textarea>
            <input type="file" name="post_image" accept="image/*,video/*" required>
            <input type="submit" value="Post" name="submit">

        </form>
    </section>

    <?php

    // PHP code to handle post submission will go here
    


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $content = $_POST['content'];
        $image = $_FILES['post_image'];

        if (empty($content) && $image['error'] == 4) {
            // No content and no image uploaded
            error_log("No content or image provided for the post.");
            header("Location: dashboard.php?error=emptypost");
            exit();

        } else {



            if (!createPost($conn, $content, $image)) {
                ("Failed to create post.");
                header("Location: dashboard.php?error=postfailed");
                exit();
            } else {
                header("Location: dashboard.php?success=postcreated");
                exit();

            }
            ;
        }


    }

    ?>



    <!-- Timeline -->
    <section class="timeline">

        <?php

        $posts = getPosts($conn, 10);

        foreach ($posts as $post) {
            echo '<div class="card">';
            echo '<p><strong>' . htmlspecialchars($post['username']) . '</strong> shared a post</p>';
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
    </section>


</main>
<?php include '../../app/Includes/components/footer.php'; ?>