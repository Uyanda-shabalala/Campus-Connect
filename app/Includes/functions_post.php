<?php
//this file contains functions related to posts (e.g viewing posts, creating posts, deleting posts, etc)
include __DIR__ . '/../config/database.php';

function createPost($conn, $content, $image)
{
    $user_id = $_SESSION['user_Id']; // Assuming user ID is stored in session
    //function to create a new post    
    include __DIR__ . '/../config/constants.php';

    error_log("SESSION ID: " . print_r($_SESSION, true));
    //image upload handling 
    $imagePath = ImageUpload(image: $image);
    error_log("User ID: " . $user_id);
    error_log("Image Path: " . $imagePath);
    error_log("Content: " . $content);
    //handling content upload 
    $sql = "INSERT INTO posts (userId, content, image_path, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
        header(header: "location: ../../public/pages/dashboard.php?error=stmtfailed");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $content, $imagePath);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }



    return true;
}


function ImageUpload($image)
{
    include __DIR__ . '/../config/constants.php';
    //image upload handling
    error_log("Image data: " . print_r($post_images_path, true));



    if ($image && $image["error"] == 0) {

        $filetype = mime_content_type($image["tmp_name"]);
        //mime_content_type reades the header instead of the extension thus making safer incase someone changes a .exe to .jpg to inject malware 



        if (str_starts_with($filetype, "image/")) {
            $fileName = uniqid() . "_" . $image["name"];

            //creating the full path for the file
            $fileTmpName = $image["tmp_name"];
            $fileDestination = $post_images_path . $fileName;

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $filePathForDB = "uploads/post_images/" . $fileName;
                return $filePathForDB;

            } else {
                error_log("Failed to move uploaded file.");
                header("Location: ../../public/pages/dashboard.php?error=fileuploadfailed");
                exit();

            }

        } elseif (str_starts_with($filetype, 'video/')) {
            $fileName = uniqid() . "_" . $image["name"];

            //creating the full path for the file
            $fileTmpName = $image["tmp_name"];
            $fileDestination = $post_videos_path . $fileName;

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $filePathForDB = "uploads/post_videos/" . $fileName;
                return $filePathForDB;

            } else {
                error_log("Failed to move uploaded file.");
                header("Location: ../../public/pages/dashboard.php?error=fileuploadfailed");
                exit();

            }

        } else {
            error_log("Unsupported file type: " . $filetype);
            header("Location: ../../public/pages/dashboard.php?error=unsupportedfiletype");
            exit();
        }
    }

}
function deletePost($conn, $post_id)
{
    //function to delete a post    
}

function myPosts($conn)
{
    //function to retrieve posts made by a specific user
    $user_id = $_SESSION['user_Id']; // Assuming user ID is stored in session
    $sql = "SELECT * FROM posts WHERE userId = ? ORDER BY created_at DESC";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
        header("location: ../../public/pages/dashboard.php?error=stmtfailed");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $posts = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);
        return $posts;
    }
    ;



}

function receiver_posts($conn)
{

    $user_id = $_SESSION['receiver_Id']; // Assuming user ID is stored in session
    $sql = "SELECT * FROM posts WHERE userId = ? ORDER BY created_at DESC";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
        header("location: ../../public/pages/dashboard.php?error=stmtfailed");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $posts = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        return $posts;
    }


}

function getPosts($conn, $limit = 10)
{
    //function to retrieve posts from the database the limits the number of posts retrieved at a time before the page refreshes
    $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        error_log("Failed to prepare statement: " . mysqli_error($conn));
        header("location: ../../public/pages/dashboard.php?error=stmtfailed");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $resultData = mysqli_stmt_get_result($stmt);
        $posts = mysqli_fetch_all($resultData, MYSQLI_ASSOC);
        mysqli_stmt_close($stmt);

        //sql to get username from user id
        foreach ($posts as &$post) {
            $userId = $post['userId'];
            $userSql = "SELECT userUsername FROM users_info WHERE userid = ?";
            $userStmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($userStmt, $userSql)) {
                error_log("Failed to prepare user statement: " . mysqli_error($conn));
                continue;
            }
            mysqli_stmt_bind_param($userStmt, "i", $userId);
            mysqli_stmt_execute($userStmt);
            $userResult = mysqli_stmt_get_result($userStmt);
            if ($userRow = mysqli_fetch_assoc($userResult)) {
                $post['username'] = $userRow['userUsername'];
            } else {
                $post['username'] = "Unknown";
            }
            mysqli_stmt_close($userStmt);

        }
        return $posts;
    }
}

//function to like a post will be added in the future 


//
?>