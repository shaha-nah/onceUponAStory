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
    <title>View Content</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
  	<link href="../css/font-awesome.min.css" rel="stylesheet">
  	<link href="../css/datepicker3.css" rel="stylesheet">
  	<link href="../css/styles.css" rel="stylesheet">
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
      height: 400%;
      background: #f1f4f7;
    }

    </style>
  </head>

  <body>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
      <!-- <div class="row">
        <ol class="breadcrumb">
          <li>
            <a href="#">
              <em class="fa fa-bar-chart"></em>
            </a>
          </li>
          <li class="active">Reported Contents</li>
        </ol>
      </div>
      <div class="row">
  			<div class="col-lg-12">
  				<h1 class="page-header">Story Name</h1>
  			</div>
  		</div> -->

      <?php
        // is set POST
        if (isset($_GET['storyid'])){
            $storyidReceived=$_GET['storyid'];
        }
        if (isset($_GET['chapterno'])){
            $chapternoReceived=$_GET['chapterno'];
        }
        else{
          $chapternoReceived=1;
        }
        $activemenu = "content";
        require_once "../includes/db_connect.php";
        include('../includes/amenu.php');

        $storyQuery = " SELECT  storyname, storydescription
                        FROM    story
                        WHERE   storyid = $storyidReceived;";
        $storyResult =  $conn->query($storyQuery);

        $storyrow = $storyResult->fetch();
        $storyname = $storyrow['storyname'];
        $storydescription = $storyrow['storydescription'];

        $chapterQuery = " SELECT  chapterno, chaptername, story
                          FROM    chapter
                          WHERE   storyid = $storyidReceived;";
        $chapterResult =  $conn->query($chapterQuery);
        $chapterStory = $conn->query($chapterQuery);

        $chaptercount=$chapterResult->rowCount();

      ?>
      <div class="row">
			  <div class="col-lg-12">
				  <?php echo '<h2>'.$storyname.'</h2>';?>
          <?php echo '<p>'.$storydescription.'</p>'?>
          <p><p>
			  </div>
        <div class="col-lg-12">
				<div class="panel panel-default">
          <!-- <?php echo $chaptercount;?> -->
					<div class="panel-body tabs" style="padding-left:0px;">
            <div class="col-md-4" style="width:10%;padding-left:0px;padding-right:0px;min-width:125px;">
						<ul class="nav nav-tabs" style = "display:block;padding-right:0px;padding-left:0px;">
              <?php
                while ($chapterrow = $chapterResult->fetch()){
                  if ($chapterrow['chapterno'] == $chapternoReceived){
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
						<div class="tab-content">
              <?php
                while ($chapterrow = $chapterStory->fetch()){
                  if ($chapterrow['chapterno'] == $chapternoReceived){
                    echo '  <div class="tab-pane fade in active" id="tab'.$chapterrow['chapterno'].'">
      								        <h4>'.$chapterrow['chaptername'].'</h4>
      								        <p>'.$chapterrow['story'].'</p>
      							        </div>'
                    ;
                  }
                  else{
                    echo '  <div class="tab-pane fade" id="tab'.$chapterrow['chapterno'].'">
      								        <h4>'.$chapterrow['chaptername'].'</h4>
      								        <p>'.$chapterrow['story'].'</p>
      							        </div>';
                  }
                }
              ?>
						</div>
            </div>
					</div>
				</div><!--/.panel-->
			</div><!--/.col-->
      </div> <!--row-->
    </div> <!-- main column div col sm 9-->
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script>

    </script>
  </body>
</html>