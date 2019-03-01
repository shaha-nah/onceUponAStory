<?php
    $server_name = "localhost";
    $user_name = "shahanah";
    $dpassword = "1234";
    $db_name = "onceuponastory";
    
    try{
        $conn = new PDO("mysql:host=$server_name;dbname=$db_name", $user_name, $dpassword);
    }
    catch(PDOException $e){
        echo $sql . "<br>" . $e->getMessage();
    }
?>
