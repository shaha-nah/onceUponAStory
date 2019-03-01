<?php
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: alogin.php");
    }
    else{
        $admin=$_SESSION['username'];
    }
?>

<html>
    <head>
        <title></title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
      	<link href="../css/font-awesome.min.css" rel="stylesheet">
      	<link href="../css/datepicker3.css" rel="stylesheet">
      	<link href="../css/styles.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
            include('../includes/amenu.php');
            require_once "../includes/db_connect.php";
        ?>
        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>
