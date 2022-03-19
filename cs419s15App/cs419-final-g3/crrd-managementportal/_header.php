<?php require_once('inc/user_auth.php'); ?>
<?php require_once('inc/functions.php'); ?>
<?php
    $fileName = basename($_SERVER['PHP_SELF']);
    $title = get_title($fileName);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="Shortcut Icon" href="assets/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css">
    <script src="assets/js/vendor/jquery.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
</head>
<body>
<div class="full-width">
    <header>
		<?php include("inc/navigation.php"); ?>
    </header>