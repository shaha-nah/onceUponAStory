<?php
    require_once "../includes/db_connect.php";

    $storyid=$_POST['storyid'];
    $username=$_POST['username'];

    $insertList="   INSERT INTO myreads(storyid, username, archive, currentreads, readinglist)
                    VALUES      ($storyid, '$username',0, 0, 1)";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $insertResult=$conn->exec($insertList);
    if ($insertResult){
        echo 'Story inserted';
    }
    else{
        echo 'An error occured. Insertion failed';
    }

?>
