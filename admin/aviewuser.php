<?php
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: alogin.php");
    }
    else{
        $admin=$_SESSION['username'];
    }
?>

<html>
    <head>
        <title>User Details</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
      	<link href="../css/font-awesome.min.css" rel="stylesheet">
      	<link href="../css/datepicker3.css" rel="stylesheet">
      	<link href="../css/styles.css" rel="stylesheet">
        <link href="../css/profile.css" rel="stylesheet">
        <link href="../css/aviewprofilestyle.css" rel="stylesheet">
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

        </style>
    </head>

    <body>
      <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
    			<ol class="breadcrumb">
    				<li>
              <a href="auser.php">
    					  <em class="fa fa-users"></em>
    				  </a>
            </li>
    				<li class="active">Reported Users</li>
    			</ol>
    		</div>
        <div class="row">
			    <div class="col-lg-12">
				    <h1 class="page-header">User Profile</h1>
			    </div>
		    </div><!--/.row-->

        <div class="row">
          <div class="col-md-12">

              <?php
                  $activemenu="user";
                  require_once "../includes/db_connect.php";
                  include('../includes/amenu.php');


                  if (isset($_GET['profile'])){
                      $profile=$_GET['profile'];
                      echo '<div class="panel panel-default">
                                <div class="panel panel-heading">
                                  Quick Actions
                                  <button type="button" class="btn btn-md btn-success">Reviewed</button>
                                  <a href = "auser.php?action=ban&profile='.$profile.'"> <button type="button" class="btn btn-md btn-danger">Ban User</button></a>
                                </div>

                            </div>';
                  }


                    // APROFILE HEADER CODE




                  $uQuery = " SELECT  username, name, gender, dob, profiledescription,location, profilepicture, email, dateregistered
                              FROM    user
                              WHERE   username='$profile'";

                  $uResult = $conn->query($uQuery);

                  $row = $uResult->fetch();
                ?>
          </div> <!-- col md 12 -->
        </div><!-- row -->
        <!-- code to display profile -->
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
                              <ul class="nav nav-pills" style: "float: right;">
                                <li class="active"><a href="#pilltab1" data-toggle="tab">Stories</a></li>
                                <li><a href="#pilltab2" data-toggle="tab">Comments</a></li>
                                <li><a href="#pilltab3" data-toggle="tab">Follower</a></li>
                                <li><a href="#pilltab4" data-toggle="tab">Following</a></li>
                              </ul>
                              <div class="tab-content">
                                <div class="tab-pane fade in active" id="pilltab1">
                                  <?php
                                    $storyQuery = " SELECT  storyid, storyname, datecreated, category, storypicture
                                                    FROM    story
                                                    WHERE   author='$profile'";

                                    $storyResult = $conn->query($storyQuery);
                                    if ($storyResult -> rowCount() == 0 ){
                                      echo '<p>No stories</p>';
                                    }else{
                                      echo "<table><tr><th></th><th>Story Name</th><th>Date Created</th><th>Category</th><th></th></tr>";
                                      while ($srow = $storyResult->fetch()) {
                                        echo "<tr>";
                                          echo "<td class = 'img'><figure><a href='aviewcontent.php?storyid=".$srow["storyid"]."'><img src='../images/".$srow['storypicture']." 'style ='width:30px; height:30px; padding:0px; border-radius: 20%;'></figure></a></td>";
                                          echo "<td class = 'story'><a href='aviewcontent.php?storyid=".$srow["storyid"]."'>" . $srow['storyname'] . "</a></td>";
                                          echo "<td class = 'datec'>" . $srow['datecreated'] . "</td>";
                                          echo "<td class = 'cat'>" . $srow['category'] . "</td>";
                                          echo "<td class = 'btn'><button type='button' class='btn btn-sm btn-danger'>Censor</button></td>";
                                          // echo "<td><figure><img src='images/". $srow['house_url']. "' width=100px> <figcaption>".  $srow['address'] ."</figcaption></figure></td>";
                                          // echo "<td><button class='viewReviews' type='button' id='". $srow['house_id'] . "'>Click here to view all reviews for this house</button></td>";
                                        echo "</tr>";
                                       }//end while
                                      echo "</table>";
                                    }

                                  ?>
                                </div>
                                <div class="tab-pane fade" id="pilltab2">
                                  <h4>Tab 2</h4>
                                  <p>Comment</p>
                                  <?php
                                    $storyQuery = " SELECT DISTINCT  story.storyid, story.storypicture, story.storyname , chapter.chaptername, review.commenttext
                                                    FROM review, story, chapter
                                                    WHERE review.username = 'chrissy'
                                                    AND review.storyid=story.storyid
                                                    AND review.chapterno = chapter.chapterno;";
                                                    //echo $storyQuery;

                                    $storyResult = $conn->query($storyQuery);
                                    if ($storyResult -> rowCount() == 0 ){
                                      echo '<p>No stories</p>';
                                    }else{
                                      echo "<table><tr><th></th><th>Story Name</th><th>Chapter Name</th><th>Comment</th><th</th></tr>";
                                      while ($srow = $storyResult->fetch()) {
                                        echo "<a href='aviewcontent.php?storyid=".$srow["storyid"]."'><tr>";
                                          echo "<td class = 'img'><figure><a href='aviewcontent.php?storyid=".$srow["storyid"]."'><img src='../images/".$srow['storypicture']." 'style ='width:30px; height:30px; padding:0px; border-radius: 20%;'></figure></a></td>";
                                          echo "<td class = 'story'><a href='aviewcontent.php?storyid=".$srow["storyid"]."'>" . $srow['storyname'] . "</a></td>";
                                          echo "<td class = 'chapter'>" . $srow['chaptername'] . "</td>";
                                          echo "<td class = 'cat'>" . $srow['commenttext'] . "</td>";
                                          echo "<td class = 'btn'><button type='button' class='btn btn-sm btn-danger'>Censor</button></td>";
                                          // echo "<td><figure><img src='images/". $srow['house_url']. "' width=100px> <figcaption>".  $srow['address'] ."</figcaption></figure></td>";
                                          // echo "<td><button class='viewReviews' type='button' id='". $srow['house_id'] . "'>Click here to view all reviews for this house</button></td>";
                                        echo "</tr></a>";
                                       }//end while
                                      echo "</table>";
                                    }

                                  ?>
                                </div>
                                <div class="tab-pane fade" id="pilltab3">
                                  <h4>Tab 3</h4>
                                  <p>Follower</p>
                                  <?php
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
                                        echo ' <a href="profile.php?profile='.$username.'">'.$username.'</a>';
                                      }
                                  }
                                  ?>
                                </div>
                                <div class="tab-pane fade" id="pilltab4">
                                  <h4>Tab 4</h4>
                                  <p>Following</p>
                                  <?php
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
                                          echo '<a href="profile.php?profile='.$username.'">'.$username.'</a>';
                                      }
                                  }
                                  ?>
                                </div>
                              </div>
                            </div>
                          </div><!--/.panel-->
                        <!-- </div> row menu -->

                      </div><!-- col lg 8-->
                    </div><!-- panel body -->
                  </div><!--row ki fr background vin blanc-->
            </div> <!-- container fluid -->
          </div> <!-- col lg 12 -->
        </div> <!-- row display profile-->



        <div class="row"><!--  tab row -->
          <div class="col-lg-12">

          </div><!-- /.col-->
        </div><!-- /.row -->
      </div> <!-- col md 12 -->

      <script src="../js/jquery-1.11.1.min.js"></script>
      <script src="../js/bootstrap.min.js"></script>
    </body>
</html>