<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';
echo "Starting...";


$objDb = new DbConnect;
$conn = $objDb -> connect();


$method = $_SERVER['REQUEST_METHOD'];



switch ($method) {
    case "GET":
        echo "Requesting GET method.";
        $sql = "SELECT * FROM books";
        $path = explode('/', $_SERVER['REQUEST_URI']);
        if(isset($path[3]) && is_numeric($path[3])) {
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $products = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($products);
        break;

    case "POST":
        $product = json_decode( file_get_contents('php://input') );
        echo "SKU: " . $product->sku . "</br>"; 
        echo "Name: " . $product->name . " "; 
        echo "Price: " . $product->price . " ";
        echo "Size: " . $product->size . " ";

        $sql = "INSERT INTO dvd(id, sku, Name, Price, Size) VALUES(null, :sku, :name, :price, :size)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':sku', $product->sku);
        $stmt->bindParam(':name', $product->name);
        $stmt->bindParam(':price', $product->price);
        $stmt->bindParam(':size', $product->size);

        if($stmt->execute()) {
            $response = ['status' => 1, 'mesage' => 'Product added successfully!'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to add product.'];
        }
        echo json_encode($response);
        break;
    
    default:
        echo "No request received.";
        break;
}




















?>
