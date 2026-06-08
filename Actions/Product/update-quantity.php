<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $user_id = $_SESSION['user_id'];

        //check stock first before updating
        $sql = "SELECT stock FROM products WHERE product_id = ?";
        $product = runQuery($pdo, $sql, [$product_id])->fetch();
        if (!$product) {
            echo json_encode(['success' => false, 'error' => 'Product not found.']);
            exit;
        }
        if ($quantity > $product['stock']) {
            echo json_encode(['success' => false, 'error' => 'Insufficient stock available.']);
            exit;
        }
        

        $sql = "UPDATE cart SET quantity = ? WHERE product_id = ? AND user_id = ?";
        runQuery($pdo, $sql, [$quantity, $product_id, $user_id]);


        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>