<?php
session_start();
session_destroy();

require_once '../config/config.php';
redirect('auth/login.php');
?>
