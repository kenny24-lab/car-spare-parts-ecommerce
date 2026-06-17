<?php

require_once "config/init.php";

requireCustomer();

$orderId =
isset($_GET['id'])
? (int)$_GET['id']
: 0;

$userId =
$_SESSION['user_id'];

$stmt =
$conn->prepare("
SELECT *
FROM orders
WHERE id=?
AND user_id=?
");

$stmt->bind_param(
"ii",
$orderId,
$userId
);

$stmt->execute();

$order =
$stmt->get_result()->fetch_assoc();

if(!$order){

die("Invalid Order");
}

$stmt =
$conn->prepare("
SELECT *
FROM order_items
WHERE order_id=?
");

$stmt->bind_param(
"i",
$orderId
);

$stmt->execute();

$items =
$stmt->get_result();

while($item = $items->fetch_assoc()){

    $productStmt =
    $conn->prepare("
    SELECT main_image
    FROM products
    WHERE id=?
    ");

    $productStmt->bind_param(
    "i",
    $item['product_id']
    );

    $productStmt->execute();

    $product =
    $productStmt->get_result()->fetch_assoc();

    $_SESSION['cart'][$item['product_id']] = [

        'id'       => $item['product_id'],
        'name'     => $item['product_name'],
        'price'    => $item['price'],
        'quantity' => $item['quantity'],
        'image'    => $product['main_image'] ?? ''

    ];
}

header("Location: cart.php");
exit;