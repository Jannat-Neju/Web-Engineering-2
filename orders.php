<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Your Orders</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
    body {
       margin: 0;
       font-family: Arial, sans-serif;
       background-color: #f4f4f4;
     }

     .container {
       display: flex;
       min-height: 100vh;
     }

     /* Sidebar */
     .sidebar {
       width: 250px;
       background-color: #6B4226;
       color: white;
       padding: 20px;
       box-sizing: border-box;
       display: flex;
       flex-direction: column;
     }

     .sidebar h2 {
       font-size: 18px;
       margin-bottom: 20px;
       text-align: center;
     }

     .sidebar p {
       font-size: 14px;
       margin-bottom: 25px;
       color: #ccc;
       text-align: center;
     }

     .sidebar a {
       display: flex;
       align-items: center;
       gap: 10px;
       color: white;
       text-decoration: none;
       padding: 12px 15px;
       font-size: 16px;
       border-radius: 6px;
       margin-bottom: 25px;
       transition: all 0.3s ease;
     }

     .sidebar a:hover {
       background-color: #008000;
       padding-left: 18px;
     }

     .sidebar a.logout-btn {
       background-color: #008000;
       color: #fff;
       padding: 12px 15px;
       text-align: center;
       font-weight: 600;
       border-radius: 6px;
       margin-bottom: 12px;
       text-decoration: none;
       transition: all 0.3s ease;
     }

     .sidebar a.logout-btn:hover {
       background-color: #fff;
       color: #6B4226;
     }

      .main-content {
         flex: 1;
         padding: 40px;
         background-color: #fff;
         min-height: 100vh;
         box-sizing: border-box;
      }

      .title {
         font-size: 24px;
         font-weight: bold;
         margin-bottom: 20px;
         color: #333;
      }

      .box-container {
         display: flex;
         flex-direction: column;
         gap: 20px;
      }

      .order-summary {
         background-color: #fff;
         border: 1px solid #ddd;
         border-radius: 10px;
         box-shadow: 0 2px 5px rgba(0,0,0,0.05);
         transition: all 0.3s ease;
         cursor: pointer;
         overflow: hidden;
      }

      .order-header {
         padding: 15px 20px;
         background-color: #6B4226;
         color: #fff;
         display: flex;
         justify-content: space-between;
         align-items: center;
         font-size: 16px;
         border-bottom: 1px solid #ccc;
      }

      .order-header:hover {
         background-color: #008000;
      }

      .order-details {
         display: none;
         padding: 15px 20px;
         background-color: #f9f9f9;
      }

      .order-details p {
         margin: 8px 0;
         font-size: 15px;
         color: #333;
      }

      .order-details span {
         font-weight: bold;
      }

      .toggle-icon {
         transition: transform 0.3s ease;
      }

      .empty {
         font-size: 18px;
         color: #999;
         text-align: center;
         margin-top: 50px;
      }

      @media (max-width: 768px) {
         .order-summary {
            width: 100%;
         }
      }
   </style>
</head>
<body>

<?php @include 'header.php'; ?>

<div class="container">
   <?php @include 'sidebar.php'; ?>

   <div class="main-content">
      <h1 class="title">Your Orders</h1>

      <div class="box-container">
         <?php
         $select_orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id'") or die('query failed');
         if (mysqli_num_rows($select_orders) > 0) {
            $count = 0;
            while ($fetch_orders = mysqli_fetch_assoc($select_orders)) {
               $count++;
         ?>
         <div class="order-summary" onclick="toggleOrder(<?php echo $count; ?>)">
            <div class="order-header">
               <span><strong>Order #<?php echo $count; ?></strong> - <?php echo $fetch_orders['placed_on']; ?></span>
               <i class="fas fa-chevron-down toggle-icon" id="icon-<?php echo $count; ?>"></i>
            </div>
            <div class="order-details" id="order-<?php echo $count; ?>">
               <p>Name: <span><?php echo $fetch_orders['name']; ?></span></p>
               <p>Number: <span><?php echo $fetch_orders['number']; ?></span></p>
               <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
               <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
               <p>Payment Method: <span><?php echo $fetch_orders['method']; ?></span></p>
               <p>Your Orders: <span><?php echo $fetch_orders['total_products']; ?></span></p>
               <p>Total Price: <span>$<?php echo $fetch_orders['total_price']; ?>/-</span></p>
               <p>Payment Status:
                  <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'tomato' : 'green'; ?>">
                     <?php echo $fetch_orders['payment_status']; ?>
                  </span>
               </p>
            </div>
         </div>
         <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
         ?>
      </div>
   </div>
</div>

<?php @include 'footer.php'; ?>

<script>
function toggleOrder(id) {
   const details = document.getElementById("order-" + id);
   const icon = document.getElementById("icon-" + id);

   if (details.style.display === "block") {
      details.style.display = "none";
      icon.style.transform = "rotate(0deg)";
   } else {
      details.style.display = "block";
      icon.style.transform = "rotate(180deg)";
   }
}
</script>

</body>
</html>
