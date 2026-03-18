<!DOCTYPE html>
<?php
        $db = new PDO("sqlite:categories.db");
        $category = $_GET['category'] ?? null;
        $stmt = $db->query("SELECT * FROM Products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $priceQuery = $db->query("SELECT SUM(price) AS total_price FROM Products");
        $row = $priceQuery->fetch(PDO::FETCH_ASSOC);
        
?>

<html>
    <head>
        <title>Cart Items</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel ="stylesheet" href ="cart.css">
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
                <li><a href="cart.php" class="active">Shopping Cart</a></li>
                <li><a href="OrderHistory.html">Order History</a></li>
            </ul>
        </nav>
        </header>
        
        <main>
            <div class="CartItemsContainer">
                <table class="CartItemsTable">
                    <thead>
                        <tr>
                            <th colspan="2">Cart Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($products as $product): ?>
                            <tr>
                                <td class="imgcell">
                                    <?php if($product['category'] == 'book'):?>
                                        <img src="https://covers.openlibrary.org/b/isbn/<?php echo $product['isbn']; ?>-M.jpg" alt="Book Cover">
                                    <?php else:?>
                                        <img src="<?php echo $product['image'];?>" alt ="supply image"
                                    <?php endif ?>
                                </td>
                                <td class="titlecell">
                                    <p>Author:<?php echo $product['author'];?></p>
                                    <p>ISBN:<?php echo $product['isbn'];?></p>
                                    <p>$<?php echo $product['price'];?></p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="CartItemsSumTable">
                    <table>
                        <thead>
                            <tr>
                                <th colspan ="2">Cart Items Sum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($products as $product): ?>
                            <tr>                            
                                <td class="titlecell"> 
                                    <?php if($product['category'] == 'book'):?>
                                    <p>ISBN:<?php echo $product ['isbn'];?></p> 
                                    <?php else:?>
                                    <p><?php echo $product['title']; ?></p>
                                    <?php endif ?>
                                </td>
                                <td><p>$<?php echo $product['price'];?></p></td>
                            </tr>
                            <?php endforeach?>
                            <tr>                            
                                <td class="titlecell">                                
                                    <p>Total:</p>                                
                                </td>
                                <td><p>$<?php echo $row['total_price']; ?></p></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="CheckoutButton" onclick ="window.location.href = 'checkout.php'">
                             Check Out       
                    </button>
                </div>     
            </div>            
        </main>
        
        
    </body>
</html>
