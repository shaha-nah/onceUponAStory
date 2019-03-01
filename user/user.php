<?php
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: login.php");
    }
    else{
        $user=$_SESSION['username'];
    }
?>

<html>
    <head>
        <title>Users</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="../css/xfollowbutton.css">
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-color/2.1.2/jquery.color.min.js'></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
            $activemenu="";
            include('../includes/menu.php');

            require_once "../includes/db_connect.php";
            if (isset($_GET['profile'])){
                $profile=$_GET['profile'];
                if ($profile==$user){
                    // include('includes/myprofilemenu.php');
                    $profile=$user;
                }
                else{
                    // include('includes/profilemenu.php');
                }
            }
            else{
                // include('includes/myprofilemenu.php');
                $profile=$user;
            }

            include('../includes/profileheader.php');

            if (isset($_GET['action'])){
                if (isset($_GET['user'])){
                    $username=$_GET['user'];
                }
                else{
                    $username=$_GET['profile'];
                }
                if (isset($_GET['action'])){
                    if ($_GET['action'] == "follow"){
                        $followInsert=" INSERT INTO follow(follower, following)
                                        VALUES      ('$user','$username')";
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $insertResult=$conn->exec($followInsert);
                    }
                    if ($_GET['action'] == "unfollow"){
                        $unfollowDelete="   DELETE
                                            FROM    follow
                                            WHERE   follower='$user'
                                            AND     following='$username'";
                        $unfollowResult=$conn->query($unfollowDelete);
                    }
                }
            }

            if (isset($_GET['type'])){
                if ($_GET['type'] == "follower"){
                    $followQuery="  SELECT  follower
                                    FROM    follow
                                    WHERE   following='$profile'";
                    $followResult=$conn->query($followQuery);
                    $followCount=$followResult->rowCount();
                    if ($followCount == 0){
                        echo '<h3>No followers yet</h3>';
                    }
                    else{
                        while ($row=$followResult->fetch()){
                            $username=$row['follower'];
                            if ($username!=$user){
                                $followQuery2=" SELECT  *
                                                FROM    follow
                                                WHERE   follower='$user'
                                                AND     following='$username'";
                                $followResult2=$conn->query($followQuery2);
                                $followCount2=$followResult2->rowCount();
                                if ($followCount2==0){
                                    echo '  <div class=user>
                                                <a href="profile.php?profile='.$username.'">'.$username.'</a>
                                                <a href="user.php?profile='.$profile.'&type=follower&action=follow&user='.$username.'">    Follow</a>
                                            </div>
                                    ';
                                }
                                else{
                                    echo '  <div class=user>
                                                <a href="profile.php?profile='.$username.'">'.$username.'</a>
                                                <a href="user.php?profile='.$profile.'&type=follower&action=follow&user='.$username.'">Unfollow</a>
                                            </div>
                                    ';
                                }
                            }
                        }
                    }
                }

                if ($_GET['type'] == "following"){
                    $followQuery="  SELECT  following
                                    FROM    follow
                                    WHERE   follower='$profile'";
                    $followResult=$conn->query($followQuery);
                    $followCount=$followResult->rowCount();
                    if ($followCount == 0){
                        echo '<h3>User is not following anyone</h3>';
                    }
                    else{
                        while ($row=$followResult->fetch()){
                            $username=$row['following'];
                            if ($username!=$user){
                                echo '  <div class=user>
                                            <a href="profile.php?profile='.$username.'">'.$username.'</a>
                                            <a href="user.php?profile='.$profile.'&type=following?action=unfollow&user='.$username.'">   Unfollow</a>
                                        </div>
                                ';
                            }
                        }
                    }
                }

                if ($_GET['type'] == "reader"){
                    if (isset($_GET['storyid'])){
                        $storyid=$_GET['storyid'];
                        $readerQuery="  SELECT  username
                                        FROM    myreads
                                        WHERE   storyid=$storyid
                                        AND     (archive=1
                                        OR      currentreads=1)";
                        $readerResult=$conn->query($readerQuery);
                        while ($row=$readerResult->fetch()){
                            $username=$row['username'];
                            echo '<p><a href="profile.php?profile='.$username.'">'.$username.'</a></p>';
                            if ($username!=$user){
                                $followQuery="  SELECT  *
                                                FROM    follow
                                                WHERE   follower='$user'
                                                AND     following='$username'";
                                $queryResult=$conn->query($followQuery);
                                $queryCount=$queryResult->rowCount();
                                if ($queryCount == 0){
                                    echo '  <div class=user>
                                                <a href="profile.php?profile='.$username.'">'.$username.'</a>
                                                <a href="user.php?profile='.$profile.'&type=follower&action=follow&user='.$username.'">    Follow</a>
                                            </div>
                                    ';
                                }
                                else{
                                    echo '  <div class=user>
                                                <a href="profile.php?profile='.$username.'">'.$username.'</a>
                                                <a href="user.php?profile='.$profile.'&type=following?action=unfollow&user='.$username.'">   Unfollow</a>
                                            </div>
                                    ';
                                }
                            }
                        }
                    }
                }
            }

        ?>
    </body>
</html>
