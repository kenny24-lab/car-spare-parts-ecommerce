<?php

require_once "config/init.php";

// Variables
$message = "";
$message_type = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get and clean form data
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];


    // Basic Validation
    if (
        empty($full_name) ||
        empty($email) ||
        empty($password)
    ) {

        $message = "Please fill all required fields.";
        $message_type = "error";

    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid email address.";
        $message_type = "error";

    }
    elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    }
    elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $message_type = "error";

    }
    else {

        // Call registration function
        $result = registerUser(
            $full_name,
            $email,
            $phone,
            $password
        );


        if ($result === true) {

            $message = "Registration successful. You can now login.";
            $message_type = "success";

        }
        else {

            $message = $result;
            $message_type = "error";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Register | <?= APP_NAME ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<style>

body{
    font-family: Arial, sans-serif;
    background:#f4f4f4;
}

.container{
    width:400px;
    margin:50px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
}

h2{
    text-align:center;
}

input{
    width:100%;
    padding:12px;
    margin:8px 0;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    background:#0066cc;
    color:white;
    border:none;
    cursor:pointer;
}

.success{
    background:#d4edda;
    color:#155724;
    padding:10px;
    margin-bottom:15px;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    margin-bottom:15px;
}

a{
    text-decoration:none;
}

</style>

</head>


<body>


<div class="container">


<h2>Create Account</h2>


<?php if($message): ?>

<div class="<?= $message_type ?>">

<?= $message ?>

</div>

<?php endif; ?>


<form method="POST">


<input 
type="text"
name="full_name"
placeholder="Full Name"
required>


<input 
type="email"
name="email"
placeholder="Email Address"
required>


<input 
type="text"
name="phone"
placeholder="Phone Number">


<input 
type="password"
name="password"
placeholder="Password"
required>


<input 
type="password"
name="confirm_password"
placeholder="Confirm Password"
required>


<button type="submit">
Create Account
</button>


</form>


<p style="text-align:center;">

Already have an account?

<a href="login.php">
Login
</a>

</p>


</div>


</body>

</html>