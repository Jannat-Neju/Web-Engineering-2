<?php
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
   exit();
}

// ---- UPDATE ORDER STATUS ----
if(isset($_POST['update_payment'])){
   $order_id = $_POST['order_id'];
   $update_status = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_status' WHERE id = '$order_id'") or die('query failed');
   $message = "Order #$order_id status updated to $update_status.";
}

// ---- DELETE ORDER ----
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
   exit();
}

// 🧾 If invoice is requested, redirect to invoice.php for email send
if(isset($_GET['invoice'])){
   $invoice_id = $_GET['invoice'];
   header("location:invoice.php?order_id=$invoice_id");
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Orders</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{
    --green: #008000;
    --brown: #6B4226;
    --page-bg: #f7f7f7;
}

*{box-sizing:border-box;margin:0;padding:0;font-family:"Poppins",sans-serif;}
body{display:flex;background:var(--page-bg);color:#222;min-height:100vh;}

/* Sidebar */
.sidebar{
    width:240px;background:var(--brown);height:100vh;position:fixed;top:0;left:0;
    padding:25px 20px;display:flex;flex-direction:column;box-shadow:3px 0 10px rgba(0,0,0,0.2);
}
.sidebar h2{color:#fff;margin-bottom:35px;font-size:22px;text-transform:uppercase;text-align:center;}
.sidebar a{color:#fff;text-decoration:none;padding:12px 15px;border-radius:8px;margin-bottom:10px;display:flex;align-items:center;gap:10px;transition:all 0.3s ease;font-weight:500;}
.sidebar a:hover{background-color:var(--green);}
.sidebar a.logout{margin-top:15px;background:var(--green);color:#fff;text-align:center;font-weight:600;}
.sidebar a.logout:hover{background:#fff;color:var(--brown);}

/* Main Content */
.main-content{
    margin-left:260px;padding:30px;width:calc(100% - 260px);
}
.title{color:var(--brown);font-size:28px;margin-bottom:20px;}

/* Collapsible Orders */
.collapsible{background-color:#fff;color:var(--brown);cursor:pointer;padding:15px 20px;width:100%;border:none;text-align:left;outline:none;font-size:16px;border-radius:10px;margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 3px 8px rgba(0,0,0,0.1);transition: background 0.3s;}
.collapsible:hover{background-color:var(--green); color:#fff;}
.collapsible i{margin-left:10px; transition: transform 0.3s ease;}

/* Collapsible Content */
.content{max-height:0;overflow:hidden;transition:max-height 0.3s ease;background-color:#fff;border-radius:0 0 10px 10px;margin-bottom:10px;box-shadow:0 3px 8px rgba(0,0,0,0.1);padding:0 20px;}
.content p{margin:10px 0;font-weight:500;}
.content span{font-weight:400;color:#333;}

.delete-btn, .invoice-btn{
   background-color:#b23b3b;
   color:#fff;
   padding:8px 15px;
   border-radius:6px;
   font-weight:500;
   cursor:pointer;
   text-decoration:none;
   display:inline-block;
   margin-bottom:5px;
}

.invoice-btn {
   background-color:#007bff;
   margin-left:10px;
}
.invoice-btn:hover{background-color:#0056b3;}
.delete-btn:hover{background-color:#962d2d;}

.empty{text-align:center;color:var(--brown);font-size:18px;margin-top:50px;}

/* Update form */
.update-form {display:inline-block;margin:8px 0;}

/* Status Dropdown */
.status-select {
   padding:8px 14px;
   font-size:15px;
   border:none;
   border-radius:8px;
   color:#fff;
   cursor:pointer;
   font-weight:600;
   transition:all 0.3s ease;
}

/* Color per status */
.status-select.pending { background:#ff9800; }
.status-select.completed { background:#4CAF50; }
.status-select.dispatched { background:#2196F3; }
.status-select.cancelled { background:#f44336; }

.status-select:hover { opacity:0.85; }

@media(max-width:720px){
    .main-content{margin-left:0;padding:20px;}
    .sidebar{position:static;width:100%;height:auto;flex-direction:row;justify-content:space-around;padding:12px 0;}
    .sidebar a{margin-bottom:0;}
    .sidebar a.logout{margin-top:0;}
}
</style>
</head>
<body>

<?php @include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h1 class="title">Placed Orders</h1>

    <?php if(isset($message)) echo '<p style="color:green;font-weight:600;">'.$message.'</p>'; ?>

    <?php
    $select_orders = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY id DESC") or die('query failed');
    if(mysqli_num_rows($select_orders) > 0){
        while($fetch_orders = mysqli_fetch_assoc($select_orders)){

            // Choose class for button color
            $status_class = strtolower($fetch_orders['payment_status']);
    ?>
    <button class="collapsible">
        <?php echo htmlspecialchars($fetch_orders['name']); ?> | £<?php echo htmlspecialchars($fetch_orders['total_price']); ?>
        <i class="fas fa-chevron-down"></i>
    </button>
    <div class="content">
        <p>User ID: <span><?php echo $fetch_orders['user_id']; ?></span></p>
        <p>Placed on: <span><?php echo $fetch_orders['placed_on']; ?></span></p>
        <p>Number: <span><?php echo $fetch_orders['number']; ?></span></p>
        <p>Email: <span><?php echo $fetch_orders['email']; ?></span></p>
        <p>Address: <span><?php echo $fetch_orders['address']; ?></span></p>
        <p>Total Products: <span><?php echo $fetch_orders['total_products']; ?></span></p>
        <p>Payment Method: <span><?php echo $fetch_orders['method']; ?></span></p>
        <p>Payment Status:</p>

        <form action="" method="post" class="update-form">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment" class="status-select <?php echo $status_class; ?>" onchange="this.form.submit()">
               <option disabled selected><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="Pending">Pending</option>
               <option value="Completed">Completed</option>
               <option value="Dispatched">Dispatched</option>
               <option value="Cancelled">Cancelled</option>
            </select>
        </form>

        <!-- 🧾 Added Invoice button beside Delete -->
        <a href="invoice.php?order_id=<?php echo $fetch_orders['id']; ?>" class="invoice-btn">Invoice</a>
        <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
    </div>
    <?php
        }
    } else {
        echo '<p class="empty">No orders placed yet!</p>';
    }
    ?>
</div>

<script>
const collapsibles = document.querySelectorAll(".collapsible");
collapsibles.forEach(coll => {
    coll.addEventListener("click", function(){
        const content = this.nextElementSibling;
        const isActive = this.classList.contains("active");
        if(!isActive){
            this.classList.add("active");
            content.style.maxHeight = content.scrollHeight + "px";
            this.querySelector('i').classList.replace('fa-chevron-down','fa-chevron-up');
        } else {
            this.classList.remove("active");
            content.style.maxHeight = null;
            this.querySelector('i').classList.replace('fa-chevron-up','fa-chevron-down');
        }
    });
});
</script>

</body>
</html>
