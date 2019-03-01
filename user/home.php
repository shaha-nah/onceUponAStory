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
        <link rel="stylesheet" href="../css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../slick/slick.css">
        <link rel="stylesheet" type="text/css" href="../slick/slick-theme.css">
        <!-- <link href="../css/bootstrap.min.css" rel="stylesheet"> -->
        <style type="text/css">
            html, body {
            margin: 0;
            padding: 0;
            }

            * {
            box-sizing: border-box;
            }

            .slider {
                width: 95%;
                margin: 1px auto;
            }

            .slick-slide img {
            width: 100%;
            }

            .slick-prev:before,
            .slick-next:before {
            color: black;
            }


            .slick-slide {
            transition: all ease-in-out .3s;
            }

            .slick-active {
            }

            .slick-current {
            }
        </style>

        <!-- <script src="http://code.jquery.com/jquery-1.10.1.min.js" ></script> -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
        <script src="../js/ajax.js"></script>
        <script src="../js/jquery-1.11.1.min.js"></script>

        <script>
            $(document).ready(function(){
                $("div.addbutton form").on('click',function(event){

                    event.preventDefault();
                    var storyid=$(this).attr('id');

                    var form="div.addbutton form#";
                    var ss = form.concat(storyid);
                    var data = $(this).serialize();
                    $.ajax({
                        type:   "POST",
                        url:    "addToReadingList.php",
                        data:   data,
                        success: function(data) {
                            var add=("#add").concat(storyid);
                            $(add).val("Added");
                        },
                        error: function() {
                            alert("A problem occured");
                        }
                    })
                });
            });
        </script>

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
                });
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

            if (isset($_GET['referer'])){
                if ($_GET['referer'] == "preference"){
                    echo '<script language="javascript">';
                    echo 'alert("Welcome to Once Upon a Story")';
                    echo '</script>';
                }
            }

            // CONTINUE READING
            $readQuery = "  SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                            FROM    story, myreads
                            WHERE   story.storyid = myreads.storyid
                            AND     story.author <> '$user'
                            AND     myreads.username='$user'
                            AND     myreads.currentreads=1";

            $readResult = $conn->query($readQuery);
            $readRows=$readResult->rowCount();
            if ($readRows != 0){
                ?>
                    <a href="reads.php?type=currentreads"><h2>CONTINUE READING</h2></a>

                    <div class="container">
                        <section class="regular slider">
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
                                echo'    <div>
                                            <div class="story">
                                                <div class="details">
                                                    <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                    <h3>'.$row['storyname'].'</h3>
                                                </div>
                                                <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                                <div class="addbutton">
                                                    <a href="reader.php?storyid='.$row['storyid'].'">Continue Reading</a>
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
                                                    echo '
                                                </div>
                                            </div>
                                        </div>
                                ';
                            }?>
                        </section>
                    </div>
                <?php
            }

            //RECOMMENDED
            $preferenceQuery = "    SELECT  preference
                                    FROM    user
                                    WHERE   username='$user'";
            $preferenceResult=$conn->query($preferenceQuery);
            $preferenceRow=$preferenceResult->fetch();
            $preference=$preferenceRow['preference'];
            
            $recommendedQuery = "   SELECT      story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                    FROM        story
                                    WHERE       story.author <> '$user'
                                    AND         story.storyid NOT   IN(     SELECT  storyid
                                                                            FROM    myreads
                                                                            WHERE   username='$user')
                                    AND         story.storyid       IN(     SELECT  storyid
                                                                            FROM    chapter)
                                    AND         story.category      IN(     $preference)
                                    ORDER BY    RAND()
                                    LIMIT       10";
            $recommendedResult = $conn->query($recommendedQuery);
            $recommendedRows=$recommendedResult->rowCount();
            if ($recommendedRows != 0){
                ?>
                    <h2>RECOMMENDED</h2>

                    <div class="container">
                        <section class="regular slider">
                            <?php while ($row=$recommendedResult->fetch()){
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
                                echo'   <div>
                                            <div class="story">
                                                <div class="details">
                                                    <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                    <h3>'.$row['storyname'].'</h3>
                                                </div>
                                                <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                                <div class="addbutton">
                                                    <form id="'.$row['storyid'].'">
                                                        <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                        <input type="hidden" name="username" value="'.$user.'">
                                                        <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                            </div>
                                        </div>';
                            }?>
                        </section>
                    </div>
                <?php
            }

            //FOLLOWED USER
            $followingQuery = " SELECT      story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                FROM        story
                                WHERE       story.author <> '$user'
                                AND         story.storyid NOT   IN( SELECT  storyid
                                                                    FROM    myreads
                                                                    WHERE   username='$user')
                                AND         story.storyid       IN( SELECT  storyid
                                                                    FROM    chapter)
                                AND         story.author        IN( SELECT  following
                                                                    FROM    follow
                                                                    WHERE   follower='$user')
                                ORDER BY    RAND()
                                LIMIT       10";
            $followingResult = $conn->query($followingQuery);
            $followingRows=$followingResult->rowCount();
            if ($followingRows != 0){
                ?>
                    <h2>FROM USERS YOU FOLLOW</h2>

                    <div class="container">
                        <section class="regular slider">
                            <?php while ($row=$followingResult->fetch()){
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
                                echo'   <div>
                                            <div class="story">
                                                <div class="details">
                                                    <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                    <h3>'.$row['storyname'].'</h3>
                                                </div>
                                                <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                                <div class="addbutton">
                                                    <form id="'.$row['storyid'].'">
                                                        <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                        <input type="hidden" name="username" value="'.$user.'">
                                                        <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                            </div>
                                        </div>';
                            }?>
                        </section>
                    </div>
                <?php
            }

            // TOP RATED STORIES
            $topQuery = "   SELECT      story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture, ROUND(AVG(review.rating), 0) AS rating
                            FROM        story, review
                            WHERE       story.author <> '$user'
                            AND         story.storyid NOT IN(   SELECT  storyid
                                                                FROM    myreads
                                                                WHERE   username='$user')
                            AND         story.storyid     IN(   SELECT  storyid
                                                                FROM    chapter)
                            AND         story.storyid=review.storyid
                            GROUP BY    story.storyid
                            ORDER BY    rating DESC
                            LIMIT       10";
            $topResult = $conn->query($topQuery);
            $topRows=$topResult->rowCount();
            if ($topRows != 0){
                ?>
                    <h2>TOP RATED STORIES</h2>

                    <div class="container">
                        <section class="regular slider">
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

                                echo'   <div>
                                            <div class="story">
                                                <div class="details">
                                                    <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                    <h3>'.$row['storyname'].'</h3>
                                                </div>
                                                <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                                <div class="addbutton">
                                                    <form id="'.$row['storyid'].'">
                                                        <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                        <input type="hidden" name="username" value="'.$user.'">
                                                        <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                            </div>
                                        </div>';
                            }?>
                        </section>
                    </div>


                <?php
            }

            //EXPLORE
            $exploreQuery = "   SELECT      story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                FROM        story
                                WHERE       story.author <> '$user'
                                AND         story.storyid NOT IN(   SELECT  storyid
                                                                    FROM    myreads
                                                                    WHERE   username='$user')
                                AND         story.storyid     IN(   SELECT  storyid
                                                                    FROM    chapter)
                                ORDER BY    datecreated DESC, RAND()
                                LIMIT       10";
            $exploreResult = $conn->query($exploreQuery);
            $exploreRows=$exploreResult->rowCount();
            if ($exploreRows != 0){
                ?>
                    <h2>EXPLORE</h2>

                    <div class="container">
                        <section class="regular slider">
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
                                echo'   <div>
                                            <div class="story">
                                                <div class="details">
                                                    <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                    <h3>'.$row['storyname'].'</h3>
                                                </div>
                                                <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                                <div class="addbutton">
                                                    <form id="'.$row['storyid'].'">
                                                        <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                        <input type="hidden" name="username" value="'.$user.'">
                                                        <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                            </div>
                                        </div>';
                            }?>
                        </section>
                    </div>
                <?php
            }
        ?>
        <div class="centered" id="showStory" style="display:none;">
            <button type="button" id="hideStory" style="float:right;">x</button>
            <div id="storyDetails" style="background-color:white;"></div>
        </div>
        <!-- <script src="../js/modal.js"></script> -->
        <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
        <script src="../slick/slick.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript">
            $(document).on('ready', function() {
                $(".regular").slick({
                    infinite: true,
                    slidesToShow: 6,
                    slidesToScroll: 2
                });
            });
        </script>
        <div class="centered" id="showStory" style="display:none;">
            <button type="button" id="hideStory" style="float:right;">x</button>
            <div id="storyDetails" style="background-color:white;">
            </div>
        </div>
    </body>
</html>
