<?php
  $adminQuery = " SELECT  profilepicture
                  FROM    user
                  WHERE   username = '$admin'";
  $adminResult = $conn ->query($adminQuery);
  $row = $adminResult -> fetch();
  $profilepicture = $row['profilepicture'];
?>

<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-Adminbar"></span>
        <span class="icon-bar"></span></button>
      <a class="navbar-brand" href="dashboard.php"><span>OnceUpon</span>aStory</a>
        </li>
      </ul>
    </div>
  </div><!-- /.container-fluid -->
</nav>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <div class="profile-sidebar">
    <div class="profile-userpic">
      <?php echo '<img src="../images/'.$profilepicture.'" class="img-responsive" alt="">';?>
    </div>
    <div class="profile-usertitle">
      <?php echo '<div class="profile-usertitle-name">'.$admin.'</div>'; ?>
      <div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
    </div>
    <div class="clear"></div>
  </div>
  <div class="divider"></div>
  <form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
	</form>
  <ul class="nav menu">
    <li <?php if ($activemenu == "dashboard") { echo 'class="active"';} ?>><a href="dashboard.php"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
    <li <?php if ($activemenu == "user") { echo 'class="active"';} ?> ><a href="auser.php"><em class="fa fa-users">&nbsp;</em> Manage Users</a></li>
    <li <?php if ($activemenu == "content") { echo 'class="active"';} ?> ><a href="acontent.php"><em class="fa fa-bar-chart">&nbsp;</em> Manage Contents</a></li>
    <li <?php if ($activemenu == "review") { echo 'class="active"';} ?> ><a href="areview.php"><em class="fa fa-comments-o">&nbsp;</em> Manage Comments</a></li>
    <li><a href="alogin.php"><em class="fa fa-power-off">&nbsp;</em> Logout</a></li>
  </ul>
</div><!--/.sidebar-->
