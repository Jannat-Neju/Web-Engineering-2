<?php
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
   exit();
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $details = mysqli_real_escape_string($conn, $_POST['details']);

   mysqli_query($conn, "UPDATE `products` SET name = '$name', details = '$details', price = '$price' WHERE id = '$update_p_id'") or die('query failed');

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;
   $old_image = $_POST['update_p_image'];

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Image file size is too large!';
      }else{
         mysqli_query($conn, "UPDATE `products` SET image = '$image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($image_tmp_name, $image_folder);
         unlink('uploaded_img/'.$old_image);
         $message[] = 'Image updated successfully!';
      }
   }

   $message[] = 'Product updated successfully!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Product</title>

<!-- font awesome cdn link -->
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
/* Sidebar */
.sidebar{
    width:240px;
    background:var(--brown);
    height:100vh;
    position:fixed;
    top:0;
    left:0;
    padding:25px 20px;
    display:flex;
    flex-direction:column;
    box-shadow:3px 0 10px rgba(0,0,0,0.2);
}
.sidebar h2{color:#fff;margin-bottom:35px;font-size:22px;text-align:center;text-transform:uppercase;}
.sidebar a{color:#fff;text-decoration:none;padding:12px 15px;border-radius:8px;margin-bottom:10px;display:flex;align-items:center;gap:10px;transition:all 0.3s;font-weight:500;}
.sidebar a:hover{background-color:var(--green);}
.sidebar a.logout{margin-top:15px;background:var(--green);color:#fff;text-align:center;font-weight:600;}
.sidebar a.logout:hover{background:#fff;color:var(--brown);}

/* Main Content */
.main-content{
    margin-left:260px;
    padding:20px;
    width:calc(100% - 260px);
}

/* Update Product Form */
.update-product{
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
    max-width:500px; /* smaller width */
    margin:auto;
}
.update-product h1{color:var(--brown);font-size:24px;margin-bottom:15px;text-align:center;}
.update-product form{display:flex;flex-direction:column;gap:12px;}
.update-product form .image{
    width:120px;
    height:120px;
    object-fit:cover;
    border-radius:10px;
    margin:auto;
}
.update-product input[type="text"],
.update-product input[type="number"],
.update-product textarea,
.update-product input[type="file"]{
    padding:8px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:14px;
}
.update-product textarea{resize:none;}
.update-product .btn,
.update-product .option-btn{
    padding:8px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:14px;
    text-decoration:none;
    text-align:center;
    color:#fff;
    transition:0.3s;
}
.update-product .btn{background:var(--green);}
.update-product .btn:hover{background:#056b00;}
.update-product .option-btn{background:var(--brown);}
.update-product .option-btn:hover{background:#4a2f1b;}
.update-product p.empty{text-align:center;color:gray;font-size:16px;margin-top:20px;}

/* Responsive */
@media(max-width:720px){
    .main-content{margin-left:0;padding:15px;}
    .update-product{width:100%;padding:15px;}
}
</style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <section class="update-product">
        <h1>Update Product</h1>
        <?php
        $update_id = $_GET['update'];
        $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" class="image" alt="">
            <input type="hidden" value="<?php echo $fetch_products['id']; ?>" name="update_p_id">
            <input type="hidden" value="<?php echo $fetch_products['image']; ?>" name="update_p_image">
            <input type="text" class="box" value="<?php echo $fetch_products['name']; ?>" required placeholder="Update product name" name="name">
            <input type="number" min="0" class="box" value="<?php echo $fetch_products['price']; ?>" required placeholder="Update product price" name="price">
            <textarea name="details" class="box" required placeholder="Update product details" cols="30" rows="5"><?php echo $fetch_products['details']; ?></textarea>
            <input type="file" accept="image/jpg, image/jpeg, image/png" class="box" name="image">
            <input type="submit" value="Update Product" name="update_product" class="btn">
            <a href="admin_products.php" class="option-btn">Go Back</a>
        </form>
        <?php
            }
        } else{
            echo '<p class="empty">No product selected for update</p>';
        }
        ?>
    </section>
</div>

<script src="js/admin_script.js"></script>
</body>
</html>
