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
        <title>Reads</title>
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
                if (isset($_GET['profile'])){
                    $profile=$_GET['profile'];
                }
                else{
                    $profile=$user;
                }

                if (isset($_GET['type'])){
                    $type=$_GET['type'];
                }

                include('../includes/profileheader.php');
                require_once "../includes/db_connect.php";


                if (isset($_GET['action'])){
                    $storyid = $_GET['storyid'];
                    if ($_GET['action'] == "remove"){

                        $sDelete="  DELETE
                                    FROM    myreads
                                    WHERE   storyid=$storyid
                                    AND     username='$user'";

                        $sResult=$conn->query($sDelete);
                        // header("Location:reads.php?type=$type");
                    }

                    if ($_GET['action'] == "archive"){
                        $sUpdate="  UPDATE  myreads
                                    SET     archive=1, currentreads=0
                                    WHERE   username='$user'
                                    AND     storyid=$storyid";
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $sResult=$conn->exec($sUpdate);
                    }

                    if ($_GET['action'] == "addlist"){
                        $storyid=$_GET['storyid'];
                        $insertList="   INSERT INTO myreads(storyid, username, readinglist)
                                        VALUES      ($storyid, '$user', 1)";
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $insertResult=$conn->exec($insertList);
                    }
                }

                if ($type=="archive"){
                    $sQuery = " SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                FROM    story, myreads
                                WHERE   myreads.username='$profile'
                                AND     story.storyid=myreads.storyid
                                AND     myreads.archive=1";
                }
                if ($type=="currentreads"){
                    $sQuery = " SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                FROM    story, myreads
                                WHERE   myreads.username='$profile'
                                AND     story.storyid=myreads.storyid
                                AND     myreads.currentreads=1";
                }
                if ($type=="readinglist"){
                    $sQuery = " SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                FROM    story, myreads
                                WHERE   myreads.username='$profile'
                                AND     story.storyid=myreads.storyid
                                AND     myreads.readinglist=1";
                }

                $sResult = $conn->query($sQuery);
                $sRows = $sResult->rowCount();
                if ($sRows == 0){
                    $type=$_GET['type'];
                    if ($profile==$user){
                        if ($type=="archive"){
                            echo '<h4>You have not completed any books</h4>';
                        }
                        if ($type=="currentreads"){
                            echo '<a href="discover.php">Start Reading</a>';
                        }
                        if ($type=="readinglist"){
                            echo '<a href="discover.php">Discover books</a>';
                        }

                    }
                    else{
                        echo "<h3>This user is not reading any books</h3>";
                    }
                }
                else{ ?>
                    <div class="container">
                        <?php while ($row=$sResult->fetch()){
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

                            $storyid=$row['storyid'];
                            echo'   <div class="story">
                                        <a href="viewStory.php?storyid='.$row['storyid'].'&action=add"><img src="../images/'.$row['storypicture'].'"></a>
                                        <h3>'.$row['storyname'].'</h3>';
                                        if ($profile != $user){

                                            $readQuery="    SELECT  *
                                                            FROM    myreads
                                                            WHERE   username='$user'
                                                            AND     storyid=$storyid";
                                            $readResults=$conn->query($readQuery);
                                            $readCount=$readResults->rowCount();
                                            if ($readCount==0){
                                echo'           <div class="addbutton">
                                                    <form id=add>
                                                        <a href="reads.php?type='.$type.'&profile='.$profile.'&storyid='.$row['storyid'].'&action=addlist">Add to reading list</a>
                                                    </form>
                                                </div>';
                                            }
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
                                        echo '</div>';
                                        if ($profile == $user){
                                            if ($type=="archive"){
                                                echo'   <div class=buttonlink>
                                                            <a href="reads.php?type=archive&profile='.$profile.'&action=remove&storyid='.$storyid.'">Remove</a>
                                                        </div>';
                                            }
                                            if ($type=="currentreads"){
                                                echo'   <div class=buttonlink>
                                                            <a href="reads.php?type=currentreads&profile='.$profile.'&action=archive&storyid='.$storyid.'">Archive</a>
                                                        </div>';
                                            }
                                            if ($type=="readinglist"){
                                                echo'   <div class=buttonlink>
                                                            <a href="reads.php?type=readinglist&profile='.$profile.'&action=remove&storyid='.$storyid.'">Remove</a>
                                                        </div>';
                                            }
                                        }
                            echo'   </div>';
                        }?>
                    </div>
                <?php
                }
           ?>
    </body>
</html>
