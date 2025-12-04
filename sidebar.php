<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="sidebar">
   

   <h2><?php echo $_SESSION['user_name'] ?? 'User'; ?></h2>
   <p><?php echo $_SESSION['user_email'] ?? ''; ?></p>

   <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
   <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
   <a href="password.php"><i class="fas fa-lock"></i> Password</a>
   <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
   <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Log Out</a>
</div>
