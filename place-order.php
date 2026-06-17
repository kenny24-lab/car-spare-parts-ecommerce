<?php

require_once "config/init.php";

mysqli_report(
    MYSQLI_REPORT_ERROR |
    MYSQLI_REPORT_STRICT
);

error_reporting(E_ALL);
ini_set('display_errors', 1);

requireCustomer();

if(empty($_SESSION['cart'])){
    header("Location: cart.php");
    exit;
}

$userId = $_SESSION['user_id'];

$customerName = trim($_POST['customer_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');
$paymentMethod = trim($_POST['payment_method'] ?? '');

if(
    empty($customerName) ||
    empty($phone) ||
    empty($address) ||
    empty($paymentMethod)
){
    die("Please complete all checkout fields.");
}



$cart = $_SESSION['cart'];

$total = 0;

foreach($cart as $item){
    $total += $item['price'] * $item['quantity'];
}

try{

    /*
    |--------------------------------------------------------------------------
    | Start Transaction
    |--------------------------------------------------------------------------
    */

    $conn->begin_transaction();

    /*
    |--------------------------------------------------------------------------
    | Validate Stock Before Order
    |--------------------------------------------------------------------------
    */

    foreach($cart as $item){

        $stockCheck = $conn->prepare("
            SELECT stock_quantity,name
            FROM products
            WHERE id=?
        ");

        $stockCheck->bind_param(
            "i",
            $item['id']
        );

        $stockCheck->execute();

        $product =
        $stockCheck
        ->get_result()
        ->fetch_assoc();

        if(!$product){

            throw new Exception(
                "Product not found."
            );
        }

        if(
            $product['stock_quantity']
            <
            $item['quantity']
        ){

            throw new Exception(
                $product['name']
                .
                " has insufficient stock."
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Create Order
    |--------------------------------------------------------------------------
    */

     $trackingNumber =
"TRK" .
date("Ymd") .
rand(1000,9999);

    $stmt = $conn->prepare("
        INSERT INTO orders
(
    user_id,
    total_amount,
    status,
    customer_name,
    phone,
    address,
    payment_method,
    tracking_number
)
        VALUES
(
?,
?,
'pending',
?,
?,
?,
?,
?
)
    ");

    $stmt->bind_param(
"idsssss",
$userId,
$total,
$customerName,
$phone,
$address,
$paymentMethod,
$trackingNumber
);

    if(!$stmt->execute()){
    throw new Exception(
        $stmt->error
    );
}

    $orderId = $conn->insert_id;

   


    /*
    |--------------------------------------------------------------------------
    | Save Order Items
    |--------------------------------------------------------------------------
    */

    foreach($cart as $item){

        $subtotal =
        $item['price']
        *
        $item['quantity'];

        $stmtItem = $conn->prepare("
            INSERT INTO order_items
            (
                order_id,
                product_id,
                product_name,
                quantity,
                price,
                subtotal
            )
            VALUES
            (
                ?,?,?,?,?,?
            )
        ");

        $stmtItem->bind_param(
    "iisidd",
    $orderId,
    $item['id'],
    $item['name'],
    $item['quantity'],
    $item['price'],
    $subtotal
);

        if(!$stmtItem->execute()){

    throw new Exception(
        "Order Item Error: "
        . $stmtItem->error
    );
}
        /*
        |--------------------------------------------------------------------------
        | Reduce Stock
        |--------------------------------------------------------------------------
        */

        /*
|--------------------------------------------------------------------------
| Get Current Stock
|--------------------------------------------------------------------------
*/

$getStock = $conn->prepare("
    SELECT stock_quantity
    FROM products
    WHERE id=?
");

$getStock->bind_param(
    "i",
    $item['id']
);

$getStock->execute();

$currentStock =
$getStock
->get_result()
->fetch_assoc()['stock_quantity'];

$newStock =
$currentStock -
$item['quantity'];

/*
|--------------------------------------------------------------------------
| Reduce Stock
|--------------------------------------------------------------------------
*/

$stock = $conn->prepare("
    UPDATE products
    SET stock_quantity=?,
        status=?
    WHERE id=?
");

$status =
($newStock <= 0)
?
'out_of_stock'
:
'available';

$stock->bind_param(
    "isi",
    $newStock,
    $status,
    $item['id']
);

if(!$stock->execute()){
    throw new Exception(
        "Failed to update stock."
    );
}

/*
|--------------------------------------------------------------------------
| Inventory History
|--------------------------------------------------------------------------
*/

$note =
"Order #".$orderId;

$inventory = $conn->prepare("
    INSERT INTO inventory_transactions
    (
        product_id,
        transaction_type,
        quantity,
        previous_stock,
        new_stock,
        notes
    )
    VALUES
    (
        ?,
        'stock_out',
        ?,
        ?,
        ?,
        ?
    )
");

$inventory->bind_param(
    "iiiis",
    $item['id'],
    $item['quantity'],
    $currentStock,
    $newStock,
    $note
);

if(!$inventory->execute()){
    throw new Exception(
        "Inventory Log Error: "
        . $inventory->error
    );
}
    }

    /*
    |--------------------------------------------------------------------------
    | Save Payment
    |--------------------------------------------------------------------------
    */

    $transactionId =
    "TXN" .
    time() .
    rand(1000,9999);

    $payment = $conn->prepare("
    INSERT INTO payments
    (
        order_id,
        amount,
        payment_method,
        payment_status,
        transaction_id
    )
    VALUES
    (
        ?,
        ?,
        ?,
        'paid',
        ?
    )
");

    $payment->bind_param(
        "idss",
        $orderId,
        $total,
        $paymentMethod,
        $transactionId
    );

    if(!$payment->execute()){

    throw new Exception(
        "Payment Error: "
        . $payment->error
    );
}

    /*
    |--------------------------------------------------------------------------
    | Commit Transaction
    |--------------------------------------------------------------------------
    */

    $conn->commit();

    /*
    |--------------------------------------------------------------------------
    | Clear Cart
    |--------------------------------------------------------------------------
    */

    unset($_SESSION['cart']);

    $_SESSION['success'] =
    "Order placed successfully";

    echo "ORDER SUCCESSFUL";
echo "<br>Order ID: ".$orderId;
echo "<br>Tracking Number: ".$trackingNumber;
exit;

    exit;

}catch(Exception $e){

    $conn->rollback();

    die(
        "<h2>SQL ERROR</h2>" .
        $e->getMessage()
    );
}