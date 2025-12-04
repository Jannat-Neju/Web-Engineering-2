<?php

@include 'config.php';

session_start();

// $user_id = $_SESSION['user_id'];

// if(!isset($user_id)){
//    header('location:login.php');
// }

if(isset($_POST['add_to_wishlist'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_wishlist_numbers) > 0){
        $message[] = 'already added to wishlist';
    }elseif(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{
        mysqli_query($conn, "INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_image')") or die('query failed');
        $message[] = 'product added to wishlist';
    }

}

if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($check_cart_numbers) > 0){
        $message[] = 'already added to cart';
    }else{

        $check_wishlist_numbers = mysqli_query($conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_wishlist_numbers) > 0){
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
   <title>search page</title>

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
            margin: 10px 0;
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
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .btn-group {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            display: flex;
            justify-content: center;
            gap: 20px;
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
        
        .fas.fa-eye {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #3498db;
            font-size: 20px;
            z-index: 1;
        }
        
        .price {
            font-size: 20px;
            color: #e74c3c;
            margin: 10px 0;
            font-weight: bold;
        }
        
        .name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        
        .search-form {
            margin: 20px auto;
            max-width: 500px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .search-form input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            max-width: 400px;
        }
        
        .search-form input[type="submit"] {
            padding: 10px 20px;
            background-color: #008000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .search-form input[type="submit"]:hover {
            background-color: #6B4226;
        }
        
        .products {
            margin-top: 30px;
        }
        
        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            padding: 10px;
        }
        
        .empty {
            text-align: center;
            font-size: 18px;
            color: #7f8c8d;
            padding: 20px;
            grid-column: 1 / -1;
        }
        
        .heading {
            text-align: center;
            padding: 20px;
            background-color: #6B4226;
            margin-bottom: 30px;
        }
        
        .heading h3 {
            color: #ccc;
            font-size: 30px;
            margin-bottom: 10px;
        }
        
        .heading p {
            color: #7f8c8d;
            font-size: 30px;
        }
        
        .heading p a {
            color: #e84393;
            text-decoration: none;
        }
        
        .heading p a:hover {
            text-decoration: underline;
        }
   </style>
</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>search page</h3>
    <p> <a href="home.php">home</a> / search </p>
</section>

<section class="search-form">
    <form action="" method="POST">
        <input type="text" class="box" placeholder="search products by name or price..." name="search_box" required>
        <input type="submit" class="btn" value="search" name="search_btn">
    </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container">

      <?php
        if(isset($_POST['search_btn'])){
         $search_box = mysqli_real_escape_string($conn, $_POST['search_box']);
         
         // Check if the search term is numeric (potentially a price search)
         if(is_numeric($search_box)) {
             // Search by price (exact match or range could be implemented)
             $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE price = '$search_box'") or die('query failed');
         } else {
             // Search by name
             $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'") or die('query failed');
         }
         
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
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
            }else{
                echo '<p class="empty">no result found!</p>';
            }
        } else {
            // Don't show any products before search
            echo '<p class="empty">Search for products by name or price!</p>';
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