<!DOCTYPE html>
<?php
    $db = new PDO("sqlite:bookstore.db");

    $category = $_GET['category'] ?? null;
    $search = $_GET['search'] ?? "";
    $username = $_GET['username'] ?? 'Guest';

    $sql = "SELECT * FROM Products WHERE 1=1";
    $params = [];

    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }

    if ($search != "") {
        $sql .= " AND (title LIKE ? OR author LIKE ? OR isbn LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name = "author" content="Yuni Lin">
    <meta name="viewport" content="wideth=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Categories.css">
    <title>Category</title>
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
                <li><a href="Categories.php?username=<?php echo urlencode($username); ?>" class="active">Categories</a></li>
                <li><a href="Cart.php?username=<?php echo urlencode($username); ?>">Shopping Cart</a></li>
                <li><a href="OrderHistory.php?username=<?php echo urlencode($username); ?>">Order History</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div id="categorySearch">
            <div id="categoryMenu">
                <a class="category <?php if(($category ?? '') == '') echo 'active'; ?>" 
                href="categories.php?search=<?php echo urlencode($search ?? ''); ?>&username=<?php echo urlencode($username); ?>">All</a>

                <a class="category <?php if(($category == 'book')) echo 'active'; ?>" 
                href="categories.php?category=book&search=<?php echo urlencode($search ?? ''); ?>&username=<?php echo urlencode($username); ?>">Books</a>
    
                <a class="category <?php if(($category == 'Supply')) echo 'active'; ?>" 
                href="categories.php?category=Supply&search=<?php echo urlencode($search ?? ''); ?>&username=<?php echo urlencode($username); ?>">Supplies</a>
            </div>
            <h3 style="color: #0e4e8f; font-size: 18px; padding-top: 30px; margin-top: 30px; text-align: center;">Search Products</h3>
            <br/>
            <form method="GET" action="categories.php">
                <div style="justify-content: center; display: flex;">
                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category ?? ''); ?>">
                    <input type="text" id="searchBar" name = "search" value="<?php echo htmlspecialchars($search); ?>"
                    placeholder="<?php 
                        if (($category ?? '') == 'book') echo 'Enter ISBN or author name here..'; 
                        elseif (($category ?? '') == 'Supply') echo 'Enter supply name here..';
                        else echo 'Enter name here..'; 
                    ?>"
                    style="width:500px; font-size:16px; margin-bottom: 30px; padding:10px;">
                </div>
            </form>
        </div>
    </main>

    <section>
        <h3>Product Results</h3>

        <div id="productResults">
            <?php foreach ($products as $product): ?>
            <article class="product">
                <?php if($product['category'] == 'book'): ?>
                    <img src="https://covers.openlibrary.org/b/isbn/<?php echo $product['isbn']; ?>-M.jpg" alt="Book cover">
                <?php else: ?>
                    <img src="<?php echo $product['image']; ?>" alt="supply image">
                <?php endif; ?>

                <div class= "productInfo" style="color: #0e4e8f;">
                    <p><?php echo $product['title']; ?></p>
                    <?php if($product['category'] == 'book'): ?>
                        <p>Author: <?php echo $product['author']; ?></p>
                        <p>ISBN: <?php echo $product['isbn']; ?></p>
                    <?php endif; ?>

                    <p>$<?php echo $product['price']; ?></p>
                    <div class="addCartContainer">
                        <a href="addToCart.php?productId=<?php echo $product['productId']; ?>
                        &username=<?php echo urlencode($username); ?>" class="addCartBtn">
                            Add to Cart
                        </a>
                    </div>
                </div>
            </article>
                <?php endforeach; ?>
        </div>
    </section>
    <script>
        const searchBar = document.getElementById("searchBar");

        searchBar.addEventListener("input" , function() {
            if (this.value.trim() === "") {
                this.form.submit();
            }
        });
        
        document.getElementById("searchBar").addEventListener("keypress", function(event){
            if (event.key === "Enter"){
                this.form.submit();
            }
        });
    </script>
</body>