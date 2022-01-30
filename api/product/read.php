<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/product.php';
include_once '../user/authenticate.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$auth = new Authenticate();
if ($auth->keyOrException instanceof Exception) return;

if (isset($_GET['id'])) {
    $product->id = $_GET['id'];
    $stmt = $product->readOne();
    if ($product->name != null) {
        $product_arr = array(
            "id" =>  $product->id,
            "name" => $product->name,
            "description" => $product->description,
            "price" => $product->price,
            "category_id" => $product->category_id,
            "category_name" => $product->category_name
        );
        http_response_code(200);
        echo json_encode($product_arr);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Product with id ".$product->id." does not exist."));
    }
} else {
    $stmt = $product->read();
    $num = $stmt->rowCount();
    if ($num > 0) {
        $products_arr = array();
        $products_arr["records"] = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $product_item = array(
                "id" => $id,
                "name" => $name,
                "description" => html_entity_decode($description),
                "price" => $price,
                "category_id" => $category_id,
                "category_name" => $category_name
            );
            array_push($products_arr["records"], $product_item);
        }
        http_response_code(200);
        echo json_encode($products_arr);
    } else {
        http_response_code(404);
        echo json_encode(
            array("message" => "No products found.")
        );
    }
}
