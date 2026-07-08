<?php
session_start();
header('Content-Type: application/json');

if (isset($_GET['lang'])) {
    $allowed = ['fr', 'en', 'wo'];
    if (in-array($_GET['lang'], $allowed)) {
        $_SESSION['lang'] = $_GET['lang'];
        echo json_encode(['success' => true]);
        exit;
    }
}
echo json_encode(['success' => false]);
?>