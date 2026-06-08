<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $cartItems = runQuery($pdo, "SELECT c.cart_id, c.quantity, p.product_id, p.product_name, p.price FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? ORDER BY c.created_at DESC;", [$_SESSION['user_id']], true);
        $paymentMethod = $_POST['payment_method'] ?? 'COD';
        if (empty($cartItems)) {
            echo json_encode(['success' => false, 'error' => 'Your cart is empty.']);
            exit;
        }
        $totalPrice = $_POST['total_price'] ?? 0;
        $pdo->beginTransaction();

        //Get current address id of user
        $stmt = $pdo->prepare("SELECT current_address_id FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $addressId = $stmt->fetchColumn();
        $_SESSION['address_id'] = $addressId; // Store in session for later use

        // Insert order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount,address_id) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $totalPrice, $_SESSION['address_id'] ?? null]);

        //Insert order items
        $orderId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
        foreach ($cartItems as $item) {
            //Deduct stock
            $stmtStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?");
            $stmtStock->execute([$item['quantity'], $item['product_id'], $item['quantity']]);
            if ($stmtStock->rowCount() === 0) {
                $pdo->rollback();
                echo json_encode(['success' => false, 'error' => 'Insufficient stock for product: ' . $item['product_name']]);
                exit;
            }
            //Proceed to normal operation if stock is available
            $subtotal = $item['price'] * $item['quantity'];
            $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price'], $subtotal]);
        }

        //Give notification to admin
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id,title, message,type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], "New Order Placed", "New order #$orderId placed.", "order"]);

        //Log payment method
        $stmt = $pdo->prepare("INSERT INTO payments (order_id, payment_method, amount,payment_status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$orderId, $paymentMethod, $totalPrice, 'paid']);

        // Clear cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);

        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        $pdo->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>