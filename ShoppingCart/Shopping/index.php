<?php
include("../database/dbconnection.php");
session_start();
$id = $_SESSION['id'];
if (isset($_GET["logout"])) {
    unset($id);
    session_destroy();
    header("location: ../home/login.php");
}


if (isset($_POST["add"])) {
    $product_name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];

    $sql = "select * from cart where name ='$product_name' and user_id='$id'";
    $select_cart = mysqli_query($con, $sql) or die('query failed');

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'product already added to the cart';
    } else {
        mysqli_query($con, "insert into cart(user_id,name,price,image,quantity) values('$id','$product_name','$price','$image','$quantity')") or die('query failed');
        $message[] = 'product added to the cart';
    }
}

if (isset($_POST["update_cart"]))
{
    $update_quantity=$_POST['cart_quantity'];
    $update_id=$_POST['cart_id'];
    mysqli_query($con,"update cart set quantity=$update_quantity where id=$update_id");
    $message[]='product quantity updated successfully';
}
if(isset($_GET["remove"]))
{
    $remove_id=$_GET['remove'];
    mysqli_query($con,"delete from cart where id = $remove_id ");
    header("location:index.php");
}
if(isset($_GET["delete_all"]))
{
    mysqli_query($con,"delete from cart where user_id = $id");
    header("location:index.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.css" />
    <link rel="stylesheet" href="style.css" type="text/css">

    <style>

    </style>
</head>

<body>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="msg" onclick="this.remove();">' . $message . '</div>';
        }
    }
    ?>
    <div class="container">
        <div class="user-profile">
            <?php
            $select_user = mysqli_query($con, "select * from user_form where id='$id'");
            if (mysqli_num_rows($select_user) > 0) {
                $fetch_user = mysqli_fetch_assoc($select_user);
            }
            ?>
            <p>username:<span><?= $fetch_user['name'] ?></span></p>
            <p>email:<span><?= $fetch_user['email'] ?></span></p>
            <div class="flex">
                <a href="../home/login.php" class="btn">Login</a>
                <a href="../home/register.php" class="option-btn">Register</a>
                <a href="index.php?logout= <?= $id ?>" onclick="return confirm ('Are you sure you want to logout?')" class="delete-btn">Logout</a>
            </div>

        </div>
        <div class="products">
            <h3 class="heding">Men Wears</h3>
            <?php
            $sql = "select * from products";
            $result = mysqli_query($con, $sql);
            if (mysqli_num_rows($result) > 0) {
                foreach ($result as $product) {

            ?>

                    <form action="" method="post">
                        <div class="card-shadow">

                            <div>
                                <img src=<?= $product['product_img'] ?> alt=<?= $product['product_name'] ?>>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $product['product_name'] ?></h5>
                                <h6>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </h6>
                                <h5>
                                    <span class="price"><?= number_format($product['product_price'], 2) ?> Rs</span>
                                </h5>

                                <input type="hidden" name="name" value="<?= $product['product_name'] ?>">
                                <input type="hidden" name="price" value="<?= $product['product_price'] ?>">
                                <input type="hidden" name="image" value="<?= $product['product_img'] ?>">
                                <input type="number" name="quantity" value="1" class="form-control">
                                <button type="submit" class="card" name="add">Add to Cart<i class="fas fa-shopping-cart"></i></button>

                            </div>
                        </div>
                    </form>


            <?php
                }
            } else {
                echo "No records found";
            }
            ?>
        </div>

        <div class="shopping-cart">
            <h1 class="heading">Shopping Cart</h1>
            <table>
                <thead>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total price</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    $shopping_cart = mysqli_query($con, "select * from cart where user_id='$id'");
                    $total = 0;
                    if (mysqli_num_rows($shopping_cart) > 0) {
                        foreach ($shopping_cart as $shopping_cart) {
                    ?>

                            <tr>
                                <td><img src=<?= $shopping_cart['image'] ?> height="100"></td>
                                <td><?= $shopping_cart['name'] ?></td>
                                <td><?= number_format($shopping_cart['price'], 2) ?> Rs</td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="cart_id" value="<?= $shopping_cart['id'] ?>">
                                        <input type="number" min="1" name="cart_quantity" value="<?= $shopping_cart['quantity'] ?>">
                                        <input type="submit" name="update_cart" value="update" class="option-btn">
                                    </form>
                                </td>
                                <td>Rs<?php echo $sub_total = ($shopping_cart['price'] * $shopping_cart['quantity']) ?>/=</td>
                                <td><a href="index.php?remove=<?= $shopping_cart['id'] ?>" class="delete-btn" onclick="return confirm('remove item from cart?')">Remove</a></td>
                            </tr>
                    <?php
                            $total += $sub_total;
                        }
                    }
                    else{
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">no item added</td></tr>';
                     }
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4">Grand total :</td>
                        <td>Rs<?= $total ?>/=</td>
                        <td><a href="index.php?delete_all" onclick="return confirm('delete all from cart?')" class="delete-btn <?php echo ($total > 1) ? '' : 'disabled' ?>">Delete all</a></td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-btn">
                <a href="#" class="btn <?php echo ($total > 1) ? '' : 'disabled' ?>">proceed to checkout</a>
            </div>

        </div>

    </div>
</body>

</html>