<?php
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: login.php");
    }
    else{
        $user=$_SESSION['username'];
    }
?>

<?php
    require_once "../includes/db_connect.php";

    if (isset($_POST['action'])){
        if ($_POST['action'] == "addlist"){
            $storyid=$_POST['storyid'];
            $username=$_POST['username'];
            $insertList="   INSERT INTO myreads(storyid, username, readinglist)
                            VALUES      ($storyid, '$username', 1)";
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $insertResult=$conn->exec($insertList);
            if ($insertResult){
                echo 'Story inserted';
            }
            else{
                echo 'An error occured. Insertion failed';
            }
        }

        if ($_POST['action'] == "bookmark"){
            $storyid=$_POST['storyid'];
            $username=$_POST['username'];
            $chapterno=$_POST['chapterno'];
            $bookmarkQuery="    SELECT  *
                                FROM    bookmark
                                WHERE   username='$username'
                                AND     storyid=$storyid";
            $bookmarkResults=$conn->query($bookmarkQuery);
            $bookmarkRows=$bookmarkResults->rowCount();
            if ($bookmarkRows==0){
                $bookmarkInsert="   INSERT INTO bookmark(username, storyid, chapterno)
                                    VALUES      ('$username', $storyid, $chapterno)";
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $insertResult=$conn->exec($bookmarkInsert);
            }
            else{
                $bookmarkUpdate="   UPDATE  bookmark
                                    SET     chapterno=$chapterno
                                    WHERE   username='$username'
                                    AND     storyid=$storyid";
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $updateResult=$conn->exec($bookmarkUpdate);
            }

            if ($updateResult || $insertResult){
                echo 'Bookmarked';
            }
            else{
                echo 'Please try again.';
            }

        }

        if ($_POST['action'] == "comment"){
            $username=$_POST['username'];
            $chapterno=$_POST['chapterno'];
            $storyid=$_POST['storyid'];
            $commentdate=date("Y/m/d");

            if (empty($_POST['comment'])){
                $commenttext="";
            }
            else{
                $commenttext=$_POST['comment'];
            }

            if (empty($_POST['rating'])){
                $rating=0;
            }
            else{
                $rating=$_POST['rating'];
            }

            $commentInsert="    INSERT INTO review(username, storyid, chapterno, commenttext, commentdate, rating)
                                VALUES      ('$username', $storyid, $chapterno, '$commenttext', '$commentdate', $rating)";
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $commentResult=$conn->exec($commentInsert);
            if ($commentResult){
                echo 'Done';
                header("Location: reader.php?storyid='.$storyid.'&chapterno='.$chapterno.'");
            }
        }
    }
?>
