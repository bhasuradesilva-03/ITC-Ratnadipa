<?php
require_once __DIR__ . '/includes/data.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/cms-admin.php';
if (cms_admins()) { header('Location: cms/login.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
 if (!hash_equals(csrf_token(), $_POST['csrf'] ?? '')) $error='Invalid request. Please try again.';
 else { $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??'';
  if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<10) $error='Enter a name, valid email and password with at least 10 characters.';
  else { $ok=cms_admin_save([['id'=>1,'name'=>$name,'email'=>$email,'password'=>password_hash($password,PASSWORD_DEFAULT)]]); if($ok){header('Location: cms/login.php');exit;} $error='Could not save admin file. Check config folder permissions.'; }
 }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Set up CMS Admin</title><link rel="stylesheet" href="assets/css/cms.css"></head><body><main class="auth-card"><h1>ITC Ratnadipa CMS</h1><p>Create the first administrator account. You can add more admins after signing in.</p><?php if($error):?><p class="error"><?=e($error)?></p><?php endif;?><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><label>Admin name<input name="name" required></label><label>Email<input type="email" name="email" required></label><label>Password (minimum 10 characters)<input type="password" name="password" minlength="10" required></label><button>Create first admin</button></form></main></body></html>
