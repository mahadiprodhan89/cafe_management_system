<?php
require_once 'config/session.php';

// Redirect based on login status and role
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: admin/dashboard.php');
    } else {
        header('Location: user/dashboard.php');
    }
} else {
    header('Location: login.php');
}
exit();
?>

