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
        <title></title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <link rel="stylesheet" href="../css/story.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

            if (isset($_GET['action'])){
                if ($_GET['action'] == "addlist"){
                    $storyid=$_GET['storyid'];
                    $insertList="   INSERT INTO myreads(storyid, username, readinglist)
                                    VALUES      ($storyid, '$user', 1)";
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insertResult=$conn->exec($insertList);
                }
            }

            $input=$_GET['input'];

            $storyQuery="   SELECT      story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                            FROM        story, chapter
                            WHERE       story.storyid=chapter.storyid
                            AND         story.author <> '$user'
                            AND         story.storyname LIKE '%$input%'";
            $queryResult=$conn->query($storyQuery);
            $storyCount=$queryResult->rowCount();

            if ($storyCount == 0){
                echo 'No stories found';
            }
            else{ ?>
                <div class="container">
                        <?php
                        while ($row=$queryResult->fetch()){
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
                                        <a href="viewStory.php?storyid='.$row['storyid'].'&action=add"><img src="../images/'.$row['storypicture'].'"></a>
                                        <h3>'.$row['storyname'].'</h3>
                                        <h4><a href="profile.php?profile='.$row['author'].'">By '.$row['author'].'</a></h4>
                                        <h4>'.$row['storydescription'].'</h4>
                                        <div class="addbutton">
                                            <form id=add>
                                                <a href="search.php?storyid='.$row['storyid'].'&action=addlist">Add to reading list</a>
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
    </body>
</html>
