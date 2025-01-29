<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: chat.php');
} else {
    header('Location: login.php');
}
?>