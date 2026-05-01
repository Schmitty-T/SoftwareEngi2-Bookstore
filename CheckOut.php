<!DOCTYPE html>
<?php
        date_default_timezone_set('America/Chicago');
        $db = new PDO("sqlite:bookstore.db");
        $category = $_GET['category'] ?? null;
        $username = $_GET['username'] ?? 'Guest';  

        $stmt = $db->query("SELECT * 
                FROM OrderItems
                INNER JOIN Products ON OrderItems.productId = Products.productId;");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $priceQuery = $db->query("SELECT SUM(Products.price) AS total_price FROM OrderItems
                INNER JOIN Products ON OrderItems.productId = Products.productId");
        $row = $priceQuery->fetch(PDO::FETCH_ASSOC);
        $total = $row['total_price'] ?? 0;

        $stmt = $db-> prepare("SELECT userId FROM Users WHERE username = :username");
        $stmt -> execute([':username' => $username]);
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);
               
                
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $card_number = str_replace('-','',$_POST['card_number']);
            $exp_date = $_POST['exp_date'];
            $ccv = $_POST['ccv'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $zip_code = $_POST['zip_code'];
            $street_address = $_POST['street_address'];
            $state = $_POST['state'];
            $city = $_POST['city'];
            
            
            $key = hex2bin('5b3b99abd78f5972984cf9d5fbf2049d945f715838eb34ac8be95f735fa2ce15');
            $stmt = $db->query("SELECT * FROM CreditCards");            

            $valid = false;

            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                $card = decryptData($row['card_number'], $key);
                $exp = decryptData($row['exp_date'], $key);
                $ccv_check = decryptData($row['ccv'], $key);

                $card = str_replace('-', '',$card);

                if($card === $card_number &&
                    $exp === $exp_date &&
                    $ccv_check === $ccv)
                    {
                        $valid = true;
                        break;
                    }
            }
            if($valid) {

                $ordNum = 'ON-' . date('Ymd') . '-' . mt_rand(1000,9999);

                $stmt = $db -> prepare("
                INSERT INTO Orders
                (userId, confNum, orderDate, total, status)
                VALUES
                (:user, :ordNum, :timeStamp, :total, :status)");

                $stmt -> execute([ 
                    ':user' => $user['userId'],
                    ':ordNum' => $ordNum,
                    ':timeStamp' => date('Y-m-d H:i:s'),
                    ':total' => $total,
                    ':status' => 'Ordered'
                ]);


                echo "<script>window.onload = function() {successPopup(
                      '$ordNum','categories.php?username=" . urlencode($username) . "'); };</script>";
            } else {
                echo"<script>window.onload = function() {errorPopup(); };</script>";
        }
        }
        function decryptData($encryption, $key) {
            $cipher = 'aes-256-gcm';

            $data = base64_decode($encryption);

            $ivLength = openssl_cipher_iv_length($cipher);
            $iv = substr($data, 0, $ivLength);
            $tag = substr($data, $ivLength, 16);
            $ciphertext = substr($data, $ivLength + 16);


            return openssl_decrypt(
                $ciphertext,
                $cipher,
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
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
            <form method = "POST" action = "checkout.php?username=<?php echo urlencode($username);?>">
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
                                        <input  id='expiration' type="text" maxlength='10' name='exp_date' placeholder="Expiration eg:11/28" required>                                
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
