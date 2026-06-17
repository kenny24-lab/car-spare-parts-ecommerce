<?php





/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
*/

// Application Name
define("APP_NAME", "AutoParts Hub");

// Application URL
define("BASE_URL", "http://localhost/car-spare-parts/");

// Default Time Zone
date_default_timezone_set("Africa/Kigali");


/*
|--------------------------------------------------------------------------
| File Upload Paths
|--------------------------------------------------------------------------
*/

// Product Images
define("PRODUCT_UPLOAD_PATH", "uploads/products/");

// User Profile Images
define("USER_UPLOAD_PATH", "uploads/users/");


/*
|--------------------------------------------------------------------------
| Application Settings
|--------------------------------------------------------------------------
*/

// Maximum file upload size (5 MB)
define("MAX_FILE_SIZE", 5 * 1024 * 1024);

// Allowed image extensions
define("ALLOWED_IMAGE_TYPES", [
    "jpg",
    "jpeg",
    "png",
    "webp"
]);

?>