<!DOCTYPE html>
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
        <title>Home</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/story.css">
        <link rel="stylesheet" href="../css/user/modal.css">
        <link rel="stylesheet" href="../css/font-awesome.min.css">

        <script src="http://code.jquery.com/jquery-1.10.1.min.js" ></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="../js/user/modal.js"></script>
        <!-- <script src="../js/ajax.js"></script>
        <script src="../js/jquery-1.11.1.min.js"></script> -->
        <script>
            $(document).ready(function(){
                $("button.viewStory").click(function(){
                    var story=$(this).attr('id');
                    
                    $.post("viewStory.php",
                    {storyid:story}, function(result, status){
                        if (status=="success"){
                            $("div#showStory #storyDetails").html(result);
                            $("div#showStory").fadeToggle();
                        }
                    })
                });

                $("button#hideStory").click(function(){
                    $("div#showStory #storyDetails").html("");
                    $("div#showStory").fadeToggle();
                })
            });
        </script>
        <style>
            div.centered{
                width: 800px;
                height: 400px;
                position:fixed; 
                /* top: calc(50% - 25px); // half of width
                left: calc(50% - 50px); // half of height */
                z-index:41;
                top:0;
                left:0;
            }
        </style>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
            $activemenu="home";
 
            include('../includes/menu.php');

            require_once "../includes/db_connect.php";

            
            // CONTINUE READING
            $readQuery = "  SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                            FROM    story, myreads
                            WHERE   story.storyid = myreads.storyid
                            AND     story.author <> '$user'
                            AND     myreads.username='$user'
                            AND     myreads.currentreads=1
                            LIMIT 6";

            $readResult = $conn->query($readQuery);
            $readRows=$readResult->rowCount();
            if ($readRows != 0){
                ?>
                    <a href="reads.php?type=currentreads"><h2>CONTINUE READING</h2></a>

                    <div class="container">
                        <?php while ($row=$readResult->fetch()){
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
                            echo'   <div class="story">  
                                        <button class="viewStory" type="button" id="'.$row['storyid'].'" style="border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                        <h3>'.$row['storyname'].'</h3>
                                        <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                        <div class="stars">';
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

            // TOP RATED STORIES
            $topQuery = "   SELECT      story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture, avg(chapter.rating)
                            FROM        story, chapter
                            WHERE       story.storyid=chapter.storyid
                            AND         story.author <> '$user'
                            AND         story.storyid NOT IN(   SELECT  storyid
                                                                FROM    myreads
                                                                WHERE   username='$user')
                            GROUP BY    story.storyid
                            ORDER BY    AVG(chapter.rating) LIMIT 6";

            $topResult = $conn->query($topQuery);
            $topRows=$topResult->rowCount();
            if ($topRows != 0){
                ?>
                    <h2>TOP RATED STORIES</h2>

                    <div class="container">
                        <?php while ($row=$topResult->fetch()){
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

                            echo'   <div class="story">
                                        <a href="reader.php?storyid='.$row['storyid'].'&action=add"><img src="../images/'.$row['storypicture'].'"></a>
                                        <h3>'.$row['storyname'].'</h3>
                                        <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                        <div class="addbutton">
                                            <form id="'.$row['storyid'].'">
                                                <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                <input type="hidden" name="username" value="'.$user.'">
                                                <input type="hidden" name="action" value="addlist">
                                                <input type="submit" id="add" value="Add to Reading List" />
                                            </form>
                                        </div>

                                        <div class="stars">';
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

            //EXPLORE
            $exploreQuery = "   SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                FROM    story
                                WHERE   story.author <> '$user'
                                AND     story.storyid NOT IN(   SELECT  storyid
                                                                FROM    myreads
                                                                WHERE   username='$user')
                                ORDER BY RAND()
                                LIMIT 6";
            $exploreResult = $conn->query($exploreQuery);
            $exploreRows=$exploreResult->rowCount();
            if ($exploreRows != 0){
                ?>
                    <h2>EXPLORE</h2>

                    <div class="container">
                        <?php while ($row=$exploreResult->fetch()){
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
                            echo'   <div class="story">
                                        <a href="reader.php?storyid='.$row['storyid'].'&action=add"><img src="../images/'.$row['storypicture'].'"></a>
                                        <h3>'.$row['storyname'].'</h3>
                                        <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                        <div class="addbutton">
                                            <form id="addform">
                                                <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                <input type="hidden" name="username" value="'.$user.'">
                                                <input type="hidden" name="action" value="addlist">
                                                <input type="submit" id="add" value="Add to Reading List" />
                                            </form>
                                        </div>

                                        <div class="stars">';
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
        ?>
        <div class="centered" id="showStory" style="display:none;">
            <button type="button" id="hideStory" style="float:right;">x</button>
            <div id="storyDetails" style="background-color:white;">
            </div>
        </div>
    </body>
</html>

 