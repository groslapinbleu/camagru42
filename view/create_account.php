<?php
if(!isset($_SESSION))
{
	session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Camagru - creation de compte</title>
<meta content="width=device-width, initial-scale=1" name="viewport" />
<link rel="stylesheet" href="../css/application.css" />
</head>

<?php
	require __DIR__ . '/../control/app_create_account.php';
	require __DIR__ . '/../control/captcha.php'; 
    include('../view/header_create_account.php');
    include('../view/content_create_account.php');
    include('../view/footer.php');
?>

</html>
