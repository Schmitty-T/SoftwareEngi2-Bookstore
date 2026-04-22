<?php
    $db = new PDO('sqlite:bookstore.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $productId = $_GET['productId'] ?? null;
    $username = $_GET['username'] ?? null;

    if (!$username || $username === "Guest") {
    header("Location: Login.html");
    exit();
    }

    $stmtUser = $db->prepare("SELECT userId FROM users WHERE username = ?");
    $stmtUser->execute([$username]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    $userId = $user['userId'] ?? null;

    if($userId && $productId) {
        $stmt = $db->prepare("SELECT orderId FROM Orders WHERE userId = ? AND status = 'cart'");
        $stmt->execute([$userId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {

        $stmt = $db->prepare("INSERT INTO Orders (userId, orderDate, status, total) VALUES (?, datetime('now'), 'cart', 0)");
        $stmt->execute([$userId]);
        $orderId = $db->lastInsertId();
        }
        else {
        $orderId = $order['orderId'];
        }

        $stmt = $db->prepare("SELECT * FROM orderItems WHERE orderId = ? AND productId = ?");
        $stmt->execute([$orderId, $productId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
       
        $stmt = $db->prepare("UPDATE orderItems SET quantity = quantity + 1 WHERE orderId = ? AND productId = ?");
        $stmt->execute([$orderId, $productId]);
    } else {
        
        $stmt = $db->prepare("INSERT INTO orderItems (orderId, productId, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$orderId, $productId]);
    }
}
    header("Location: Cart.php?username=" . urlencode($username));
    exit();
?>