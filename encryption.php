<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new PDO("sqlite:bookstore.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/*
|--------------------------------------------------------------------------
| AES-256-GCM Encryption Functions
|--------------------------------------------------------------------------
*/
function encryptData($plaintext, $key) {
    $cipher = "aes-256-gcm";

    $iv = random_bytes(openssl_cipher_iv_length($cipher));
    $tag = "";

    $ciphertext = openssl_encrypt(
        $plaintext,
        $cipher,
        $key,
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );

    return base64_encode($iv . $tag . $ciphertext);
}

function decryptData($encryptedData, $key) {
    $cipher = "aes-256-gcm";

    $data = base64_decode($encryptedData);

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

/*
|--------------------------------------------------------------------------
| Secure Key
|--------------------------------------------------------------------------
| Generate once with:
| openssl rand -hex 32
|--------------------------------------------------------------------------
*/
$key = hex2bin("5b3b99abd78f5972984cf9d5fbf2049d945f715838eb34ac8be95f735fa2ce15");

/*
|--------------------------------------------------------------------------
| Test Card Data (Learning Only)
|--------------------------------------------------------------------------
*/
$card_number = "1234123412341234";
$expiration_date = "12/29";
$ccv = "123";

/*
|--------------------------------------------------------------------------
| Encrypt Data
|--------------------------------------------------------------------------
*/
$encrypted_card = encryptData($card_number, $key);
$encrypted_exp = encryptData($expiration_date, $key);
$encrypted_ccv = encryptData($ccv, $key);


/*
|--------------------------------------------------------------------------
| Insert Into Database
|--------------------------------------------------------------------------
| Make sure your CreditCards table includes:
| id INTEGER PRIMARY KEY
| card_number TEXT
| expiration_date TEXT
| ccv TEXT
| last4 TEXT
|--------------------------------------------------------------------------
*/
$stmt = $db->prepare("
    INSERT INTO CreditCards (
        card_number,
        exp_date,
        ccv
       
    ) VALUES (
        :card_number,
        :expiration_date,
        :ccv        
    )
");

$stmt->bindValue(':card_number', $encrypted_card, PDO::PARAM_STR);
$stmt->bindValue(':expiration_date', $encrypted_exp, PDO::PARAM_STR);
$stmt->bindValue(':ccv', $encrypted_ccv, PDO::PARAM_STR);

$stmt->execute();

echo "Encrypted test card inserted successfully.<br>";

/*
|--------------------------------------------------------------------------
| Optional Verification
|--------------------------------------------------------------------------
*/
$check = $db->query("SELECT * FROM CreditCards ORDER BY id DESC LIMIT 1");
$row = $check->fetch(PDO::FETCH_ASSOC);

echo "Decrypted Card: " . decryptData($row['card_number'], $key) . "<br>";
echo "Decrypted Expiration: " . decryptData($row['exp_date'], $key) . "<br>";
echo "Decrypted CCV: " . decryptData($row['ccv'], $key) . "<br>";
?>