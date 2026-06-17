<?php

require_once "../config/init.php";

requireAdmin();

if(!isset($_GET['id']))
{
    header("Location: orders.php");
    exit;
}

$orderId = (int)$_GET['id'];

try{

    $conn->begin_transaction();

    /*
    |--------------------------------------------------------------------------
    | Check Order Exists
    |--------------------------------------------------------------------------
    */

    $order = $conn->query("
        SELECT *
        FROM orders
        WHERE id = {$orderId}
    ");

    if($order->num_rows == 0)
    {
        throw new Exception("Order not found.");
    }

    /*
    |--------------------------------------------------------------------------
    | Restore Product Stock
    |--------------------------------------------------------------------------
    */

    $items = $conn->query("
        SELECT product_id, quantity
        FROM order_items
        WHERE order_id = {$orderId}
    ");

    while($item = $items->fetch_assoc())
    {
        $updateStock = $conn->prepare("
            UPDATE products
            SET stock_quantity =
                stock_quantity + ?
            WHERE id = ?
        ");

        $updateStock->bind_param(
            "ii",
            $item['quantity'],
            $item['product_id']
        );

        $updateStock->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Payment Record
    |--------------------------------------------------------------------------
    */

    $payment = $conn->prepare("
        DELETE FROM payments
        WHERE order_id = ?
    ");

    $payment->bind_param(
        "i",
        $orderId
    );

    $payment->execute();

    /*
    |--------------------------------------------------------------------------
    | Delete Order Items
    |--------------------------------------------------------------------------
    */

    $orderItems = $conn->prepare("
        DELETE FROM order_items
        WHERE order_id = ?
    ");

    $orderItems->bind_param(
        "i",
        $orderId
    );

    $orderItems->execute();

    /*
    |--------------------------------------------------------------------------
    | Delete Order
    |--------------------------------------------------------------------------
    */

    $deleteOrder = $conn->prepare("
        DELETE FROM orders
        WHERE id = ?
    ");

    $deleteOrder->bind_param(
        "i",
        $orderId
    );

    $deleteOrder->execute();

    $conn->commit();

    $_SESSION['success'] =
        "Order deleted successfully.";

}
catch(Exception $e){

    $conn->rollback();

    $_SESSION['error'] =
        $e->getMessage();
}

header("Location: orders.php");
exit;