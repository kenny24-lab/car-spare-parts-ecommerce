<?php

require_once "config/init.php";

// Destroy user session
logoutUser();

// Redirect to login page
header("Location: login.php");
exit;