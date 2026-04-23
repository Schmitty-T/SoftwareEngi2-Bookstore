<!DOCTYPE html>
<?php
        $db = new PDO("sqlite:bookstore.db");
        $category = $_GET['category'] ?? null;
        $username = $_GET['username'] ?? 'Guest';
        
        $stmt = $db->query("SELECT * 
                FROM OrderItems
                INNER JOIN Products ON OrderItems.productId = Products.productId;");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $priceQuery = $db->query("SELECT SUM(price) AS total_price FROM Products");
        $row = $priceQuery->fetch(PDO::FETCH_ASSOC);
        $total = 0;
      
?>


<html>
    <head>
        <title>Cart</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel ="stylesheet" href ="Cart.css">        
    </head>
    <body>
        <header>
        <div id="headerTop">
            <div class="headerLeft"></div>
            <div id="logo">
                <img src="McNeeseLogo.png" alt="Bookstore Logo">
            </div>
            <div id="userPanel">
                <span>Hello, <?php echo htmlspecialchars($username); ?></span>
                <button><a href="Login.html">Log Out</a></button>
            </div>
        </div>
        <nav id="navBar">
            <ul>
                <li><a href="Homepage.html">Homepage</a></li>
                <li><a href="Categories.php"> Categories</a></li>
                <li><a href="cart.php" class ='active'>Shopping Cart</a></li>
                <li><a href="OrderHistory.php">Order History</a></li>
            </ul>
        </nav>
        </header>
        
        <main>
            <form method = "POST">
            <div class="CartItemsContainer">                
                <table class="CartItemsTable">
                    <thead>
                        <tr>
                            <th colspan="3">Cart Items</th>
                        </tr>
                    </thead>
                    <tbody>                        
                            <?php foreach($products as $product): ?>
                                <tr>
                                    <td class="imgcell">
                                        <?php if($product['category'] == 'book'):?>
                                            <img src="https://covers.openlibrary.org/b/isbn/<?php echo $product['isbn']; ?>-M.jpg" alt="Book Cover">
                                        <?php else:?>
                                            <img src="<?php echo $product['image'];?>" alt ="supply image">
                                        <?php endif ?>
                                    </td>
                                    <td class="titlecell">
                                        <?php if($product['category'] == 'book'):?>
                                            <p>Author:<?php echo $product['author'];?></p>
                                            <p>ISBN:<?php echo $product['isbn'];?></p>
                                            <p>$<?php echo $product['price'];?></p>
                                        <?php else:?>                                          
                                            <p><?php echo $product['title'];?></p>
                                            <p>$<?php echo $product['price'];?></p>
                                        <?php endif ?>    
                                    </td>                    
                                    <td class = 'quantity'>
                                        <button type='button' class='button' onclick ='decrease(this)'>-</button><!-- comment -->
                                        <input type ='number' name='quantity[<?php echo $product['productId'];?>]'
                                               value='1' min='0' class='input'>
                                        <button type='button' class='button' onclick='increase(this)'>+</button>
                                        <button type='button' class='removeButton'  onclick='removeItem(this)'>Remove Item</button>
                                    </td>                  
                                </tr>
                            <?php endforeach; ?>                           
                    </tbody>
                </table>     
                <div class = "CartItemsSumTable">
                        <table>
                            <thead>
                                <tr>
                                    <th colspan ="2">Cart Items Sum</th>
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
                        <button class="CheckoutButton" type ="button" onclick ="window.location.href = 'checkout.php'">
                             Check Out       
                    </button>
                </form>
                </div>    
            </div>    
        </main>
        <script src="Cart.js"></script>
    </body>
</html>
