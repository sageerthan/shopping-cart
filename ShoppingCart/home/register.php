<?php
    include("../database/dbconnection.php");
      if(isset($_POST['submit']))
      {
       $name=$_POST['name'];
       $password=$_POST['password'];
       $cpassword=$_POST['cpassword'];
       $email=$_POST['email'];

       $sql="select * from user_form where email='$email' and  password='$password'";
       $result=mysqli_query($con,$sql);
       
       if($name=='' or $email=='' or $password=='' or $cpassword=='')
       {
        $message[]="please fill all the fields";
       }
       else
       {
          if(mysqli_num_rows($result)>0)
          {
             $message[]="user already exist";
          }
          elseif($password != $cpassword)
          {
             $message[]="please check the confirm password";
          }
          else
          {
            mysqli_query($con,"insert into user_form (name,email,password) values('$name','$email','$password')");
            $message[]= "Registered successfully!!!";
            header("location:login.php");
          }
       }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
   <h3>register now</h3>
   <input type="text" name="name"  placeholder="enter username" class="box">
   <input type="email" name="email"  placeholder="enter email" class="box">
   <input type="password" name="password" placeholder="enter password" class="box">
   <input type="password" name="cpassword"  placeholder="confirm password" class="box">
   <input type="submit" name="submit" class="btn" value="Register now">
   <p>already have an account? <a href="login.php">login now</a></p>
</form>

</div>
</body>
</html>