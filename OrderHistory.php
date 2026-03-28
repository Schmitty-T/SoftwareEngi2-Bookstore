<?php session_start();

    $db = new PDO("sqlite:bookstore.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   // $userId = $_SESSION["userId"];
   // $username = $_SESSION["username"];
    $userId = 1; //Testing order history page
    $username = "testuser";

    $stmt = $db->prepare("SELECT * FROM Orders WHERE userId = ?");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name = "author" content="Yuni Lin">
    <link rel="stylesheet" href="OrderHistory.css">
    <title>Order History</title>
</head>

<body>
    <header>
        <div id="headerTop">
            <div class="headerLeft"></div>
            <div id="logo">
                <img src="McNeeseLogo.png" alt="Bookstore Logo">
            </div>
            <div id="userPanel">
                <span>Hello, <span id="username"></span>Johnatan</span>
                <button><a href="Login.html">Log Out</a></button>
            </div>
        </div>
        <nav id="navBar">
            <ul>
                <li><a href="Homepage.html">Homepage</a></li>
                <li><a href="Categories.php">Categories</a></li>
                <li><a href="Cart.html">Shopping Cart</a></li>
                <li><a href="OrderHistory.php" class="active">Order History</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Order History</h1>
        <div id="orderHistoryContainer">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="orderHistoryTableBody">
                    <?php if (count($orders) > 0): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= $order['orderId']?></td>
                                <td><?= $order['orderDate']?></td>
                                <td>$<?= number_format($order['total'], 2) ?></td>
                                <td><?= $order['status']?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4"> No order history found.</td>
                        </tr>
                    <?php endif;?>
                </tbody>
            </table>
        </div>
    </main> 