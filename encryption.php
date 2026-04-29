 


<?php
$db = new PDO("sqlite:bookstore.db");

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

$key = hex2bin("5b3b99abd78f5972984cf9d5fbf2049d945f715838eb34ac8be95f735fa2ce15");

$stmt = $db->query("SELECT id, card_number, expiration_date, ccv FROM CreditCards");

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $encrypted_card = encryptData($row['card_number'], $key);
    $encrypted_exp = encryptData($row['expiration_date'], $key);
    $encrypted_ccv = encryptData($row['ccv'], $key);

    $update = $db->prepare("
        UPDATE CreditCards
        SET encrypted_card_number = :card,
            encrypted_expiration_date = :exp,
            encrypted_ccv = :ccv
        WHERE id = :id
    ");

    $update->bindValue(':card', $encrypted_card, PDO::PARAM_STR);
    $update->bindValue(':exp', $encrypted_exp, PDO::PARAM_STR);
    $update->bindValue(':ccv', $encrypted_ccv, PDO::PARAM_STR);
    $update->bindValue(':id', $row['id'], PDO::PARAM_INT);

    $update->execute();
}

echo "Migration complete.";
?>