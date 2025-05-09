<?php 
require "../includes/connection.php";
$query = "SELECT * FROM annonce";
$stmt = $conn->prepare($query);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($data);
?>