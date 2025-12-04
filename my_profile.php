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
   <title>User Dashboard</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css"> <!-- Optional external style -->
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
       margin-bottom: 12px; /* spacing between features */
       transition: all 0.3s ease;
     }

     .sidebar a:hover {
       background-color: #008000; /* green hover for regular links */
       padding-left: 18px; /* subtle indent on hover */
     }

     /* Logout button */
     .sidebar a.logout-btn {
       background-color: #008000; /* default green */
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
       background-color: #fff;    /* white on hover */
       color: #6B4226;            /* brown text on hover */
     }

     /* Main Content */
     .main-content {
       flex: 1;
       padding: 40px;
       background-color: #fff;
     }

     /* Placeholder text */
     .placeholder {
       font-size: 18px;
       color: gray;
       margin-top: 100px;
       text-align: center;
     }

     /* Responsive */
     @media (max-width: 768px) {
       .sidebar {
         width: 100%;
         flex-direction: row;
         justify-content: space-around;
         height: auto;
         padding: 12px 0;
       }

       .sidebar a {
         margin-bottom: 0;
       }

       .logout-btn {
         margin-top: 0;
       }
     }
   </style>
</head>
<body>

<!-- Header -->
<?php include 'header.php'; ?>

<div class="container">
   <!-- Sidebar -->
   <div class="sidebar">
      <h2><?php echo $_SESSION['user_name'] ?? 'User'; ?></h2>
      <p><?php echo $_SESSION['user_email'] ?? ''; ?></p>

      <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
      <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
      <a href="password.php"><i class="fas fa-lock"></i> Password</a>
      <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
      <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Log Out</a>
   </div>

   <!-- Main Content -->
   <div class="main-content" id="main-content">
      <p class="placeholder">Select an option from the sidebar.</p>
   </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
