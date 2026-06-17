<?php

require_once "../config/init.php";

requireCustomer();

$userId = $_SESSION['user_id'];

$message = "";
$messageType = "";

/*
|--------------------------------------------------------------------------
| Upload Profile Photo
|--------------------------------------------------------------------------
*/

if(
    $_SERVER['REQUEST_METHOD'] === 'POST'
    &&
    isset($_POST['upload_photo'])
){

    if(!empty($_FILES['profile_photo']['name'])){

        $fileName =
        time() . "_" .
        basename($_FILES['profile_photo']['name']);

        $target =
        "../uploads/profiles/" .
        $fileName;

        if(!file_exists("../uploads/profiles")){
            mkdir("../uploads/profiles",0777,true);
        }

        if(move_uploaded_file(
            $_FILES['profile_photo']['tmp_name'],
            $target
        )){

            $stmt = $conn->prepare("
            UPDATE users
            SET profile_photo=?
            WHERE id=?
            ");

            $stmt->bind_param(
                "si",
                $fileName,
                $userId
            );

            $stmt->execute();

            $message =
            "Profile photo updated successfully.";

            $messageType =
            "success";
        }
    }
}

/*
|--------------------------------------------------------------------------
| Update Profile
|--------------------------------------------------------------------------
*/

if(
    $_SERVER['REQUEST_METHOD'] === 'POST'
    &&
    isset($_POST['update_profile'])
){

    $fullName =
    trim($_POST['full_name']);

    $email =
    trim($_POST['email']);

    $phone =
    trim($_POST['phone']);

    $stmt = $conn->prepare("
    UPDATE users
    SET
    full_name=?,
    email=?,
    phone=?
    WHERE id=?
    ");

    $stmt->bind_param(
        "sssi",
        $fullName,
        $email,
        $phone,
        $userId
    );

    if($stmt->execute()){

        $_SESSION['full_name'] =
        $fullName;

        $_SESSION['email'] =
        $email;

        $message =
        "Profile updated successfully.";

        $messageType =
        "success";
    }
}

/*
|--------------------------------------------------------------------------
| Get User Data
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->bind_param(
"i",
$userId
);

$stmt->execute();

$user =
$stmt->get_result()->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

<title>My Account | <?= APP_NAME ?></title>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,sans-serif;
}

body{
background:darkgray;
}

.header{

background:
linear-gradient(
135deg,
#ff6600,
#ff9500
);

padding:50px;
text-align:center;
color:white;
}

.header h1{
font-size:42px;
}

.container{

width:1000px;
max-width:95%;

margin:30px auto;

display:grid;

grid-template-columns:
320px 1fr;

gap:30px;
}

.card{

background:white;

border-radius:15px;

padding:25px;

box-shadow:
0 5px 20px rgba(0,0,0,.08);
}

.profile-photo{

width:180px;
height:180px;

border-radius:50%;

object-fit:cover;

display:block;

margin:auto;

border:5px solid #eee;
}

.default-avatar{

width:180px;
height:180px;

border-radius:50%;

background:#ddd;

display:flex;
align-items:center;
justify-content:center;

font-size:70px;

margin:auto;
}

.center{
text-align:center;
}

input{

width:100%;

padding:12px;

margin-bottom:15px;

border:1px solid #ddd;

border-radius:8px;
}

button{

background:#007bff;

color:white;

border:none;

padding:12px 20px;

border-radius:8px;

cursor:pointer;
}

button:hover{
background:#0056b3;
}

.success{

background:#d4edda;

color:#155724;

padding:12px;

border-radius:8px;

margin-bottom:15px;
}

.menu a{

display:block;

padding:12px;

margin-bottom:10px;

background:#f8f9fa;

text-decoration:none;

color:#333;

border-radius:8px;
}

.menu a:hover{

background:#007bff;

color:white;
}

@media(max-width:768px){

.container{
grid-template-columns:1fr;
}

}

</style>

</head>

<body>

<div class="header">

<h1>👤 My Account</h1>

<p>
Manage your profile and account settings
</p>

</div>

<?php if($message): ?>

<div
style="
width:1000px;
max-width:95%;
margin:20px auto;
"
class="<?= $messageType; ?>"
>

<?= $message; ?>

</div>

<?php endif; ?>

<div class="container">

<!-- LEFT PANEL -->

<div class="card center">

<?php if(!empty($user['profile_photo'])): ?>

<img
src="../uploads/profiles/<?= htmlspecialchars($user['profile_photo']); ?>"
class="profile-photo"
>

<?php else: ?>

<div class="default-avatar">
👤
</div>

<?php endif; ?>

<br>

<form
method="POST"
enctype="multipart/form-data"
>

<input
type="file"
name="profile_photo"
required
>

<button
type="submit"
name="upload_photo"
>
Upload Photo
</button>

</form>

<hr style="margin:20px 0;">

<div class="menu">

<a href="profile.php">
👤 Profile
</a>

<a href="change-password.php">
🔒 Change Password
</a>

<a href="../orders.php">
📦 My Orders
</a>

<a href="../wishlist.php">
❤️ Wishlist
</a>

<a href="../cart.php">
🛒 Cart
</a>

<a href="../index.php">
🏠 Home
</a>

</div>

</div>

<!-- RIGHT PANEL -->

<div class="card">

<h2 style="margin-bottom:20px;">
Personal Information
</h2>

<form method="POST">

<label>Full Name</label>

<input
type="text"
name="full_name"
value="<?= htmlspecialchars($user['full_name']); ?>"
required
>

<label>Email Address</label>

<input
type="email"
name="email"
value="<?= htmlspecialchars($user['email']); ?>"
required
>

<label>Phone Number</label>

<input
type="text"
name="phone"
value="<?= htmlspecialchars($user['phone']); ?>"
required
>

<button
type="submit"
name="update_profile"
>
Save Changes
</button>

</form>

</div>

</div>

</body>
</html>