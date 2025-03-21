<?php 
  session_start();
  echo "Wellcome:".$_SESSION['admin']['username'];
?>
<a href="logout.php">LOGOUT</a>