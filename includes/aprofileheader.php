<?php

   if ($profile<>$user){
       $followQuery="  SELECT  follower,following
                       FROM    follow
                       WHERE   follower='$user'
                       AND     following='$profile'";
       $followResult=$conn->query($followQuery);
       $followCount=$followResult->rowCount();
   }

   $uQuery = " SELECT  username, name, gender, dob, profiledescription,location, profilepicture
               FROM    user
               WHERE   username='$profile'";

   $uResult = $conn->query($uQuery);

   $row = $uResult->fetch();
   $username = $row.['username'];
   echo '
               <ul class="profile-social-links">
                   <li>
                       <a href="profile.php">Stories</a>
                   </li>
                   <li>
                       <a href="reads.php?type=archive&profile='.$profile.'">Archive</a>
                   </li>
                   <li>
                       <a href="reads.php?type=currentreads&profile='.$profile.'">Reads</a>
                   </li>
                   <li>
                       <a href="reads.php?type=readinglist&profile='.$profile.'">Reading List</a>
                   </li>
                   <li>
                       <a href="user.php?type=follower">Followers</a>
                   </li>
                   <li>
                       <a href="user.php?type=following">Following</a>
                   </li>
                   <li style="float:right;">';
                   if($profile == $admin){
                     if($profile != $user){
                         if ($followCount == 0){
                             echo '<a href="profile.php?action=follow&profile='.$profile.'">Follow</a>';
                         }
                         else{
                             echo '<a href="profile.php?action=unfollow&profile='.$profile.'">Unfollow</a>';
                         }
                     }
                   }
                     if($profile != $admin){
                         echo '<a href="profile.php?action=unfollow&profile='.$profile.'">REPORT</a>';
                       }
   echo'           </li>
               </ul>
           <br/><br/><br/>
   ';
?>



  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-10 offset-xl-1">
        <div class="row">
          <div class="col-lg-6">
            <div class="hero-text">
              <?php echo '<h2>'.$row['name'].'</h2>';
                    echo '<p>'.$row['profiledescription'].'</p>';?>
            </div>
            <div class="hero-info">
              <h2>General Info</h2>
              <ul>
                <li><span>Username</span> coco</li>
                <li><span>Date of Birth</span>Aug 25, 1988</li>
                <li><span>Address</span>Rosia Road 55, Gibraltar, UK</li>
                <li><span>E-mail</span>mariawilliams@company.com</li>
                <li><span>Phone </span>+43 5266 22 345</li>
              </ul>
            </div>
          </div>
          <div class="col-lg-6">
            <figure class="hero-image">
            <?php
                echo '<img src="../images/'.$row['profilepicture'].'" alt="5">';
            ?>
            </figure>
          </div>
        </div>
      </div>
    </div>
  </div>
