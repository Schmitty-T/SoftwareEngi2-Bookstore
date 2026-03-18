<?php
session_start();
header("Content-Type: application/json");
$db = null;
try{

try {
    //for database users connection
    $db = new PDO("sqlite:users.db");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $errorConn) {
    echo json_encode(["status"=>"error","message"=>"Cant connect to the db!"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //formats the received info for the users database
    $userName = trim($_POST["username"]);
    $email = strtolower(trim($_POST["email"]));
    $password = $_POST["password"];
    //$confirmPassword = "pass";

    //if ($password != $confirmPassword) {
    //    echo json_encode(["status" => "error","message" => "Passwords dont match,"]);
    //    exit();
    //}
    if (empty($userName) || empty($email) || empty($password)) {
        echo json_encode(["status" => "error","message" => "One (or more) fields are empty"]);
        exit();
    }else{}

    try {
        //if it can, i twill insert the new user info into db
        $dbQueryStatement = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $dbQueryStatement -> execute([$userName, $email, password_hash($password, PASSWORD_DEFAULT)]);

        $_SESSION["username"] = $userName;

        echo json_encode(["status" => "success","username" => $userName]);
        exit();

    } catch(PDOException $alreadyExists) {
        //if user is in the db alreadyy
        echo json_encode(["status" => "error","message" => "Username or email already exists!"]);
        echo json_encode(["status" => "error","message" => "Database errors"]);
        exit();
    }
}

echo json_encode(["status" => "error","message" => "Invalid request"]);
} catch (Exception $errr){
    //if theres unknown error occuring
    echo json_encode(["status" => "error","mesage" => "Unknown error occured"]);
    echo json_encode();
}
exit();
?>