<?php

function addToWishlist($userId, $productId)
{
    global $conn;

    $stmt = $conn->prepare(
        "
        SELECT id
        FROM wishlist
        WHERE user_id = ?
        AND product_id = ?
        "
    );

    $stmt->bind_param(
        "ii",
        $userId,
        $productId
    );

    $stmt->execute();

    if (
        $stmt->get_result()->num_rows > 0
    ) {
        return false;
    }

    $stmt = $conn->prepare(
        "
        INSERT INTO wishlist
        (
            user_id,
            product_id
        )
        VALUES
        (
            ?, ?
        )
        "
    );

    $stmt->bind_param(
        "ii",
        $userId,
        $productId
    );

    return $stmt->execute();
}

function getWishlistCount($userId)
{
    global $conn;

    $stmt = $conn->prepare(
        "
        SELECT COUNT(*) AS total
        FROM wishlist
        WHERE user_id = ?
        "
    );

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();

    $result =
    $stmt->get_result()
    ->fetch_assoc();

    return $result['total'];
}


function removeFromWishlist($userId, $productId)
{
    global $conn;

    $stmt = $conn->prepare(
        "
        DELETE FROM wishlist
        WHERE user_id = ?
        AND product_id = ?
        "
    );

    $stmt->bind_param(
        "ii",
        $userId,
        $productId
    );

    $stmt->execute();
}