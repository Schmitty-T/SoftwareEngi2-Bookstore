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
    <title>Employee Information</title>
    <link rel="stylesheet" href="AdminHomePage.css">
</head>

<body>

<header>
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
</header>

<main>

<table id="EmployeeID">

    <caption>Employee Info</caption>

    <thead>
        <tr>
            <th>Name</th>
            <th>Birthdate</th>
            <th>Position</th>
        </tr>
    </thead>

    <tbody>

    <?php
    $results = $db->query("SELECT EmployeeName, BirthDate, Position FROM Employee");

    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['EmployeeName']) . "</td>";
        echo "<td>" . htmlspecialchars($row['BirthDate']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Position']) . "</td>";
        echo "</tr>";
    }
    ?>

    </tbody>

</table>
</main>
    <a href="AdminHomePage.php" class="EmpInfoButton">Back</a>
</body>
</html>