<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Connexion</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
<body>
    <?php include_once 'header.php';?>

</body>
</html>