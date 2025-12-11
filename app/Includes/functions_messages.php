<?php
// This file contains functions related to messages (e.g., sending messages, viewing messages, deleting messages)

session_start(); // make sure sessions are started

// Send a message and save it to the DB
function send_message($conn, $message)
{
    $sender = $_SESSION['user_Id'] ?? null;
    $receiver = $_SESSION['receiver_Id'] ?? null;

    if (!$sender || !$receiver || !$message) {
        return false; // basic validation
    }

    $sql = "INSERT INTO messages (sender_id, receiver_id, message_text, sent_at) VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'iis', $sender, $receiver, $message);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return true;
}

// Get messages between the current user and receiver
function get_messages($conn, $limit = 50)
{
    $sender = $_SESSION['user_Id'] ?? null;
    $receiver = $_SESSION['receiver_Id'] ?? null;

    if (!$sender || !$receiver) {
        return [];
    }

    $sql = "SELECT id, sender_id, receiver_id, message_text, sent_at
            FROM messages
            WHERE (sender_id = ? AND receiver_id = ?)
               OR (sender_id = ? AND receiver_id = ?)
            ORDER BY sent_at ASC
            LIMIT ?";

    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, 'iiiis', $sender, $receiver, $receiver, $sender, $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $msgs = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $msgs;
}

// Delete a message by ID
function delete_message($conn, $messageId)
{
    $userId = $_SESSION['user_Id'] ?? null;

    if (!$userId || !$messageId) {
        return false;
    }

    $sql = "DELETE FROM messages WHERE id = ? AND (sender_id = ? OR receiver_id = ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 'iii', $messageId, $userId, $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return true;
}
?>