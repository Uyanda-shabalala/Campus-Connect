<?php
session_start();
include '../../app/Includes/components/header.php';
include_once '../../app/config/database.php';
include_once '../../app/Includes/components/validation.php';
include_once '../../app/Includes/functions_user.php';
include_once '../../app/Includes/functions_post.php';

?>

<head>
    <title>Profile - Campus Connect</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>
<main class="container">
    <h2>Your Profile</h2>

    <div class="card">
        <?php
        error_log("Profile Picture Path: " . print_r($_SESSION['profile_pic'], true));
        echo '<img src="../' . htmlspecialchars($_SESSION['profile_pic']) . '" alt="Profile Picture" width="100">';
        ?>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['name']); ?></p>
        <p><strong>username:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p>
            <strong>Campus:</strong>
            <?php echo htmlspecialchars($_SESSION['campus']); ?>
        </p>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="newUserName" placeholder="Update username">
            <label for="profile_pic">Update Profile Picture:</label>
            <input type="file" name="newProfilePic">
            <input type="submit" value="Update Profile">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle profile update logic here
        

            $newProfilePic = $_FILES['newProfilePic'];
            $newusername = $_POST['newUserName'];


            if (isset($newProfilePic) && $newProfilePic['error'] == 0 && empty($newusername)) {
                if (!update_profile_pic($conn, $newProfilePic)) {
                    echo "<p class='error' style='color:red;'>Profile picture failed to update.</p>";
                } else {
                    echo "<p class= success style='color:green'>Profile picture updated. </p>";
                    exit();
                }

            } elseif (!empty($newusername) && $newProfilePic['error'] == 4) {
                update_username($conn, $newusername);
                echo "<p class= success style='color:green'>Username updated. </p>";

                exit();

            } elseif (!empty($newusername) && $newProfilePic['error'] == 0) {
                update_profile_pic($conn, $newProfilePic);
                update_username($conn, $newusername);
                echo "<p class= success style='color:green'>Username and profile picture updated successfully </p>";
                exit();

            } else {
                echo "No changes made.";
            }
        }

        ?>

    </div>

    <div class="my-posts">

        <?php

        $posts = myPosts($conn);

        foreach ($posts as $post) {
            echo '<div class="card">';
            echo '<p><strong>' . $_SESSION['username'] . '</strong> shared a post</p>';
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