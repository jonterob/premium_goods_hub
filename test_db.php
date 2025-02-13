<?php
require 'db.php';

if ($pdo) {
    echo "Database connected successfully!";
} else {
    echo "Database connection failed!";
}
?>
