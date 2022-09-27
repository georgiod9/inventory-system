<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: *");

include 'DbConnect.php';
#echo "STARTING..." . PHP_EOL;


$GLOBALS['attribute_type_id'] = array(
    "weight",
    "size",
    "height",
    "width",
    "length"
);

class Product
{
    public $sku;
    public $name;
    public $price;


    public $id;

    function __construct($sku, $name, $price)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    function set_sku($sku)
    {
        $this->sku = $sku;
    }

    function get_sku()
    {
        return $this->sku;
    }

    function set_name($name)
    {
        $this->name = $name;
    }

    function get_name()
    {
        return $this->name;
    }

    function set_price($price)
    {
        $this->price = $price;
    }

    function get_price()
    {
        return $this->price;
    }

    function set_id($id){
        $this->id = $id;
    }

    function get_id(){
        return $this->id;
    }
}

class ProductAttribute
{
    public $sku;
    public $name;
    public $price;

    public function __construct($attributeArray)
    {
        $this->sku = $attributeArray['sku'];
        $this->name = $attributeArray['name'];
        $this->price = $attributeArray['price'];
    }
}

class ItemObj extends Product
{
    public $values = array();
    public $attributeNameList = array();
    public $attr = array();

    public function __construct($msg)
    {
        $this->sku = $msg->sku;
        $this->name = $msg->name;
        $this->price = $msg->price;
       
    }

    public function constructItemModel($msg, $attrArray, $attrNameList)
    {
        $this->sku = $msg->sku;
        $this->name = $msg->name;
        $this->price = $msg->price;

        #foreach($attrArray as $key => $value) {
        #$str = $attrNameList[$key] . ": " . $value;
        #array_push($this->attr, $str);
        #echo "*****Fetched object with attributes: " . json_encode($this->attr) . PHP_EOL;
        #}

        foreach ($attrArray as $key => $value) {
            array_push($this->values, $value);
        }

        foreach ($attrNameList as $key => $value) {
            array_push($this->attributeNameList, $value);
        }
        #array_push($this->values, $attrArray);

        #echo "Created ItemObject: " . $this->sku . " " . $this->name . " " . $this->price . " " . json_encode($this->values)  . PHP_EOL;
        #echo "The item has the following attributes: " . json_encode($this->attributeNameList) . PHP_EOL . PHP_EOL;
    }

    public function setAttributes($attrArray)
    {
        sort($attrArray);
        foreach ($attrArray as $key => $value) {
            array_push($this->attr, $value);
        }
        #echo "*****Fetched object with attributes: " . json_encode($this->attr) . PHP_EOL;
    }
}



function getTypeId($string)
{
    echo "String to match is: " . $string . PHP_EOL;
    foreach ($GLOBALS['attribute_type_id'] as $key => $value) {
        # if(!strcmp($value, $string)){
        #echo "KEY is: " . $key . " Value is: " . $value . PHP_EOL;
        # return $key;
        # }
    }
}

function insertProduct($itemObject, $conn)
{
    try {
        $sql = "INSERT INTO `product`(`product_id`, `sku`, `name`, `price`) VALUES (null, :sku, :name, :price);";

        $stmt = $conn->prepare($sql);
        #$conn->beginTransaction();

        $stmt->bindParam(':sku', $itemObject->sku);
        $stmt->bindParam(':name', $itemObject->name);
        $stmt->bindParam(':price', $itemObject->price);


        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Product added successfully! '];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to add product. '];
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $product_id = $conn->lastInsertId();

        echo json_encode($response) . PHP_EOL;
        echo "new method: " . json_encode($result) . PHP_EOL;

        #$stmt = $conn->prepare($sql2);
        #$conn->beginTransaction();
        #$result = $conn->commit();


        echo "resulting: " . json_encode($product_id) . PHP_EOL;

        return $product_id;

    } catch (\Throwable $e) {
        #$conn->rollback();
        throw $e;
    }
}

