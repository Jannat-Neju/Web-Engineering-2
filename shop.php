<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_wishlist_numbers) > 0) {
        $message[] = 'already added to wishlist';
    } elseif (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'already added to cart';
    } else {
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'product added to wishlist';
    }
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
        $message[] = 'already added to cart';
    } else {
        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        if (mysqli_num_rows($check_wishlist_numbers) > 0) {
            mysqli_query($conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
        }
        mysqli_query($conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'product added to cart';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .box {
            position: relative;
            overflow: hidden;
        }

        .btn-group {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 10px;
            transform: translateY(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .box:hover .btn-group {
            transform: translateY(0);
        }

        .btn-group button {
            border: none;
            cursor: pointer;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-btn {
            background-color: white;
            color: #008000;
            border: 2px solid #e84393;
        }

        .cart-btn {
            background-color: #008000;
            color: white;
        }

        .cart-btn i,
        .wishlist-btn i {
            margin: 0;
        }
    </style>
</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>our shop</h3>
    <p> <a href="home.php">home</a> / shop </p>
</section>

<section class="products">
    <h1 class="title">latest products</h1>
    <div class="box-container">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
        <form action="" method="POST" class="box">
            <div class="btn-group">
                <button type="submit" name="add_to_wishlist" class="wishlist-btn"><i class="fas fa-heart"></i></button>
                <button type="submit" name="add_to_cart" class="cart-btn"><i class="fas fa-shopping-cart"></i></button>
            </div>
            <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>" class="fas fa-eye"></a>
            <div class="price">৳<?php echo $fetch_products['price']; ?>/-</div>
            <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="qty-control">
                <button type="button" onclick="decreaseQty(this)">-</button>
                <input type="number" name="product_quantity" value="1" min="1">
                <button type="button" onclick="increaseQty(this)">+</button>
            </div>
            <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
            <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
            <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
            <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
        </form>
        <?php
            }
        } else {
            echo '<p class="empty">no products added yet!</p>';
        }
        ?>
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
