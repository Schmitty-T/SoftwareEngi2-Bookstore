<?php
session_start();
header("Content-Type: application/json");

try {
    $db = new PDO("sqlite:users.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $userName = trim($_POST["username"]);
        $password = $_POST["password"];

        if (empty($userName) || empty($password)) {
            echo json_encode(["status" => "error","message" => "One (or more) fields are empty"]);
            exit();
        }

        $dbQueryStatement = $db->prepare("SELECT * FROM users WHERE username = ?");
        $dbQueryStatement->execute([$userName]);
        $user = $dbQueryStatement->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["username"] = $userName;
            echo json_encode(["status" => "success","username" => $userName]);
            exit();
        } else {
            echo json_encode(["status" => "error","message" => "Invalid username or password"]);
            exit();
        }
    }

    echo json_encode(["status" => "error","message" => "Invalid request"]);

} catch (Exception $errr) {
    echo json_encode(["status" => "error","message" => "Unknown error occurred"]);
}
exit();
?>