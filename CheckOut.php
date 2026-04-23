<!DOCTYPE html>
<?php
        $db = new PDO("sqlite:bookstore.db");
        $category = $_GET['category'] ?? null;
        $username = $_GET['username'] ?? 'Guest';
        $stmt = $db->query("SELECT * FROM Products");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $priceQuery = $db->query("SELECT SUM(price) AS total_price FROM Products");
        $row = $priceQuery->fetch(PDO::FETCH_ASSOC);
        $total = 0;
        
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $card_number = $_POST['card_number'];
            $expiration_date = $_POST['expiration_date'];
            $ccv = $_POST['ccv'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $zip_code = $_POST['zip_code'];
            $street_address = $_POST['street_address'];
            $state = $_POST['state'];
            $city = $_POST['city'];

            

            $statement = $db->prepare(
                    "SELECT * 
                     FROM CreditCards
                     WHERE card_number = :card_number
                     AND ccv = :ccv
                     AND expiration_date = :expiration_date"
                    );

            $statement->bindValue(':card_number', $card_number,PDO::PARAM_STR );
            $statement->bindValue(':expiration_date', $expiration_date,PDO::PARAM_STR);
            $statement->bindValue(':ccv', $ccv, );
            //$statement->bindValue(':first_name', $first_name,PDO::PARAM_STR );
            //$statement->bindValue(':last_name', $last_name, PDO::PARAM_STR);
            //$statement->bindValue(':zip_code', $zip_code, PDO::PARAM_STR);
            //$statement->bindValue(':street_address', $street_address,PDO::PARAM_STR);
            //$statement->bindValue(':state', $state,PDO::PARAM_STR);
            //$statement->bindValue(':city', $city,PDO::PARAM_STR);

            $statement->execute();
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            if($row) {
                echo "<script>window.onload = function () {
                successPopup();};</script>";
            }else{
                echo "<script>
                window.onload = function() {
                    errorPopup();
                };    
                </script>;";
            }
        }
      
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
                <span>Hello, <?php echo htmlspecialchars($username); ?></span>
                <button><a href="Login.html">Log Out</a></button>
            </div>
        </div>
        <nav id="navBar">
            <ul>
                <li><a href="Homepage.html?username=<?php echo urlencode($username); ?>">Homepage</a></li>
                <li><a href="Categories.php?username=<?php echo urlencode($username); ?>"> Categories</a></li>
                <li><a href="cart.php?username=<?php echo urlencode($username); ?>">Shopping Cart</a></li>
                <li><a href="OrderHistory.php?username=<?php echo urlencode($username); ?>">Order History</a></li>
            </ul>
        </nav>
        </header>
        
        <main>
            <form method = "POST" action = "checkout.php">
            <div class="CartItemsContainer">                
                <table class="CartItemsTable">
                    <thead>
                        <tr>
                            <th colspan="3">Delivery Method</th>
                        </tr>
                    </thead>
                    <tbody>    
                        <tr>
                            <td colspan='3'>
                                <div class='autocomplete-container' id='autocomplete-container'></div>      
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"><div id="street"></div><div id="postcode"></div></td>
                        </tr>
                        <tr>
                            <td colspan="3"><div id="city"></div></td>
                        </tr>
                        <tr>
                            <td colspan="3"><div id="state"></div><div id="country"></div></td>
                        </tr>
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
                                    <td colspan="2">                                
                                        <input id="CardNumber" name='card_number' type="text" maxlength='19' placeholder="Card Number" required>                                
                                    </td>                                    
                                </tr>
                                <tr>                            
                                    <td colspan="2">                                
                                        <input  id='expiration' type="text" maxlength='10' name='expiration_date' placeholder="Expiration YY-MMM eg:29-Mar" required>                                
                                    </td>                                    
                                </tr>
                                <tr>                            
                                    <td colspan="2">                                
                                        <input id='Ccv'type="text" name='ccv' maxlength='3' placeholder="CCV" required>                                
                                    </td>                                    
                                </tr>
                                <tr>                            
                                    <td colspan="2">                                
                                        <input type="text" name='first_name' placeholder="First Name">                                
                                    </td>                                    
                                </tr>
                                <tr>                            
                                    <td colspan="2">                                
                                        <input type="text" name='last_name' placeholder="Last Name">                                
                                    </td>                                    
                                </tr>
                                <tr>                            
                                    <td colspan="2">                                
                                        <input type="text" name='street_address' placeholder="Street Address">                                
                                    </td>                                    
                                </tr>
                                <tr>                            
                                    <td colspan="2">                                
                                        <input type="text" name='zip_code' placeholder="ZIP Code">                                
                                    </td>                                    
                                </tr>
                                <tr>                            
                                    <td colspan="2">                                
                                        <input type="text" name='city' placeholder="City">                                
                                    </td>                                    
                                </tr>
                                <tr>                            
                                    <td colspan="2">                                
                                        <input type="text" name='state' placeholder="State">                                
                                    </td>                                    
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
