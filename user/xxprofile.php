<!DOCTYPE html>
<?php
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: login.php?referer=home.php");
    }
    else{
        $user=$_SESSION['username'];
    }
?>

<html>
    <head>
        <title>Profile</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/story.css">
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="http://code.jquery.com/jquery-1.10.1.min.js" ></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script>
        $(document).ready(function(){
            $("#add").on('submit',function(event){
            event.preventDefault();
                data = $(this).serialize();

                $.ajax({
                type: "POST",
                url: "action.php",
                data: data
                }).done(function( msg ) {
                alert( "Story " + msg );
                });
            });
        });
        </script>
    </head>

    <body>
        <?php
            $activemenu="";
            include('../includes/menu.php');

            require_once "../includes/db_connect.php";

            if (isset($_GET['profile'])){
                $profile=$_GET['profile'];
            }
            else{
                $profile=$user;
            }

            include('../includes/profileheader.php');

            if(isset($_GET['action'])){
                if ($_GET['action'] == "complete"){
                    $storyid=$_GET['storyid'];
                    $storyUpdate="  UPDATE  story
                                    SET     status=1
                                    WHERE   storyid=$storyid";
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $updateResult=$conn->exec($storyUpdate);
                }
                if ($_GET['action'] == "delete"){
                    $storyid=$_GET['storyid'];
                    $deletestory="  DELETE
                                    FROM    story
                                    WHERE   storyid=$storyid";
                    $deleteResult=$conn->query($deletestory);
                }

                if ($_GET['action'] == "addlist"){
                    $storyid=$_GET['storyid'];
                    $insertList="   INSERT INTO myreads(storyid, username, readinglist)
                                    VALUES      ($storyid, '$user', 1)";
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insertResult=$conn->exec($insertList);
                }

            }
            $completeQuery = "  SELECT  storyid, storyname, datecreated, category, tags, storydescription, storypicture
                                FROM    story
                                WHERE   author = '$profile'
                                AND     status=1";
            $completeResult = $conn->query($completeQuery);
            $completeRows=$completeResult->rowCount();

            $ongoingQuery = "   SELECT  storyid, storyname, datecreated, category, tags, storydescription, storypicture
                                FROM    story
                                WHERE   author = '$profile'
                                AND     status=0";
            $ongoingResult = $conn->query($ongoingQuery);
            $ongoingRows=$ongoingResult->rowCount();
            if ($completeRows == 0 && $ongoingRows == 0){
                if ($profile==$user){
                    echo '<h4>You have not written any stories</h4>';
                    echo '<a href="storydetails.php"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>';
                }
                else{
                    echo '<h4>User has not written any stories yet</h4>';
                }
            }
            else{
                if ($profile==$user){
                    echo '<a href="storydetails.php"><i class="fa fa-plus-circle" aria-hidden="true" style="float:right;margin: 20px 20px;"></i></a>';
                }
                // echo '<h2>Stories</h2>';


                if ($ongoingRows != 0){
                    ?>
                    <h2>ONGOING STORIES</h2>

                    <div class="container">
                        <?php while ($row=$ongoingResult->fetch()){
                            $ratingQuery="  SELECT  ROUND(AVG(rating), 0) AS rating
                                            FROM    review
                                            WHERE   storyid=".$row['storyid']."
                                            AND     rating>0";
                            $ratingResult=$conn->query($ratingQuery);
                            $ratingRow=$ratingResult->fetch();
                            $rowCount=$ratingResult->rowCount();
                            if (empty($ratingRow['rating'])){
                                $rating=0;
                            }
                            else{
                                $rating=$ratingRow['rating'];
                            }
                            $readerQuery="  SELECT  COUNT(*)
                                            FROM    myreads
                                            WHERE   storyid=".$row['storyid']."
                                            AND     username='$user'";
                            $readerResult=$conn->query($readerQuery);
                            $readerCount=$readerResult->rowCount();
                            echo'   <div class="story">
                                        <div class="details">
                                            <a href="viewStory.php?storyid='.$row['storyid'].'&action=add"><img src="../images/'.$row['storypicture'].'"></a>
                                            <h3>'.$row['storyname'].'</h3>
                                        </div>';
                                        if ($profile != $user){
                                echo'       <div class="addbutton">
                                                <form id=add>
                                                    <a href="profile.php?profile='.$profile.'&action=addlist&storyid='.$row['storyid'].'">Add to reading list</a>
                                                </form>
                                            </div>';
                                        }
                                        else{
                                            echo'   <div class="storyaction">
                                                        <p><a href="user.php?type=reader&storyid='.$row['storyid'].'"><i class="fa fa-eye" aria-hidden="true">'.$readerCount.'</i></a></p>
                                                        <p><a href="storydetails.php?storyid='.$row['storyid'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></p>
                                                        <p><a href="editor.php?storyid='.$row['storyid'].'"><i class="fa fa-plus-circle" aria-hidden="true""></i></a></p>
                                                        <p><a href="profile.php?profile='.$profile.'&action=complete&storyid='.$row['storyid'].'"><i class="fa fa-check" aria-hidden="true"></i></a></p>
                                                        <p><a href="profile.php?profile='.$profile.'&action=delete&storyid='.$row['storyid'].'"><i class="fa fa-trash" aria-hidden="true"></i></a></p>
                                                    </div>
                                            ';
                                        }
                            echo'       <div class="stars">';
                                            $i=0;
                                            while ($i<$rating){
                                                $i++;
                                                echo '<span class="fa fa-star checked"></span>';
                                            }
                                            while ($i<5){
                                                $i++;
                                                echo '<span class="fa fa-star"></span>';
                                            }
                                        echo '</div>
                                    </div>';
                        }?>
                    </div>


                <?php
                }

                if ($completeRows != 0){
                    ?>
                    <h2>COMPLETED STORIES</h2>

                    <div class="container">
                        <?php while ($row=$completeResult->fetch()){
                            $ratingQuery="  SELECT  ROUND(AVG(rating), 0) AS rating
                                            FROM    review
                                            WHERE   storyid=".$row['storyid']."
                                            AND     rating>0";
                            $ratingResult=$conn->query($ratingQuery);
                            $ratingRow=$ratingResult->fetch();
                            $rowCount=$ratingResult->rowCount();
                            if (empty($ratingRow['rating'])){
                                $rating=0;
                            }
                            else{
                                $rating=$ratingRow['rating'];
                            }

                            $readerQuery="  SELECT  COUNT(*)
                                            FROM    myreads
                                            WHERE   storyid=".$row['storyid']."
                                            AND     username='$user'";
                            $readerResult=$conn->query($readerQuery);
                            $readerCount=$readerResult->rowCount();
                            echo'   <div class="story">
                                        <div class="details">
                                            <a href="viewStory.php?storyid='.$row['storyid'].'&action=add"><img src="../images/'.$row['storypicture'].'"></a>
                                            <h3>'.$row['storyname'].'</h3>
                                        </div>';
                                        if ($profile != $user){
                                echo'       <div class="addbutton">
                                                <form id=add>
                                                    <a href="profile.php?profile='.$profile.'&action=addlist&storyid='.$row['storyid'].'">Add to reading list</a>
                                                </form>
                                            </div>';
                                        }else{
                                            echo'   <div class="storyaction">
                                                        <a href="user.php?type=reader&storyid='.$row['storyid'].'"><i class="fa fa-eye" aria-hidden="true">'.$readerCount.'</i></a>
                                                        <p><a href="profile.php?profile='.$profile.'&action=delete&storyid='.$row['storyid'].'"><i class="fa fa-trash" aria-hidden="true"></i></a></p>
                                                    </div>
                                            ';
                                        }
                            echo'       <div class="stars">';
                                            $i=0;
                                            while ($i<$rating){
                                                $i++;
                                                echo '<span class="fa fa-star checked"></span>';
                                            }
                                            while ($i<5){
                                                $i++;
                                                echo '<span class="fa fa-star"></span>';
                                            }
                                        echo '</div>
                                    </div>';
                        }?>
                    </div>


                <?php
                }
            }
        ?>
    </body>
</html>
