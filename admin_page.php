<?php
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:login.php');
   exit();
}

function safe_count($conn, $table) {
    $chk = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if ($chk && mysqli_num_rows($chk) > 0) {
        $res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM `$table`");
        if ($res) {
            $row = mysqli_fetch_assoc($res);
            return (int)$row['cnt'];
        }
    }
    return 0;
}

$total_pendings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(total_price),0) AS total FROM `orders` WHERE payment_status = 'pending'"))['total'];
$total_completes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(total_price),0) AS total FROM `orders` WHERE payment_status = 'completed'"))['total'];
$number_of_orders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM `orders`"))['cnt'];
$number_of_products = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM `products`"))['cnt'];
$number_of_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM `users` WHERE user_type='user'"))['cnt'];
$number_of_admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM `users` WHERE user_type='admin'"))['cnt'];
$number_of_messages = safe_count($conn, 'message');
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>
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
      h1.title {
         color: #6B4226;
         margin-bottom: 25px;
      }

      .box-grid {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
         gap: 20px;
      }

      .card {
         background: #fff;
         padding: 20px;
         border-radius: 10px;
         text-align: center;
         box-shadow: 0 3px 6px rgba(0,0,0,0.1);
         transition: transform 0.2s;
      }

      .card:hover {
         transform: translateY(-5px);
      }

      .card h3 {
         color: #008000;
         margin-bottom: 10px;
         font-size: 26px;
      }

      .card p {
         color: #6B4226;
         font-weight: 600;
      }
   </style>
</head>
<body>

   <?php @include 'admin_sidebar.php'; ?>

   <div class="main-content">
      <h1 class="title">Dashboard Overview</h1>
      <div class="box-grid">
         <div class="card"><h3>৳<?php echo number_format($total_pendings, 2); ?></h3><p>Total Pendings</p></div>
         <div class="card"><h3>৳<?php echo number_format($total_completes, 2); ?></h3><p>Completed Payments</p></div>
         <div class="card"><h3><?php echo $number_of_orders; ?></h3><p>Total Orders</p></div>
         <div class="card"><h3><?php echo $number_of_products; ?></h3><p>Products Added</p></div>
         <div class="card"><h3><?php echo $number_of_users; ?></h3><p>Normal Users</p></div>
         <div class="card"><h3><?php echo $number_of_admin; ?></h3><p>Admin Users</p></div>
         <div class="card"><h3><?php echo $number_of_messages; ?></h3><p>Messages</p></div>
      </div>
   </div>

</body>
</html>
