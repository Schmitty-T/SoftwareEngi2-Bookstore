<?php
	
	$db = new PDO("sqlite:bookstore.db");

	if($_SERVER["REQUEST_METHOD"] === "POST") 
	{
		$productId = $_POST['productId'] ?? null;

		if($productId) 
		{
			$stmt = $db->prepare("DELETE FROM OrderItems WHERE productId = :productId");
			$stmt->bindValue(':productId', $productId, PDO:: PARAM_INT);
			$stmt->execute();

			echo json_encode(["success" => true]);
		}else 
		{
			echo json_encode(["success" => false]);
		}
	}
?>