<?php
// logout.php
require_once 'includes/auth.php';

session_start();
session_unset();
session_destroy();

// Start a fresh session to carry the logout notification
session_start();
setFlash("You have been logged out successfully.", "info");

header("Location: frontend/index.php");
exit();
?>
