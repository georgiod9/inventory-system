<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';


$GLOBALS['attribute_type_id'] = array(
    "weight",
    "size",
    "height",
    "width",
    "length"
);

class Product {
    public $sku;
    public $name;
    public $price;
    public $type;

    public $id;

    function __construct($sku, $name, $price, $type){
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }

    function set_sku($sku){
        $this->sku = $sku;
    }

    function get_sku() {
        return $this->sku;
    }

    function set_name($name){
        $this->name = $name;
    }

    function get_name() {
        return $this->name;
    }

    function set_price($price){
        $this->price = $price;
    }
    
    function get_price(){
        return $this->price;
    }

    function set_type($type){
        $this->type = $type;
    }

    function get_type(){
        return $this->type;
    }
    
}

class Dvd extends Product {
    public $size;

    public function __construct($product, $size){
        $this->sku = $product->sku;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->type = $product->type;
        $this->size = $size;
    }

    public function set_size($size){
        $this->size = $size;
    }
}

class Furniture extends Product{
    public $height;
    public $width;
    public $length;

    public function __construct($height, $width, $length){
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    function set_height($height){
        $this->height = $height;
    }

    function get_height(){
        return $this->height;
    }

    function set_width($width){
        $this->width = $width;
    }

    function get_width(){
        return $this->width;
    }

    function set_length($length){
        $this->length = $length;
    }

    function get_length(){
        return $this->length;
    }
}

class Book extends Product{
    public $weight;
    public function __construct($weight){
        $this->weight = $weight;
    }

    public function set_weight($weight){
        $this->weight = $weight;
    }

    public function get_weight(){
        return $this->weight;
    }
}

class ProductAttribute {
    public $typeString;
    public $typeId;
    public $product_id;
    public $value;

    function setTypeId(){
        $this->typeId = getTypeId($this->typeString);
    }

    public function __construct($itemObj){
        $this->typeId = getTypeId($itemObj->type);
        echo "TYPE is: " . getTypeId($itemObj->type);
        $this->typeString = $itemObj->type;
        $this->value = $itemObj->values;
    }

}

class ItemObj extends Product{
    public $values = array();
    public $attributeNameList = array();

    public function __construct($msg, $attrArray, $attrNameList){
        $this->type = $msg->type;
        $this->sku = $msg->sku;
        $this->name = $msg->name;
        $this->price = $msg->price;
        
        foreach ($attrArray as $key => $value) {
            array_push($this->values, $value);
        }

        foreach ($attrNameList as $key => $value) {
            array_push($this->attributeNameList, $value);
        }
        #array_push($this->values, $attrArray);

        echo "Created ItemObject: " . $this->sku . " " . $this->name . " " . $this->price . " " . json_encode($this->values)  . PHP_EOL;
        echo "The item has the following attributes: " . json_encode($this->attributeNameList) . PHP_EOL;
    }

}


function getTypeId($string){
    
    foreach ($GLOBALS['attribute_type_id'] as $key => $value) {
       # if(!strcmp($value, $string)){
            echo "KEY is: " . $key . " Value is: " . $value . PHP_EOL;
           # return $key;
       # }
    }
}

function writeDvdToDb($dvd){
    #$product = createProduct($dvd);
    #$product_attribute = new ProductAttribute($dvd);
    $arr = array(
        "150",
        "200",
        "300"
    );
    #$iObj = new ItemObj($dvd->get_type(), $arr);
}


function matchType($message){
    $product = new Product($message->sku, $message->name, $message->price, $message->type);

    switch ($message->type) {
        case 'form_dvd':
            echo "2.Trying to set dvd size to: " . $message->size . PHP_EOL . PHP_EOL;

            $dvd = new Dvd($product, $message->size);
            echo  "3.A DVD has been registered as: " . json_encode($dvd) . PHP_EOL . PHP_EOL;
            writeDvdToDb($dvd);
            break;
        
        case 'form_furniture':
            #fillFurniture($product);
            break;

        case 'form_book':
            #fillBook($product);
            break;
        default:
            # code...
            break;
    }

    return $product;
}

function handlePostRequest($msg, $iObj, $conn){
    #Write product in product table (product_id, sku, name, price)
    $sql = "INSERT INTO `product`(`product_id`, `sku`, `name`, `price`) VALUES (null, :sku, :name, :price)";

    echo "**To Write: " . json_encode($iObj) . PHP_EOL;
    echo "Keys are: " . array_keys( $msg).PHP_EOL;
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':sku', $iObj->sku);
    $stmt->bindParam(':name', $iObj->name);
    $stmt->bindParam(':price', $iObj->price);

