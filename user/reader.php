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
        <title>Reader</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/font-awesome.min.css" rel="stylesheet">
        <link href="../css/datepicker3.css" rel="stylesheet">
        <!-- <link href="../css/styles.css" rel="stylesheet"> -->
        <link href="../css/menu.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <style>
            .nav-tabs>li {
                float: right;
                margin-bottom: -1px;
            }
            .nav-tabs>li>a {
                margin-right: 0px;
            }
            /* .panel-body:before{
                width: 20%;
                background-color: BLACK;
                }
                .panel-body:after{
                height: 80%;
                background-color: YELLOW;
            } */


            /* .panel .tabs:after{
                content: '';
                position: absolute;
                width:20%;
                left:0;
                height:110%;
                background: yellow;
            } */
            .panel .tabs:before{
                content: '';
                position: absolute;
                width:140px;
                left:0%;
                height: 100%;
                background: #f1f4f7;
            }

        </style>
    </head>

    <body>
        <?php
            $activemenu = "content";
            require_once "../includes/db_connect.php";
            include('../includes/menu.php');

            // is set POST
            if (isset($_GET['storyid'])){
                $storyid=$_GET['storyid'];
            }
            if (isset($_GET['chapterno'])){
                $chapterno=$_GET['chapterno'];
            }
            else{
                $bookmarkQuery="    SELECT  chapterno
                                    FROM    bookmark
                                    WHERE   username='$user'
                                    AND     storyid=$storyid";
                $bookmarkResult=$conn->query($bookmarkQuery);
                $bookmark=$bookmarkResult->fetch();
                $rowCount=$bookmarkResult->rowCount();
                if ($rowCount == 0){
                    $chapterQuery=" SELECT  MIN(chapterno) AS firstchapter
                                    FROM    chapter
                                    WHERE   storyid=$storyid";
                    $chapterResult=$conn->query($chapterQuery);
                    $chapterRow=$chapterResult->fetch();
                    $chapterno=$chapterRow['firstchapter'];
                }
                else{
                    $chapterno=$bookmark['chapterno'];
                }
            }
            
            if ($_SERVER['REQUEST_METHOD'] == "POST"){
                $username=$user;
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
                                    VALUES      ('$user', $storyid, $chapterno, '$commenttext', '$commentdate', $rating)";
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $commentResult=$conn->exec($commentInsert);
                if ($commentResult){
                    header("Location:reader.php?storyid=$storyid");
                }
            }

            if (isset($_GET['action'])){
                if ($_GET['action'] == "add"){
                    $storyQuery="   SELECT  *
                                    FROM    myreads
                                    WHERE   storyid=$storyid
                                    AND     username='$user'";
                    $queryResult=$conn->query($storyQuery);
                    $rowCount=$queryResult->rowCount();
                    if ($rowCount == 0){
                        $storyInsert="  INSERT INTO myreads(storyid,username, currentreads)
                                        VALUES      ($storyid, '$user', 1)";
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $insertResult=$conn->exec($storyInsert);
                    }
                    else{
                        $storyUpdate="  UPDATE  myreads
                                        SET     archive=0, currentreads=1, readinglist=0";
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $updateResult=$conn->exec($storyUpdate);
                    }
                }

                if ($_GET['action'] == "review"){
                    $username=$user;
                    $chapterno=$_GET['chapterno'];
                    $storyid=$_GET['storyid'];
                    $commentdate=date("Y/m/d");

                    if (empty($_GET['comment'])){
                        $commenttext="";
                    }
                    else{
                        $commenttext=$_GET['comment'];
                    }

                    if (empty($_GET['rating'])){
                        $rating=0;
                    }
                    else{
                        $rating=$_GET['rating'];
                    }

                    $commentInsert="    INSERT INTO review(username, storyid, chapterno, commenttext, commentdate, rating)
                                        VALUES      ('$user', $storyid, $chapterno, '$commenttext', '$commentdate', $rating)";
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $commentResult=$conn->exec($commentInsert);
                }

                if ($_GET['action'] == "publish"){
                    $publishUpdate="    UPDATE  chapter
                                        SET     published=1
                                        WHERE   chapterno=$chapterno
                                        AND     storyid=$storyid";
                    $publishResult=$conn->exec($publishUpdate);
                }

                if ($_GET['action'] == "delete"){
                    $deleteChapter="    DELETE
                                        FROM    chapter
                                        WHERE   chapterno=$chapterno
                                        AND     storyid=$storyid";
                    $deleteResult=$conn->exec($deleteChapter);
                    if($deleteResult){
                        header("Location:profile.php");
                    }
                }

                if ($_GET['action'] == "deletecomment"){
                    $reviewid=$_GET['review'];
                    $deleteReview=" DELETE
                                    FROM    review
                                    WHERE   reviewid=$reviewid";
                    $deleteResult=$conn->exec($deleteReview);
                }

                if ($_GET['action'] == "flag"){
                    $reviewid=$_GET['review'];
                    $flagReview="   UPDATE  review
                                    SET     flag=1
                                    WHERE   reviewid=$reviewid";
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $flagResult=$conn->exec($flagReview);
                }

                if ($_GET['action'] == "bookmark"){
                    $storyid=$_GET['storyid'];
                    $chapterno=$_GET['chapterno'];
                    $bookmarkQuery="    SELECT  *
                                        FROM    bookmark
                                        WHERE   username='$user'
                                        AND     storyid=$storyid";
                    $bookmarkResults=$conn->query($bookmarkQuery);
                    $bookmarkRows=$bookmarkResults->rowCount();
                    if ($bookmarkRows==0){
                        $bookmarkInsert="   INSERT INTO bookmark(username, storyid, chapterno)
                                            VALUES      ('$user', $storyid, $chapterno)";
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $insertResult=$conn->exec($bookmarkInsert);
                    }
                    else{
                        $bookmarkUpdate="   UPDATE  bookmark
                                            SET     chapterno=$chapterno
                                            WHERE   username='$user'
                                            AND     storyid=$storyid";
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $updateResult=$conn->exec($bookmarkUpdate);
                    
                    }
                }
            }

            $storyQuery = " SELECT  storyname, storydescription
                            FROM    story
                            WHERE   storyid = $storyid;";
            $storyResult =  $conn->query($storyQuery);

            $storyrow = $storyResult->fetch();
            $storyname = $storyrow['storyname'];
            $storydescription = $storyrow['storydescription'];

            $chapterQuery = "   SELECT  chapterno, chaptername, story
                                FROM    chapter
                                WHERE   storyid = $storyid;";
            $chapterResult =  $conn->query($chapterQuery);
            $chapterStory = $conn->query($chapterQuery);

            $chaptercount=$chapterResult->rowCount();

        ?>
        <div class="row">
            <div class="col-lg-12">
                <?php echo '<h2>'.$storyname.'</h2>';?>
                <?php echo '<p>'.$storydescription.'</p>'?>
            </div>
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body tabs" style="padding-left:0px;">
                        <div class="col-md-4" style="width:10%;padding-left:0px;padding-right:0px;min-width:125px;">
                            <ul class="nav nav-tabs" style = "display:block;padding-right:0px;padding-left:0px;">
                                <?php
                                    while ($chapterrow = $chapterResult->fetch()){
                                        if ($chapterrow['chapterno'] == $chapterno){
                                            echo '<li class="active"><a href ="#tab'.$chapterrow['chapterno'].'" data-toggle="tab">Chapter '.$chapterrow['chapterno'].'</a></li>';
                                        }
                                        else{
                                            echo '<li><a href ="#tab'.$chapterrow['chapterno'].'" data-toggle="tab">Chapter '.$chapterrow['chapterno'].'</a></li>';
                                        }
                                    }
                                ?>
                            </ul>
                        </div>
                        <div class="col-md-9"> 
                            <div class="tab-content" >
                                <?php
                                    while ($chapterrow = $chapterStory->fetch()){
                                        if ($chapterrow['chapterno'] == $chapterno){
                                            echo '  <div class="tab-pane fade in active" id="tab'.$chapterrow['chapterno'].'">
                                                        <h4>'.$chapterrow['chaptername'].'</h4>
                                                        <p>'.$chapterrow['story'].'</p>
                                                    </div>
                                            ';
                                        }
                                        else{
                                            echo '  <div class="tab-pane fade" id="tab'.$chapterrow['chapterno'].'">
                                                        <h4>'.$chapterrow['chaptername'].'</h4>
                                                        <p>'.$chapterrow['story'].'</p>
                                                    </div>
                                            ';
                                        }
                                    }
                                ?>
                            </div>
                            <br/>
                            <form class="addcomment" method="post">
                                <h5>Add a comment</h5>
                                <input type="hidden" name="username" id="username" value="'.$user.'">
                                <input type="hidden" name="storyid" id="storyid" value="'.$storyid.'">
                                <input type="hidden" name="chapterno" id="chapterno" value="3">
                                <input type="hidden" name="action" id="action" value="comment">
                                <textarea rows="10" cols="45" name="comment"></textarea>
                                <br/><br/>
                                <h5>Rate this chapter</h5>
                                <select name="rating" id="rating">
                                    <option value="" selected>Select a rating</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <br/><br/>
                                <br>
                                <input type="submit" value="comment">
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                            </div>
                        </div>
                    </div>
                    
                </div><!--/.panel-->
                
            </div><!--/.col-->
            
        </div> <!--row-->
        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </body>
</html>