function handlePostRequest($msg, $iObj, $conn)
{
    echo "2. HANDLE POST REQUEST" . PHP_EOL;

    echo "2a). To Write Product with following details: " . $iObj->sku . " " . $iObj->name . " " . $iObj->price . PHP_EOL;

    #insert product into db table
    #insertProduct function returns the product id, i.e. primary key
    $product_id = insertProduct($iObj, $conn);




    #Write product in product table (product_id, sku, name, price)

    #echo "2a). To Write Product with following details: " . $iObj->sku . " " . $iObj->name . " " . $iObj->price . PHP_EOL;


    #$stmt = $conn->prepare($sql);
    #$stmt->bindParam(':sku', $iObj->sku);
    #$stmt->bindParam(':name', $iObj->name);
    #$stmt->bindParam(':price', $iObj->price);

    #if($stmt->execute()) {
    #    $response = ['status' => 1, 'message' => 'Product added successfully! '];
    #} else {
    #    $response = ['status' => 0, 'message' => 'Failed to add product. '];
    #}


    #echo json_encode($response) . PHP_EOL;
    #$result = $stmt->fetch(PDO::FETCH_ASSOC);
    #echo "New id is here:--->" . json_encode($result) . PHP_EOL . PHP_EOL;
    #echo "Wrote to product table: " . json_encode($iObj) . PHP_EOL . PHP_EOL;

    #$product_id = getProductId($iObj, $conn);

    echo "2b). To Write item attributes to product_attribute table: " . PHP_EOL;

    #echo "     Where product id is: " . $product_id . PHP_EOL . PHP_EOL;


    $attributesNameList = $iObj->attributeNameList;
    $attributeCount = count($iObj->attributeNameList);

    $requiredAttributesId = array();


    #Get the needed attribute_type_id from the database to use them as FK
    for ($i = 0; $i < $attributeCount; $i++) {
        $sql = "SELECT attribute_type_id
            FROM `product_attribute_type`
            WHERE attribute_type_name = :target_attribute";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':target_attribute', $attributesNameList[$i]);
        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Product added successfully! '];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to add product. '];
        }

        $requiredAttributesId[$i] = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Matched with database attribute type: " . json_encode($requiredAttributesId[$i]) . PHP_EOL;
    }

    #extract attributes names list ID to an array
    $fetchAttributes = array();
    foreach ($requiredAttributesId as $key => $value) {
        $fetchAttributes[$key] = $value['attribute_type_id'];
    }

    echo PHP_EOL . 'Final attribute list looks like: ' . json_encode($fetchAttributes) . PHP_EOL . PHP_EOL;

    #execute INSERT sql command as many times as there are attributes
    foreach ($iObj->values as $key => $value) {
        $sql =
            "INSERT INTO `product_attribute`(`product_attribute_id`, `attribute_type_id`, `product_id`, `value`)
        VALUES (null, :attribute_type_id, :product_id, :value)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':attribute_type_id', $fetchAttributes[$key]);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':value', $iObj->values[$key]);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Product added successfully! '];
            echo "Successfully added attribute_type_id " . $fetchAttributes[$key] . " at " . $product_id . PHP_EOL;
        } else {
            $response = ['status' => 0, 'message' => 'Failed to add product. '];
        }
    }
}

function handleGetRequest($iObj, $conn)
{
}

function getProductId($itemObj, $conn)
{
    $sql = "SELECT * FROM `product` WHERE product_id = (SELECT COUNT(product_id) FROM product);";
    #$sql = "SELECT * FROM `product` WHERE product_id = (SELECT COUNT(product_id) FROM product);";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        $response = ['status' => 1, 'message' => 'Product added successfully! '];
    } else {
        $response = ['status' => 0, 'message' => 'Failed to add product. '];
    }
    echo json_encode($response) . PHP_EOL;
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Search result returned: " . json_encode($result) . PHP_EOL;
    $prod_id = (int) $result["product_id"];
    echo "Fetched from products, ID: " . $prod_id . PHP_EOL;
    return $prod_id;
}

$objDb = new DbConnect;
$conn = $objDb->connect();


