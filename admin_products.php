<?php
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['add_product'])){
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);
   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $exists = mysqli_query($conn, "SELECT * FROM `products` WHERE name='$name'");
   if(mysqli_num_rows($exists) > 0){
      $message[] = 'Product already exists!';
   } else {
      mysqli_query($conn, "INSERT INTO `products`(name, price, details, image) VALUES('$name','$price','$details','$image')") or die('query failed');
      move_uploaded_file($image_tmp_name, $image_folder);
      $message[] = 'Product added successfully!';
   }
}

if(isset($_GET['delete'])){
   $id = $_GET['delete'];
   $image = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM `products` WHERE id='$id'"));
   unlink('uploaded_img/' . $image['image']);
   mysqli_query($conn, "DELETE FROM `products` WHERE id='$id'") or die('query failed');
   header('location:admin_products.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Products</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{
    --green: #008000;
    --brown: #6B4226;
    --page-bg: #f7f7f7;
}

*{box-sizing:border-box;margin:0;padding:0;font-family:"Poppins",sans-serif;}
body{display:flex;background:var(--page-bg);color:#222;min-height:100vh;}

/* Sidebar styling remains in included file */
.main-content{
    margin-left:260px;
    padding:30px;
    width:calc(100% - 260px);
}

h1,h3{color:var(--brown);margin-bottom:20px;} 

/* Add Product */
.add-products{
    display:flex;
    justify-content:center;
    margin-bottom:30px;
}
.add-products form{
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 3px 8px rgba(0,0,0,0.1);
    max-width:500px;
    width:100%;
}
.add-products .box{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
    margin-bottom:10px;
}
.btn{
    background:var(--green);
    color:#fff;
    border:none;
    padding:10px 20px;
    border-radius:6px;
    cursor:pointer;
    font-weight:500;
}
.btn:hover{background:#056b00;}

/* Search Form Center */
.search-form{
    display:flex;
    justify-content:center;
    margin-bottom:30px;
}
.search-form input{
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
    width:250px;
    margin-right:10px;
}
.search-form button{
    background:var(--green);
    color:#fff;
    padding:10px 20px;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

/* Products Grid */
.box-container{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
}
.box{
    background:#fff;
    border-radius:10px;
    padding:15px;
    box-shadow:0 3px 6px rgba(0,0,0,0.1);
    text-align:center;
}
.box img{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:10px;
    margin-bottom:10px;
}
.price{
    background:var(--green);
    color:#fff;
    padding:5px 10px;
    border-radius:6px;
    display:inline-block;
    margin-bottom:10px;
}
.option-btn,.delete-btn{
    text-decoration:none;
    color:#fff;
    padding:8px 15px;
    border-radius:6px;
    display:inline-block;
    margin:5px;
}
.option-btn{background:var(--green);}
.option-btn:hover{background:#056b00;}
.delete-btn{background:#b23b3b;}
.delete-btn:hover{background:#962d2d;}
.empty{text-align:center;color:var(--brown);font-size:18px;margin-top:50px;}

/* Responsive */
@media(max-width:720px){
    .main-content{margin-left:0;padding:20px;}
    .add-products,.search-form{flex-direction:column;align-items:center;}
    .search-form input{margin-bottom:10px;margin-right:0;width:80%;}
}
</style>
</head>
<body>

<?php @include 'admin_sidebar.php'; ?>

<div class="main-content">
    <!-- Add Product -->
    <section class="add-products">
        <form method="POST" enctype="multipart/form-data">
            <h3>Add New Product</h3>
            <input type="text" class="box" name="name" placeholder="Enter product name" required>
            <input type="number" class="box" name="price" placeholder="Enter price" required>
            <textarea name="details" class="box" placeholder="Enter details" required></textarea>
            <input type="file" name="image" class="box" required>
            <input type="submit" name="add_product" value="Add Product" class="btn">
        </form>
    </section>

    <!-- Search -->
    <section class="search-form">
        <form method="GET">
            <input type="text" name="search" placeholder="Search product..." value="<?php if(isset($_GET['search'])) echo htmlspecialchars($_GET['search']); ?>">
            <button type="submit">Search</button>
        </form>
    </section>

    <!-- Products Grid -->
    <section class="show-products">
        <div class="box-container">
            <?php
            if(isset($_GET['search'])){
                $search = mysqli_real_escape_string($conn, $_GET['search']);
                $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$search%'");
            } else {
                $select_products = mysqli_query($conn, "SELECT * FROM `products`");
            }

            if(mysqli_num_rows($select_products) > 0){
                while($fetch = mysqli_fetch_assoc($select_products)){
            ?>
            <div class="box">
                <div class="price">৳<?php echo $fetch['price']; ?></div>
                <img src="uploaded_img/<?php echo $fetch['image']; ?>" alt="">
                <h3><?php echo $fetch['name']; ?></h3>
                <p><?php echo $fetch['details']; ?></p>
                <a href="admin_update_product.php?update=<?php echo $fetch['id']; ?>" class="option-btn">Update</a>
                <a href="admin_products.php?delete=<?php echo $fetch['id']; ?>" onclick="return confirm('Delete this product?');" class="delete-btn">Delete</a>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">No products found!</p>';
            }
            ?>
        </div>
    </section>
</div>

</body>
</html>
