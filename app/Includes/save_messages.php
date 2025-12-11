<?php
session_start();
include_once '../config/database.php';
include_once 'functions_messages.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $message = $data['message'] ?? null;

    if ($message) {
        $saved = send_message($conn, $message);
        if ($saved) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to save message"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No message provided"]);
    }
}
?>