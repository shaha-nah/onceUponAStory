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
    <title>Reported Contents</title>
      <link href="../css/bootstrap.min.css" rel="stylesheet">
    	<link href="../css/font-awesome.min.css" rel="stylesheet">
    	<link href="../css/datepicker3.css" rel="stylesheet">
    	<link href="../css/styles.css" rel="stylesheet">
      <!-- <link href="../css/table.css" rel="stylesheet"> -->
      <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
          vertical-align: top;
          border-bottom: 1px solid rgb(220,220,220) ;
        }
        td.sn{
          width: 20%;
        }
        td.cn{
          padding-left: 10px;
          width: 25%;
        }
        td.fr{
          padding-left: 10px;
          width: 55%;
        }
        #cnt tr:hover {background-color: #ddd;}

      </style>
  </head>

  <body>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
      <div class="row">
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
  				<h1 class="page-header">Reported Contents</h1>
  			</div>
  		</div>
      <div class="panel panel-container">
        <?php
          $activemenu="content";
          require_once "../includes/db_connect.php";
          include('../includes/amenu.php');        
          if (isset($_GET['action'])){
            $action=$_GET['action'];
            if (isset($_GET['profile'])){
              $profile=$_GET['profile'];
              if ($action == "ban"){
                $aQuery = " UPDATE  user
                            SET     ban = 1
                            WHERE   user.username = '$profile'; ";
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $updateResult=$conn->exec($aQuery);
              }
            }
          }
          //Display list of flagged Contents
          $cQuery =  "  SELECT  story.storyid, story.storyname, chapter.chaptername, chapter.flagreason, chapter.chapterno
                        FROM    story, chapter
                        WHERE   chapter.flag = 1
                        AND     story.storyid = chapter.storyid";
          $cResult = $conn->query($cQuery);
          echo '<div class="panel-body">';
            echo '<table id = "cnt"><tr><th>Story Name</th><th style = "padding-left: 10px;">Chapter Name</th><th style = "padding-left: 10px;">Flag Reason</th><th></th></tr>';
              while ($row = $cResult->fetch()){
                echo '<tr>';
                  echo '<td class = "sn"><a href="aviewcontent.php?storyid=' .$row['storyid']. '&chapterno=' .$row['chapterno']. '">'.$row['storyname'].'</a> </td>';
                  echo '<td class="cn">' . $row['chaptername'] . '</td>';
                  echo '<td class="fr">' . $row['flagreason'] . '</td>';
                  // echo "<td>Click <a href='aviewcontent.php?storyid=" . $row['storyid'] . "' target=_blank >here </a> to view chapter</td>";
                echo '</tr>';
              }//end while
            echo '</table>';
          echo '</div>';
        ?>
      </div> <!-- panel panel-container -->
    </div>
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </body>
</html>
