<?php
require_once __DIR__.'/../includes/cms-admin.php';
unset($_SESSION['cms_admin']);session_regenerate_id(true);header('Location: login.php');exit;
