<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';

$objDb = new DbConnect;
$conn = $objDb -> connect();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    $stmt = $conn->prepare("SELECT * FROM dvd");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
    
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
try {
    $stmt = $conn->prepare("SELECT * FROM books");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
    
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    }

?>
