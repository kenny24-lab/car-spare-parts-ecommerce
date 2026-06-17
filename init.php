<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/constants.php';

require_once __DIR__ . '/database.php';

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost:3000/car-spare-parts/');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/session.php';

require_once __DIR__ . '/../functions/auth.php';

require_once __DIR__ . '/../functions/wishlist.php';

require_once __DIR__ . '/../functions/cart.php';

header_remove("X-Powered-By");