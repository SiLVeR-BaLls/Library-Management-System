<?php 
 $conn=mysqli_connect("localhost","root","","sports");

 session_start();
 if (isset($_POST['submit'])) {
   $uname=$_POST['uname'];
   $password=$_POST['password'];
   
  $query="SELECT * FROM `register` INNER JOIN roles ON register.role=roles.type WHERE username='".$uname."' AND password='".$password."'";
   $result=mysqli_query($conn,$query);

   while ($row=mysqli_fetch_assoc($result)) {
      // print_r($row);
      if ($row['role']=="admin") {
          $_SESSION['admin']=$row;
         header("Location:admin.php");
       } 
       elseif($row["role"]=="user"){
         $_SESSION['user']=$row;
         header("Location:user.php");
       }
       else{
         echo "Your UserName Or Password Not Matched!";
       }
   }
   
 }

?>