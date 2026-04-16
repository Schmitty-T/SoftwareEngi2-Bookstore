<?php
// Connect to database
try {
    $db = new SQLite3('employees.db');
} catch (Exception $e) {
    die("Database connection failed");
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="author" content="Seth Dyer">
      <link rel="stylesheet" href="AdminHomePage.css">
      <title>AdminHomePage</title>
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
                <button><a href="login.php">Log Out</a></button>
            </div>
        </div>
    </header>

    <main>
    <h1> Admin Home Page || Welcome Johnathan</h1>
    <div class="tableRow">
        <div class="Orders">
            <table>
                <caption>Store Orders</caption>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="orderBody">

                    <?php
                    $results = $db->query("SELECT OrderID, OrderDate, Status FROM Orders");

                    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['OrderID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['OrderDate']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                        echo "</tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>

        <div class="Shipments">
            <table>
                <caption>Vendor Shipments</caption>
                <thead>
                    <tr>
                        <th>Shipment ID</th>
                        <th>Ship Date</th>
                        <th>Vendor</th>
                    </tr>
                </thead>
                <tbody id="ShipmentsBody">

                    <?php
                    $results = $db->query("SELECT ShipmentID, ShipDate, Vendor FROM Shipments");

                    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['ShipmentID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ShipDate']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Vendor']) . "</td>";
                        echo "</tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>

    </div>
    </main>
    <a href="AdminEmployeePage.php" class="EmpInfoButton">Employee Info</a>
  </body>
</html>