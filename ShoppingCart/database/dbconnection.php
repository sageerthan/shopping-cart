<?php
$server="localhost";
$user="root";
$password="";
$database="shopping_cart";

try{
    $con=mysqli_connect($server,$user,$password,$database);
}
catch(mysqli_sql_exception)
{
    echo "Database couldn't connect";
}
?>