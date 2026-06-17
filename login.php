<?php

require_once "config/init.php";

/*
|--------------------------------------------------------------------------
| Redirect if already logged in
|--------------------------------------------------------------------------
*/

if (isLoggedIn()) {

    if ($_SESSION['role'] === 'admin') {

        header("Location: admin/index.php");

    } else {

        header("Location: customer/dashboard.php");

    }

    exit;
}

/*
|--------------------------------------------------------------------------
| Variables
|--------------------------------------------------------------------------
*/

$message = "";
$message_type = "";

/*
|--------------------------------------------------------------------------
| Login Process
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation
    if (empty($email) || empty($password)) {

        $message = "Please enter email and password.";
        $message_type = "error";

    } else {

        $result = loginUser($email, $password);

        if ($result === true) {

            // Safety check
            if (!isset($_SESSION['role'])) {

                $message = "User role not found.";
                $message_type = "error";

            } else {

                /*
                |--------------------------------------------------------------------------
                | Admin
                |--------------------------------------------------------------------------
                */

                if ($_SESSION['role'] === 'admin') {

                    header("Location: admin/index.php");
                    exit;
                }

                /*
                |--------------------------------------------------------------------------
                | Customer
                |--------------------------------------------------------------------------
                */

                if ($_SESSION['role'] === 'customer') {

                    // Homepage Browse Products button
                    if (
                        isset($_GET['redirect']) &&
                        $_GET['redirect'] === 'products'
                    ) {

                        header("Location: products.php");
                        exit;
                    }

                    // Normal login
                    header("Location: customer/dashboard.php");
                    exit;
                }

                /*
                |--------------------------------------------------------------------------
                | Invalid Role
                |--------------------------------------------------------------------------
                */

                $message = "Invalid user role.";
                $message_type = "error";
            }

        } else {

            $message = $result;
            $message_type = "error";
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1"
>

<title>

Login |
<?= APP_NAME ?>

</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial,sans-serif;
    background:#f4f4f4;
}

.container{

    width:420px;
    max-width:95%;

    margin:80px auto;

    background:white;

    padding:30px;

    border-radius:10px;

    box-shadow:
    0 0 20px rgba(0,0,0,.15);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

input{

    width:100%;

    padding:12px;

    margin-bottom:15px;

    border:1px solid #ccc;

    border-radius:5px;
}

button{

    width:100%;

    padding:12px;

    background:#007bff;

    color:white;

    border:none;

    border-radius:5px;

    cursor:pointer;
}

button:hover{

    background:#0056b3;
}

.success{

    background:#d4edda;

    color:#155724;

    padding:12px;

    margin-bottom:15px;

    border-radius:5px;
}

.error{

    background:#f8d7da;

    color:#721c24;

    padding:12px;

    margin-bottom:15px;

    border-radius:5px;
}

p{

    text-align:center;

    margin-top:15px;
}

a{

    color:#007bff;

    text-decoration:none;
}

a:hover{

    text-decoration:underline;
}

</style>

</head>

<body>

<div class="container">

<h2>

Login to <?= APP_NAME ?>

</h2>

<?php if ($message): ?>

<div class="<?= $message_type; ?>">

    <?= htmlspecialchars($message); ?>

</div>

<?php endif; ?>

<form method="POST">

<input
    type="email"
    name="email"
    placeholder="Email Address"
    required
>

<input
    type="password"
    name="password"
    placeholder="Password"
    required
>

<button type="submit">

    Login

</button>

</form>

<p>

Don't have an account?

<a href="register.php">

Register

</a>

</p>

</div>

</body>

</html>