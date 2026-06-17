<?php

require_once "../config/init.php";

requireAdmin();

$id = (int)$_GET['id'];

$stmt = $conn->prepare(
"
UPDATE payments
SET payment_status='failed'
WHERE id=?
"
);

$stmt->bind_param(
"i",
$id
);

$stmt->execute();

header(
"Location: payments.php"
);

exit;