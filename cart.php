<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

// if(!isset($user_id)){
//    header('location:login.php');
// };

if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
    header('location:cart.php');
}

if(isset($_GET['delete_all'])){
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    header('location:cart.php');
};

if(isset($_POST['update_quantity'])){
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
    $message[] = 'cart quantity updated!';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
        .qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        .qty-control button {
            width: 30px;
            height: 30px;
            border: none;
            background-color: #008000;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
        }
        .qty-control input[type="number"] {
            width: 50px;
            text-align: center;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            appearance: textfield;
        }
        .qty-control input::-webkit-outer-spin-button,
        .qty-control input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
   </style>

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>shopping cart</h3>
    <p> <a href="home.php">home</a> / cart </p>
</section>

<section class="shopping-cart">

    <h1 class="title">products added</h1>

    <div class="box-container">

    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
    ?>
    <div  class="box">
        <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
        <a href="view_page.php?pid=<?php echo $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
        <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="" class="image">
        <div class="name"><?php echo $fetch_cart['name']; ?></div>
        <div class="price">৳<?php echo $fetch_cart['price']; ?>/-</div>
        
        <!-- ✅ Updated quantity style -->
        <form action="" method="post">
            <input type="hidden" value="<?php echo $fetch_cart['id']; ?>" name="cart_id">
            <div class="qty-control">
                <button type="button" onclick="decreaseQty(this)">-</button>
                <input type="number" min="1" value="<?php echo $fetch_cart['quantity']; ?>" name="cart_quantity">
                <button type="button" onclick="increaseQty(this)">+</button>
            </div>
            <input type="submit" value="update" class="option-btn" name="update_quantity">
        </form>
        <!-- ✅ End update -->

        <div class="sub-total"> sub-total : <span>৳<?php echo $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
    </div>
    <?php
    $grand_total += $sub_total;
        }
    }else{
        echo '<p class="empty">your cart is empty</p>';
    }
    ?>
    </div>

    <div class="more-btn">
        <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled' ?>" onclick="return confirm('delete all from cart?');">delete all</a>
    </div>

    <div class="cart-total">
        <p>grand total : <span>৳<?php echo $grand_total; ?>/-</span></p>
        <a href="shop.php" class="option-btn">continue shopping</a>
        <a href="checkout.php" class="btn  <?php echo ($grand_total > 1)?'':'disabled' ?>">proceed to checkout</a>
    </div>

</section>

<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>
<script>
    function increaseQty(button) {
        const input = button.previousElementSibling;
        input.value = parseInt(input.value) + 1;
    }

    function decreaseQty(button) {
        const input = button.nextElementSibling;
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>

</body>
</html>
