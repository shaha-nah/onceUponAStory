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
        <title>Discover</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/modal.css">
        <link rel="stylesheet" href="../css/story.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="http://code.jquery.com/jquery-1.10.1.min.js" ></script>
        <script>
            $(document).ready(function(){
                $("div.addbutton form").on('submit',function(event){
                    event.preventDefault();
                    var storyid=$(this).attr('id');
                    var form="div.addbutton form#";
                    var ss = form.concat(storyid);
                    var data = $(ss).serialize();
                    $.ajax({
                        type: "POST",
                        url: "action.php",
                        data: data
                    }).done(function(msg) {
                        var add=("#add").concat(storyid);
                        $(add).val("Added");
                    });
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
            $activemenu="discover";
            include('../includes/menu.php');

            require_once "../includes/db_connect.php";

            if (isset($_GET['action'])){
                if ($_GET['action'] == "addlist"){
                    $storyid=$_GET['storyid'];
                    $insertList="   INSERT INTO myreads(storyid, username, readinglist)
                                    VALUES      ($storyid, '$user', 1)";
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insertResult=$conn->exec($insertList);
                }
            }

            if (!isset($_GET['category'])){
                    $storyQuery = " SELECT  storyid, storyname, author, category, tags, storydescription, storypicture
                                FROM    story
                                WHERE   author <> '$user'
                                AND     story.storyid NOT IN(   SELECT  storyid
                                                                FROM    myreads
                                                                WHERE   username='$user')";
            }
            else{
                $cat=$_GET['category'];
                $storyQuery = " SELECT  storyid, storyname, author, category, tags, storydescription, storypicture
                            FROM    story
                            WHERE   author <> '$user'
                            AND     category='$cat'
                            AND     story.storyid NOT IN(   SELECT  storyid
                                                            FROM    myreads
                                                            WHERE   username='$user')";

            }
            $storyResult = $conn->query($storyQuery);
            $sRows=$storyResult->rowCount();
            if ($sRows != 0){
                if (isset($_GET['category'])){
                    $cat=$_GET['category'];

                }
                else{
                    $cat="All categories";
                }
                echo '<h2>'.$cat.'</h2>'; ?>

                <div class="container">
                    <?php while ($row=$storyResult->fetch()){
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
                                    <div class="details">
                                    <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                        <h3>'.$row['storyname'].'</h3>
                                    </div>
                                    <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                    <div class="addbutton">
                                        <form id="'.$row['storyid'].'">
                                            <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                            <input type="hidden" name="username" value="'.$user.'">
                                            <input type="hidden" name="action" value="addlist">
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
                                    </div>';
                    }?>
                </div>
            <?php
            }
            else{
                echo '<h3>You have read all the books</h3>';
            }
        ?>

        <script src="js/modal.js"></script>
        <div class="centered" id="showStory" style="display:none;">
            <button type="button" id="hideStory" style="float:right;">x</button>
            <div id="storyDetails" style="background-color:white;">
            </div>
        </div>
    </body>
</html>
