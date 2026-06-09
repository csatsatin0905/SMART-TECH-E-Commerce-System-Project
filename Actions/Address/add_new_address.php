<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $fullName = htmlspecialchars($_POST['full_name']);
        $addressLine = htmlspecialchars($_POST['address_line']);
        $city = htmlspecialchars($_POST['city']);
        $province = htmlspecialchars($_POST['province']);
        $postalCode = htmlspecialchars($_POST['postal_code']);
        $phone = htmlspecialchars($_POST['phone']);

        $sql = "INSERT INTO addresses (user_id, full_name, address_line, city, province, postal_code, phone) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $result = runQuery($pdo, $sql, [$_SESSION['user_id'], $fullName, $addressLine, $city, $province, $postalCode, $phone]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}