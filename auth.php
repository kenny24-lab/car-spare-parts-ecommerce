<?php

/**
 * Check if email already exists
 */
function emailExists($email)
{
    global $conn;

    $sql = "SELECT id FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

/**
 * Register a new customer
 */
function registerUser($full_name, $email, $phone, $password)
{
    global $conn;

    // Check if email exists
    if (emailExists($email)) {
        return "Email already registered.";
    }

    // Encrypt password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "
        INSERT INTO users 
        (full_name, email, phone, password)
        VALUES (?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $full_name,
        $email,
        $phone,
        $hashedPassword
    );

    if ($stmt->execute()) {
        return true;
    }

    return "Registration failed.";
}

/**
 * Login user
 */
function loginUser($email, $password)
{
    global $conn;

    $sql = "
        SELECT *
        FROM users
        WHERE email = ?
        AND status = 'active'
        LIMIT 1
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();


        if (password_verify($password, $user['password'])) {


            // Create session
            $_SESSION['user_id'] = $user['id'];

            $_SESSION['full_name'] = $user['full_name'];

            $_SESSION['email'] = $user['email'];

            $_SESSION['role'] = $user['role'];
            
            echo "<pre>";
            print_r($user);
            echo "</pre>";
            exit;

            return true;
        }
    }


    return "Invalid email or password.";
}