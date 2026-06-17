<?php

require_once "../config/init.php";

requireCustomer();

$userId = $_SESSION['user_id'];

$message = "";
$messageType = "";

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {

    $currentPassword =
    $_POST['current_password'];

    $newPassword =
    $_POST['new_password'];

    $confirmPassword =
    $_POST['confirm_password'];

    $stmt = $conn->prepare(
        "
        SELECT password
        FROM users
        WHERE id = ?
        "
    );

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();

    $user =
    $stmt->get_result()->fetch_assoc();

    if (
        !password_verify(
            $currentPassword,
            $user['password']
        )
    ) {

        $message =
        "Current password is incorrect.";

        $messageType =
        "error";

    }
    elseif (
        $newPassword !==
        $confirmPassword
    ) {

        $message =
        "Passwords do not match.";

        $messageType =
        "error";

    }
    else {

        $newHash =
        password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare(
            "
            UPDATE users
            SET password = ?
            WHERE id = ?
            "
        );

        $stmt->bind_param(
            "si",
            $newHash,
            $userId
        );

        $stmt->execute();

        $message =
        "Password changed successfully.";

        $messageType =
        "success";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Change Password</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    min-height:100vh;

    background:
    linear-gradient(
        135deg,
        #0f172a,
        #1e3a8a,
        #0ea5e9
    );

    display:flex;
    justify-content:center;
    align-items:center;
    padding:30px;
}

.container{

    width:500px;
    max-width:100%;

    background:white;

    padding:40px;

    border-radius:20px;

    box-shadow:
    0 15px 40px rgba(0,0,0,.25);
}

.logo{

    text-align:center;
    margin-bottom:25px;
}

.logo h1{

    color:#0f172a;
    font-size:34px;
}

.logo p{

    color:#666;
    margin-top:5px;
}

.form-group{

    margin-bottom:20px;
}

label{

    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#333;
}

input{

    width:100%;

    padding:15px;

    border:1px solid #ddd;

    border-radius:10px;

    font-size:15px;

    transition:.3s;
}

input:focus{

    border-color:#0ea5e9;

    outline:none;

    box-shadow:
    0 0 10px rgba(14,165,233,.3);
}

.btn{

    width:100%;

    border:none;

    background:
    linear-gradient(
    135deg,
    #2563eb,
    #0ea5e9
    );

    color:white;

    padding:15px;

    border-radius:10px;

    font-size:16px;

    font-weight:bold;

    cursor:pointer;

    transition:.3s;
}

.btn:hover{

    transform:translateY(-2px);

    box-shadow:
    0 10px 20px rgba(0,0,0,.2);
}

.back-btn{

    display:block;

    margin-top:15px;

    text-align:center;

    text-decoration:none;

    color:#2563eb;

    font-weight:600;
}

.back-btn:hover{

    color:#0f172a;
}

.success{

    background:#d4edda;

    color:#155724;

    padding:12px;

    border-radius:8px;

    margin-bottom:20px;
}

.error{

    background:#f8d7da;

    color:#721c24;

    padding:12px;

    border-radius:8px;

    margin-bottom:20px;
}

</style>

</head>

<body>

<div class="container">

<div class="logo">

<h1>🔒 Change Password</h1>

<p>
Keep your account secure
</p>

</div>

<?php if(!empty($message)): ?>

<div class="<?= $messageType; ?>">

<?= $message; ?>

</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">

<label>Current Password</label>

<input
type="password"
name="current_password"
required
>

</div>

<div class="form-group">

<label>New Password</label>

<input
type="password"
name="new_password"
required
>

</div>

<div class="form-group">

<label>Confirm New Password</label>

<input
type="password"
name="confirm_password"
required
>

</div>

<button
type="submit"
name="change_password"
class="btn"
>

🔒 Change Password

</button>

</form>

<a
href="profile.php"
class="back-btn"
>

← Back To Profile

</a>

</div>
</body>
</html>