    if($stmt->execute()) {
        $response = ['status' => 1, 'message' => 'Product added successfully! '];
    } else {
        $response = ['status' => 0, 'message' => 'Failed to add product. '];
    }
    echo json_encode($response) . PHP_EOL;
    echo "Wrote to product table: " . json_encode($iObj) . PHP_EOL;

    $prod_attribute = new ProductAttribute($iObj);

    $sql = "INSERT INTO `product_attribute`(`product_attribute_id`, `attribute_type_id`, `product_id`, `value`) 
            VALUES (null, :typeId, :product_id, :value)";
    
    echo "Binding Params using: " . json_encode($prod_attribute) . PHP_EOL;
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':typeId', $prod_attribute->typeId);
    $stmt->bindParam(':product_id', getProductId($iObj, $conn));

    
}

function getProductId($itemObj, $conn){
    $sql = "SELECT * FROM `product` WHERE product_id = (SELECT COUNT(product_id) FROM product);";
    $stmt = $conn->prepare($sql);

    if($stmt->execute()) {
        $response = ['status' => 1, 'message' => 'Product added successfully! '];
    } else {
        $response = ['status' => 0, 'message' => 'Failed to add product. '];
    }
    echo json_encode($response) . PHP_EOL;
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Fetched from products, ID: " + $result . PHP_EOL;
    return $result;

}

$objDb = new DbConnect;
$conn = $objDb -> connect();


$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {
    case "GET":
        echo "Requesting GET method." . PHP_EOL;
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
        echo "Requesting POST Method..." . PHP_EOL;
        
        $message = json_decode( file_get_contents('php://input') );
        $product = $message[0];
        echo "Message received from frontend: " . json_encode($message) . PHP_EOL;
        echo "Product is as: " . json_encode($product) . PHP_EOL;

        $attributes = $message[1];
        echo "With attributes: " . json_encode($attributes) . PHP_EOL;

        $attributeNameList = $message[2];
        echo "With attribute names: " . json_encode($attributeNameList) . PHP_EOL . PHP_EOL;
        

        $itemObj = new ItemObj($product, $attributes, $attributeNameList);
        #handlePostRequest($message, $itemObj, $conn);
        echo "1.Received product with following inputs: " . json_encode($product) . PHP_EOL . PHP_EOL;
        #$item = new Product($product->sku, $product->name, $product->price, $product->type);
        #echo "Product objected created as: " . json_encode($item) . PHP_EOL . PHP_EOL;

        echo "6.Match type resulting object: " . json_encode(matchType($product)) . PHP_EOL . PHP_EOL;

        echo "SKU: " . $product->sku . PHP_EOL;
        echo "Name: " . $product->name . PHP_EOL;
        echo "Price: " . $product->price . PHP_EOL;
        #echo "Size: " . $product->size . PHP_EOL;

        #$sql = "INSERT INTO dvd(id, sku, Name, Price, Size) VALUES(null, :sku, :name, :price, :size)";
        #$stmt = $conn->prepare($sql);
        #$stmt->bindParam(':sku', $message->sku);
        #$stmt->bindParam(':name', $message->name);
        #$stmt->bindParam(':price', $message->price);
        #$stmt->bindParam(':size', $message->size);

        #if($stmt->execute()) {
        #    $response = ['status' => 1, 'mesage' => 'Product added successfully!'];
        #} else {
        #    $response = ['status' => 0, 'message' => 'Failed to add product.'];
        #}#
        #echo json_encode($response);
        break;
    
    default:
        echo "No request received." . PHP_EOL;  
        break;
}
