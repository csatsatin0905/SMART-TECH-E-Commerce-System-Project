<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['user_id'] ?? null;
    $productID = $_POST['product_id'] ?? null;
    $comment = trim($_POST['comment'] ?? '');
    $rating = $_POST['rating'] ?? null;

    if (!$productID || !$comment || $rating === null) {
        echo json_encode(['success' => false, 'message' => 'Product ID, comment, and rating are required.']);
        exit;
    }

    $sql = "INSERT INTO reviews (user_id, product_id, comment, rating) VALUES (?, ?, ?, ?);";
    try {
        runQuery($pdo, $sql, [$userID, $productID, $comment, $rating]);
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error submitting review: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}