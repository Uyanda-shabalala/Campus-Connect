<!-- app/Includes/components/user_card.php -->

<div class="card">
    <?php
    error_log("Profile Picture Path: " . print_r($_SESSION['receiver_profile_pic'], true));
    echo '<img src="../' . htmlspecialchars($_SESSION['receiver_profile_pic']) . '" alt="Profile Picture" width="100">';
    ?>

    <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['receiver_name']); ?></p>
    <p><strong>username:<?php echo htmlspecialchars($_SESSION['receiver_username']); ?></p>
    <p>
        <strong>Campus:</strong>
        <?php echo htmlspecialchars($_SESSION['receiver_campus']); ?>
    </p>
    <button type="button" id="messagebtn">message user</button>

    <script>
        document.getElementById("messagebtn").onclick = () => {
            window.location.href = "messages.php"
        }
    </script>

</div>