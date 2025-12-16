<?php
/**
 * This script generates proper password hashes for the default users
 * Run this once after importing the database to update passwords
 * Or use the plain text fallback in login.php for demo purposes
 */

require_once 'config/database.php';

$conn = getDBConnection();

// Update admin password (admin123)
$admin_hash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt->bind_param("s", $admin_hash);
$stmt->execute();
$stmt->close();

// Update user password (user123)
$user_hash = password_hash('user123', PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = 'user1'");
$stmt->bind_param("s", $user_hash);
$stmt->execute();
$stmt->close();

echo "Password hashes updated successfully!<br>";
echo "You can now login with:<br>";
echo "Admin: admin / admin123<br>";
echo "User: user1 / user123<br>";
echo "<br><a href='login.php'>Go to Login</a>";

$conn->close();
?>

