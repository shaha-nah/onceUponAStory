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
    if (isset($_POST)){
        require_once "../includes/db_connect.php";
        $storyid=$_POST['storyid'];

        $storyQuery="   SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                        FROM    story
                        WHERE   storyid = $storyid";
        $storyResult=$conn->query($storyQuery);
        $storyRow=$storyResult->fetch();

        $readQuery="    SELECT  *
                        FROM    myreads
                        WHERE   storyid=$storyid
                        AND     username='$user'";  
        $readResult=$conn->query($readQuery);
        $readCount=$readResult->rowCount();


        // echo $storyRow['storypicture'];
        // echo $storyRow['storyname'];
        // echo $storyRow['storydescription'];
        // echo $storyRow['author'];
        // echo $storyRow['category'];
        // echo $storyRow['tags'];

        echo'   <div class="story">
                    <p><img src="../images/'.$storyRow['storypicture'].'" style="width:200px; height:300px;"></p>
                    <h1>'.$storyRow['storyname'].'</h1>
                    <h3>'.$storyRow['author'].'</h3>
                    <h3>'.$storyRow['category'].'</h3>
                    <h3>'.$storyRow['tags'].'</h3>
                    <h3>'.$storyRow['storydescription'].'</h3>
        ';
        if ($storyRow['author'] != $user){
            if ($readCount == 0){
                echo'   <div class="addbutton">
                            <a href="reader.php?storyid='.$storyid.'&action=add">Start Reading</a>
                        </div>
                    </div>
                ';
            }
            else{
                echo'   <div class="addbutton">
                            <a href="reader.php?storyid='.$storyid.'">Continue Reading</a>
                        </div>
                    </div>
                ';
            }
        }
    }
?>

<!-- <html>
    <head>
        <title>Story</title>
        <link rel="stylesheet" href="../css/mystyle.css">
        <link rel="stylesheet" href="../css/menu.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
            // $activemenu="";
            // include('../includes/menu.php');
            // require_once "../includes/db_connect.php";

            // if (isset($_GET['storyid'])){
            //     $storyid=$_GET['storyid'];
            // }

            // if (isset($_GET['action'])){
            //     if ($_GET['action'] == "addlist"){
            //         $storyid=$_GET['storyid'];
            //         $insertList="   INSERT INTO myreads(storyid, username, readinglist)
            //                         VALUES      ($storyid, '$user', 1)";
            //         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //         $insertResult=$conn->exec($insertList);
            //         if ($insertResult){
            //             header("Location:home.php");
            //         }
            //     }

            //     if ($_GET['action'] == "add"){
            //       $action="add";
            //     }
            //     else{
            //       $action="";
            //     }
            // }
            // else{
            //   $action="";
            // }

            // $storyQuery="   SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture, story.datecreated
            //                 FROM    story
            //                 WHERE   story.storyid=$storyid";
            // $storyResult=$conn->query($storyQuery);
            // $storyRow=$storyResult->fetch();

            // $chapterQuery=" SELECT  chapterno, chaptername
            //                 FROM    chapter
            //                 WHERE   storyid=$storyid";
            // $chapterResult=$conn->query($chapterQuery);

            // echo'
            //     <div class="box">
            //         <div class="story">
            //             <p><img src="../images/'.$storyRow['storypicture'].'" style="width:200px; height:300px;"></p>
            //             <h2>'.$storyRow['storyname'].'</h2>
            //             <h3>'.$storyRow['author'].'</h3>
            //             <h3>'.$storyRow['datecreated'].'</h3>
            //             <h3>'.$storyRow['category'].'</h3>
            //             <h3>'.$storyRow['tags'].'</h3>
            //             <h3>'.$storyRow['storydescription'].'</h3>
            //         </div>
            //         <div class="chapter">
            //             <h2>Chapters</h2>
            //             ';
            //             while ($chapterRow=$chapterResult->fetch()){
            //                 echo'
            //                     <p><a href="reader.php?storyid='.$storyRow['storyid'].'&chapterno='.$chapterRow['chapterno'].'">'.$chapterRow['chaptername'].'</a></p>
            //                 ';
            //             }
            //     echo'</div>
            //     </div>
            //     <div class="addbutton">
            //         <form id=add>
            //             <a href="viewStory.php?storyid='.$storyRow['storyid'].'&action=addlist">Add to reading list</a>
            //         </form>
            //     </div>
            //     <div class="addbutton">
            //         <form id=add>
            //             <a href="reader.php?storyid='.$storyRow['storyid'].'&action='.$action.'">Read</a>
            //         </form>
            //     </div>
            // ';
        ?>
    </body>
</html> -->
