<?php
    include("../database/dbconnection.php");
    session_start();
      if(isset($_POST['submit']))
      {
       
       $password=$_POST['password'];
       $email=$_POST['email'];

       $sql="select * from user_form where email='$email' and  password='$password'";
       $result=mysqli_query($con,$sql);
       
       if($email=='' or $password=='')
       {
        $message[]="please fill all the fields";
       }
       else
       {
          if(mysqli_num_rows($result)>0)
          {
             $row=mysqli_fetch_assoc($result);
             $_SESSION['id']=$row['id'];
             header("location:../shopping/index.php");
          }
          
          else
          {
            $message[]= "Invalid username or password!!!";
          }
       }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        


    </style>
</head>
<body>
<?php
    if(isset($message))
    {
       foreach($message as $message)
       {
        echo'<div class="msg" onclick="this.remove();">'.$message.'</div>';
       }
    }
    ?>
<div class="form-container">
    

<form action="" method="post">
   <h3>Login now</h3>
   <input type="email" name="email"  placeholder="enter email" class="box">
   
   <input type="password" name="password" placeholder="enter password" class="box">
   
   <input type="submit" name="submit" class="btn" value="Login now">
   <p>not have an account? <a href="register.php">Register now</a></p>
</form>

</div>
</body>
</html>