<!DOCTYPE html>
<?php
        $db = new PDO("sqlite:categories.db");
        $category = $_GET['category'] ?? null;
        $stmt = $db->query("SELECT * FROM Products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $priceQuery = $db->query("SELECT SUM(price) AS total_price FROM Products");
        $row = $priceQuery->fetch(PDO::FETCH_ASSOC);
        $total = 0;
      
?>


<html>
    <head>
        <title>CheckOut</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel ="stylesheet" href ="checkout.css">        
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
                <li><a href="Categories.php"> Categories</a></li>
                <li><a href="cart.php">Shopping Cart</a></li>
                <li><a href="OrderHistory.html">Order History</a></li>
            </ul>
        </nav>
        </header>
        
        <main>
            <form method = "POST" action = "cart.php">
            <div class="CartItemsContainer">                
                <table class="CartItemsTable">
                    <thead>
                        <tr>
                            <th colspan="3">Delivery Method</th>
                        </tr>
                    </thead>
                    <tbody>                        
                                                       
                    </tbody>
                </table>     
                <div class = "CartItemsSumTable">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan ="2">Payment Method</th>
                                </tr>
                            </thead>
                            <tbody>                                
                                <tr>                            
                                    <td class="titlecell">                                
                                        <p>Total:</p>                                
                                    </td>
                                    <td>$<span id = "total">0.00</span></td>
                                </tr>
                            </tbody>
                        </table>
                        <button action="submit" class="CheckoutButton">
                                         Confirm Check Out       
                        </button>
                </form>
                </div>    
            </div>    
        </main>
        <script src="Checkout.js"></script>
    </body>
</html>
