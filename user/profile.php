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
        <title>Profile</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
      	<link href="../css/font-awesome.min.css" rel="stylesheet">
      	<link href="../css/datepicker3.css" rel="stylesheet">
      	<!-- <link href="../css/styles.css" rel="stylesheet"> -->
        <link href="../css/profile.css" rel="stylesheet">
        <link href="../css/menu.css" rel="stylesheet">
        <link href="../css/aviewprofilestyle.css" rel="stylesheet">
        <link href="../css/story.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
        <style>
            ul#ginfo li:hover{background-color: #ddc;}
            ul.nav-user {
                margin-bottom: 0px;
                padding-top: 0px;
                display: inline;
                list-style-type: none;
                display: block;
                margin-block-end: 1em;
                margin-inline-start: 0px;
                margin-inline-end: 0px;
                padding-inline-start: 40px;
            }
            ul.nav, .nav-pills {
              background-color: inherit;
              border: none;
            }

            ul.nav, .nav-pills>li a {
              color: BLACK;
            }

            .nav-pills>li.active>a{
              color: #fff;
              /* background-color: gray; */
            }
            .nav-user>li {
                padding-top: 0px;
                float: left;
                position: relative;
                display: block;
                text-align: -webkit-match-parent;
            }
            .nav-user>li>a {
                position: relative;
                display: block;
                padding: 10px 15px;
            }
            .nav-user>li>em{
                color: black;
            }
        </style>
        <style>
            table{
                table-layout: fixed;
            }
            tr{
                border-bottom: 1px solid rgb(220,220,220) ;
            }
            td{
                padding-top: 5px;
                padding-bottom: 5px;
                vertical-align: middle;
                border-bottom: 1px solid rgb(220,220,220) ;
            }
            td.img{
                width: 5%;
                padding-right: 10px;

            }
            td.story{
                width: 20%;
            }
            td.datec{
                padding-left: 10px;
                width: 25%;
            }
            td.cat{
                padding-left: 10px;
                width: 25%;
            }
            td.chapter{
                width: 20%;
            }
            td.btn{
                width: 10%;
            }
            #cnt tr:hover {background-color: #ddd;}
            .btn{
                margin-right: 10px;
                float: right;
            }
            div.profilepicture{
                height: 400px;
                color: white;
                text-align: center;
                line-height: 25px;
                width: 100%;
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }

            ul.panel-body{
                color:black;
            }

            .col-sm-12, .col-lg-10{
                padding-left:0px;
                padding-right:0px;
            }

            div.centered{
                width: 800px;
                height: 400px;
                position:fixed;
                /* top: calc(50% - 25px); // half of width
                left: calc(50% - 50px); // half of height */
                z-index:41;
                top:90;
                left:0;
            }
        </style>
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
    </head>

    <body>
        <div class="col-sm-12 col-lg-11">
        <div class="row">
            <div class="col-md-12">
                <?php
                    $activemenu="user";
                    require_once "../includes/db_connect.php";
                    include('../includes/menu.php');

                    if (isset($_GET['profile'])){
                        $profile=$_GET['profile'];
                    }
                    else{
                        $profile=$user;
                    }

                    if(isset($_GET['action'])){
                        $storyid = $_GET['storyid'];
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

                    $uQuery = " SELECT  username, name, gender, dob, profiledescription,location, profilepicture, email, dateregistered
                                FROM    user
                                WHERE   username='$profile'";

                    $uResult = $conn->query($uQuery);

                    $row = $uResult->fetch();
                ?>

            </div> <!-- col md 12 -->
        </div><!-- row -->
            <!-- code to display profile -->
        <br/><br/><br/>
        <div class="row">
            <div class="col-lg-12" >
                <div class="panel panel-default">
                    <div class="row">
                        <div class = "panel-body" style = "padding-top:0px; padding-bottom:0px;" >
                            <div class="col-md-4" style = "background-color: #ddd; padding-top: 0px;">
                                <div class="profile-sidebar">
                                    <?php echo '<div class="profilepicture" style = "background-image:url(\'../images/'.$row['profilepicture'].'\')" >';?>
                                </div>
                            </div>
                            <div class="panel-heading" style = "background-color: #ddd;  border-bottom: 1px solid black;">
                                <?php echo '<h4 style = "text-align:center;"><b>'.$row['name'].'</b></h4>'; ?>
                            </div>
                            <div class = "row">
                                <div class="hero-info">
                                    <div class="panel-body">
                                        <?php echo '<p>'.$row['profiledescription'].'</p>'; ?>
                                        <div class="panel-heading" style = "padding-left:0px; background-color: #ddd; border-bottom: 1px solid black;">
                                            <h4>General Info</h4>
                                        </div>
                                        <div class="panel-body" style = "padding-left:0px;">
                                            <ul>
                                                <?php echo '<li><span>Username</span>'.$row['username'].'</li>'; ?>
                                                <?php echo '<li><span>Date of Birth</span>'.$row['dob'].'</li>'; ?>
                                                <?php echo '<li><span>Address</span>'.$row['location'].'</li>'; ?>
                                                <?php echo '<li><span>E-mail</span>'.$row['email'].'</li>'; ?>
                                                <?php echo '<li><span>Gender</span>'.$row['gender'].'</li>'; ?>
                                                <?php echo '<li><span>User since</span>'.$row['dateregistered'].'</li>'; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div><!-- hero info -->
                            </div>
                        </div> <!--col-lg-4-->
                        <div style = "padding-left:0px;" class="col-md-8">
                            <!-- <div class="row" style = "padding-top:0px;"> -->
                            <div class="panel panel-default" style = "-webkit-box-shadow: none;box-shadow: none;">
                                <div class="panel-body tabs">
                                    <ul class="nav nav-pills">
                                        <li class="active"><a href="#pilltab1" data-toggle="tab">Stories</a></li>
                                        <li><a href="#pilltab2" data-toggle="tab">Archive</a></li>
                                        <li><a href="#pilltab3" data-toggle="tab">Reads</a></li>
                                        <li><a href="#pilltab4" data-toggle="tab">Reading List</a></li>
                                        <li><a href="#pilltab5" data-toggle="tab">Follower</a></li>
                                        <li><a href="#pilltab6" data-toggle="tab">Following</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade in active" id="pilltab1">
                                            <?php
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
                                                        <div class="container_profile">
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
                                                                                WHERE   storyid=".$row['storyid'];

                                                                $readerResult=$conn->query($readerQuery);
                                                                $readerCount=$readerResult->rowCount();
                                                                echo'   <div class="story">
                                                                            <div class="details">
                                                                                <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                                                <h3>'.$row['storyname'].'</h3>
                                                                            </div>';
                                                                            if ($profile != $user){
                                                                    echo'       <div class="addbutton">
                                                                                    <form id="'.$row['storyid'].'">
                                                                                        <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                                                        <input type="hidden" name="username" value="'.$user.'">
                                                                                        <input type="hidden" name="action" value="addlist">
                                                                                        <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                                                                WHERE   storyid=".$row['storyid'];
                                                                $readerResult=$conn->query($readerQuery);
                                                                $readerCount=$readerResult->rowCount();
                                                                echo'   <div class="story">
                                                                            <div class="details">
                                                                                <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                                                <h3>'.$row['storyname'].'</h3>
                                                                            </div>';
                                                                            if ($profile != $user){
                                                                    echo'       <div class="addbutton">
                                                                                    <form id="'.$row['storyid'].'">
                                                                                        <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                                                        <input type="hidden" name="username" value="'.$user.'">
                                                                                        <input type="hidden" name="action" value="addlist">
                                                                                        <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                        </div>
                                        <div class="tab-pane fade" id="pilltab2">
                                            <br/>
                                            <?php
                                                $sQuery = " SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                                            FROM    story, myreads
                                                            WHERE   myreads.username='$profile'
                                                            AND     story.storyid=myreads.storyid
                                                            AND     myreads.archive=1";
                                                $sResult = $conn->query($sQuery);
                                                $sRows = $sResult->rowCount();
                                                if ($sRows == 0){
                                                    if ($profile==$user){
                                                        echo '<h4>You have not completed any books</h4>';
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
                                                                        <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                                        <h3>'.$row['storyname'].'</h3>';
                                                                        if ($profile == $user){
                                                                            echo'   <a href="profile.php?type=archive&profile='.$profile.'&action=remove&storyid='.$storyid.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                                                                        }
                                                                        else{

                                                                            $readQuery="    SELECT  *
                                                                                            FROM    myreads
                                                                                            WHERE   username='$user'
                                                                                            AND     storyid=$storyid";
                                                                            $readResults=$conn->query($readQuery);
                                                                            $readCount=$readResults->rowCount();
                                                                            if ($readCount==0){
                                                                echo'           <div class="addbutton">
                                                                                    <form id="'.$row['storyid'].'">
                                                                                        <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                                                        <input type="hidden" name="username" value="'.$user.'">
                                                                                        <input type="hidden" name="action" value="addlist">
                                                                                        <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                                                        echo '</div>
                                                                    </div>';
                                                        }?>
                                                    </div>
                                                <?php
                                                }
                                            ?>
                                        </div>
                                        <div class="tab-pane fade" id="pilltab3">
                                            <br/>
                                            <?php
                                                $sQuery = " SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                                            FROM    story, myreads
                                                            WHERE   myreads.username='$profile'
                                                            AND     story.storyid=myreads.storyid
                                                            AND     myreads.currentreads=1";
                                                $sResult = $conn->query($sQuery);
                                                $sRows = $sResult->rowCount();
                                                if ($sRows == 0){
                                                    if ($profile==$user){
                                                        echo '<h4>You are not reading any books</h4>';
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
                                                                echo'   <div class="container_profile">
                                                                            <div>
                                                                                <div class="story">
                                                                                    <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                                                    <h3>'.$row['storyname'].'</h3>';
                                                                                    if ($profile == $user){
                                                                                        echo'   <a href="profile.php?profile='.$profile.'&action=archive&storyid='.$storyid.'"><i class="fa fa-archive"></i></a>';
                                                                                    }
                                                                                    else{

                                                                                        $readQuery="    SELECT  *
                                                                                                        FROM    myreads
                                                                                                        WHERE   username='$user'
                                                                                                        AND     storyid=$storyid";
                                                                                        $readResults=$conn->query($readQuery);
                                                                                        $readCount=$readResults->rowCount();
                                                                                        if ($readCount==0){
                                                                            echo'           <div class="addbutton">
                                                                                                <form id="'.$row['storyid'].'">
                                                                                                    <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                                                                    <input type="hidden" name="username" value="'.$user.'">
                                                                                                    <input type="hidden" name="action" value="addlist">
                                                                                                    <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                                                                    echo '</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>';
                                                        }?>
                                                    </div>
                                                <?php
                                                }
                                            ?>
                                        </div>
                                        <div class="tab-pane fade" id="pilltab4">
                                            <br/>
                                            <?php
                                                $sQuery = " SELECT  story.storyid, story.storyname, story.author, story.category, story.tags, story.storydescription, story.storypicture
                                                            FROM    story, myreads
                                                            WHERE   myreads.username='$profile'
                                                            AND     story.storyid=myreads.storyid
                                                            AND     myreads.readinglist=1";
                                                $sResult = $conn->query($sQuery);
                                                $sRows = $sResult->rowCount();
                                                if ($sRows == 0){
                                                    if ($profile==$user){
                                                        echo '<h4>No stories found</h4>';
                                                    }
                                                    else{
                                                        echo "<h3>No stories found</h3>";
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
                                                                            <button class="viewStory" type="button" id="'.$row['storyid'].'" onmouseover="" style="cursor: pointer;border:none;"><img src="../images/'.$row['storypicture'].'"></button>
                                                                            <h3>'.$row['storyname'].'</h3>';
                                                                            if ($profile == $user){
                                                                                echo'   <a href="profile.php?type=readinglist&profile='.$profile.'&action=remove&storyid='.$storyid.'"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                                                                            }
                                                                            else{

                                                                                $readQuery="    SELECT  *
                                                                                                FROM    myreads
                                                                                                WHERE   username='$user'
                                                                                                AND     storyid=$storyid";
                                                                                $readResults=$conn->query($readQuery);
                                                                                $readCount=$readResults->rowCount();
                                                                                if ($readCount==0){
                                                                    echo'           <div class="addbutton">
                                                                                        <form id="'.$row['storyid'].'">
                                                                                            <input type="hidden" name="storyid" value="'.$row['storyid'].'">
                                                                                            <input type="hidden" name="username" value="'.$user.'">
                                                                                            <input type="hidden" name="action" value="addlist">
                                                                                            <input type="submit" id="add'.$row['storyid'].'" class="add" value="Add to Reading List" />
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
                                                                            echo '</div>
                                                                        </div>';
                                                        }?>
                                                    </div>
                                                <?php
                                                }
                                            ?>
                                        </div>
                                        <div class="tab-pane fade" id="pilltab5">
                                            <br/>
                                            <?php
                                                $followQuery="  SELECT  follower
                                                                FROM    follow
                                                                WHERE   following='$profile'";
                                                $followResult=$conn->query($followQuery);
                                                $followCount=$followResult->rowCount();
                                                if ($followCount == 0){
                                                    echo '<h4>No users found</h4>';
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
                                            ?>
                                        </div>
                                        <div class="tab-pane fade" id="pilltab6">
                                            <br/>
                                            <?php
                                                    $followQuery="  SELECT  following
                                                                    FROM    follow
                                                                    WHERE   follower='$profile'";
                                                    $followResult=$conn->query($followQuery);
                                                    $followCount=$followResult->rowCount();
                                                    if ($followCount == 0){
                                                        echo '<h4>No users found</h4>';
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
                                            ?>
                                        </div>
                                    </div>
                                </div><!--/.panel-->
                            </div><!-- col lg 8-->
                        </div><!-- panel body -->
                    </div><!--row ki fr background vin blanc-->
                </div> <!-- container fluid -->
            </div> <!-- col lg 12 -->
        </div> <!-- row display profile-->
        </div>
        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <div class="centered" id="showStory" style="display:none;">
            <button type="button" id="hideStory" style="float:right;">x</button>
            <div id="storyDetails" style="background-color:white;">
            </div>
        </div>
    </body>
</html>
