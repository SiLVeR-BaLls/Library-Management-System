<?php 
  session_start();
  echo "Wellcome:".$_SESSION['user']['username'];
?>
<a href="logout.php">LOGOUT</a>