$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {
    case "GET":
        #echo "Requesting GET method." . PHP_EOL;
        $sql =
            "SELECT product.product_id, product.name, product.sku, product.price, product_attribute.value, product_attribute_type.attribute_type_name
        FROM product
        INNER JOIN product_attribute ON product.product_id = product_attribute.product_id
        INNER JOIN product_attribute_type ON product_attribute_type.attribute_type_id = product_attribute.attribute_type_id
        ORDER BY `product`.`product_id` ASC;";

        $path = explode('/', $_SERVER['REQUEST_URI']);
        if (isset($path[3]) && is_numeric($path[3])) {
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
        #echo json_encode($products);

        #create array filled with item objects, each having attributes: (sku, name, price, attributeList=null)
        $fetchedItems = array();
        #echo "LEngth: " . count($products) . PHP_EOL;
        foreach ($products as $key => $value) {
            #echo "Product: " . json_encode($value) . PHP_EOL;
            $item = new ProductAttribute($value);
            #echo "Created item object: " . json_encode($item) . PHP_EOL;

            $fetchedItems[$key] = new ItemObj($item);
            $fetchedItems[$key]->set_id($value['product_id']);
            #echo "Product id: " . json_encode($value['product_id']) . PHP_EOL;
            #echo "Pushed Object to fetchedItems array: " . json_encode($fetchedItems[$key]) . PHP_EOL;
        }
        #echo "Item Object Array: " . json_encode($fetchedItems) . PHP_EOL;


        #Since the database writes each attribute (h, w, l) on a separate row to allow dynamic product types
        #Loop through item array and combine attributes like H, W, L into one array attributeList = [Height: h, width: w, length: l]

        $attributes = array();
        $attributesNameList = array();
        $streak = 0;
        $reset = false;
        $itemAttributes = array();
        $itemsObjArray = array();

        foreach ($products as $key => $value) {
            $previousId = -1;
            #echo "item: " . json_encode($value) . PHP_EOL;
            $currentId = $value['product_id'];
            #echo "current = " . $value['product_id'] ;

            if ($key != count($products) - 1) {
                $nextId = $products[$key + 1]['product_id'];
                #echo "previous = " . $previousId . " current = " . $value['product_id'] . "  next = " . $products[$key + 1]['product_id'] . " streak = " . $streak . PHP_EOL .PHP_EOL;
            }

            array_push($attributes, $value['value']);
            array_push($attributesNameList, $value['attribute_type_name']);

            $str = $value['attribute_type_name'] . ": " . $value['value'];
            array_push($itemAttributes, $str);
            #echo "COUONT: " . count($products) . " Key: " . $key . PHP_EOL;

            if ($key != count($products)) {
                if ($currentId != $nextId) {
                    $reset = true;
                } else {
                    $previousId = $products[$key - 1]['value'];
                    $reset = false;
                    $streak++;
                    echo PHP_EOL;
                    if ($key == count($products) - 1) {
                        $reset = true;
                    }
                }
            }

            #echo "attributes->  " . json_encode($attributesNameList) . " " . json_encode($attributes) . PHP_EOL; 
            if ($reset) {
                $fetchedItems[$key]->setAttributes($itemAttributes);
                array_push($itemsObjArray, $fetchedItems[$key]);
                #echo "PUSHED: " . json_encode($fetchedItems[$key]) . PHP_EOL;
                $streak = 0;
                $attributes = array();
                $attributesNameList = array();
                $str = "";
                $itemAttributes = array();

                #echo "*************************************************" . PHP_EOL;
            }
        }


        #echo "Final item object array looks like: " . PHP_EOL;
        #foreach ($itemsObjArray as $key => $value) {
        #echo json_encode($itemsObjArray[$key]) . PHP_EOL;    
        #}
        echo json_encode($itemsObjArray);

        #handleGetRequest($itemObj, $conn);
        break;

    case "POST":
        echo "Requesting POST Method......" . PHP_EOL . PHP_EOL;

        echo "1. RECEIVE DATA FROM SERVER: " . PHP_EOL;

        #Parse REST data.
        $message = json_decode(file_get_contents('php://input'));
        $product = $message[0];
        echo "Product is as: " . json_encode($product) . PHP_EOL;

        $attributes = $message[1];
        echo "With attributes: " . json_encode($attributes) . PHP_EOL;

        $attributeNameList = $message[2];
        echo "With attribute names: " . json_encode($attributeNameList) . PHP_EOL . PHP_EOL;

        echo "2. CREATE ITEM OBJECT" . PHP_EOL;
        #Construct item object from data received in POST
        #$itemObj = new ItemObj($product, $attributes, $attributeNameList);
        $itemObj = new ItemObj($product);
        $itemObj->constructItemModel($product, $attributes, $attributeNameList);


        #Handle the POST request
        handlePostRequest($message, $itemObj, $conn);


        echo "1.Received product with following inputs: " . json_encode($product) . PHP_EOL . PHP_EOL;
        #$item = new Product($product->sku, $product->name, $product->price, $product->type);
        #echo "Product objected created as: " . json_encode($item) . PHP_EOL . PHP_EOL;

        #echo "6.Match type resulting object: " . json_encode(matchType($product)) . PHP_EOL . PHP_EOL;

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
