<?php

session_start();

$id =
(int)$_GET['id'];

$action =
$_GET['action'];

if(isset($_SESSION['cart'][$id])){

    if($action == "plus"){

        $_SESSION['cart'][$id]['quantity']++;

    }elseif($action == "minus"){

        $_SESSION['cart'][$id]['quantity']--;

        if(
            $_SESSION['cart'][$id]['quantity']
            <= 0
        ){
            unset($_SESSION['cart'][$id]);
        }
    }
}

header("Location: cart.php");
exit;