<?php

function addToCart($productId, $quantity = 1)
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$productId])) {

        $_SESSION['cart'][$productId] += $quantity;

    } else {

        $_SESSION['cart'][$productId] = $quantity;
    }
}

function removeFromCart($productId)
{
    if (isset($_SESSION['cart'][$productId])) {

        unset($_SESSION['cart'][$productId]);
    }
}

function updateCartQuantity($productId, $quantity)
{
    if ($quantity <= 0) {

        removeFromCart($productId);

    } else {

        $_SESSION['cart'][$productId] = $quantity;
    }
}

function getCartItems()
{
    return $_SESSION['cart'] ?? [];
}

function clearCart()
{
    unset($_SESSION['cart']);
}