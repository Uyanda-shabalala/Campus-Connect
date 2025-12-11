<div class="search">
    <form method="get" name="search-bar" id="search-bar">
        <span class="material-symbols-outlined">search</span>
        <input type="text" placeholder="Search..." name="user-search">
        <button type="submit">Go</button>
    </form>


    <div class="searh-error" id="search-error">

    </div>
</div>

<?php
if (isset($_GET['user-search'])) {
    $username = trim($_GET['user-search']);
    include __DIR__ . "/functions_user.php";

    if (search_user($username, $conn)) {
        header("Location: searched_user.php");
        exit(); // stop further script execution
    } else {
        echo "
    <div class='search-error' id='search-error'>";
        echo "<p style:color >'User not found'</p>";
        echo "</div>";
    }
}
?>