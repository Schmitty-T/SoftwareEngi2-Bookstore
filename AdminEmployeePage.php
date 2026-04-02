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
    <h1 style="text-align:center; color:#0e4e8f;">Employee Information</h1>
</header>

<main>

<table id="EmployeeID">

    <caption>Employee List</caption>

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