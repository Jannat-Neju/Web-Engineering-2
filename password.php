<?php
@include 'config.php';
session_start();

if (!isset($_SESSION['user_email'])) {
    header('location:login.php');
    exit();
}

$user_email = $_SESSION['user_email'];
$message = '';

if (isset($_POST['submit'])) {
    $current_pass = md5(mysqli_real_escape_string($conn, $_POST['current_pass']));
    $new_pass = md5(mysqli_real_escape_string($conn, $_POST['new_pass']));
    $confirm_pass = md5(mysqli_real_escape_string($conn, $_POST['confirm_pass']));

    // Get user from database
    $select = mysqli_query($conn, "SELECT * FROM users WHERE email = '$user_email'") or die('Query failed');

    if (mysqli_num_rows($select) > 0) {
        $row = mysqli_fetch_assoc($select);
        $stored_pass = $row['password'];

        // Check current password
        if ($current_pass !== $stored_pass) {
            $message = 'Current password is incorrect!';
        } elseif ($new_pass !== $confirm_pass) {
            $message = 'New password and confirm password do not match!';
        } else {
            mysqli_query($conn, "UPDATE users SET password = '$new_pass' WHERE email = '$user_email'") or die('Update failed');
            $message = 'Password updated successfully!';
        }
    } else {
        $message = 'User not found!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Change Password</title>
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
       margin-bottom: 25px; /* spacing between features */
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

      .main-content {
         flex: 1;
         padding: 60px 20px;
         display: flex;
         justify-content: center;
         align-items: flex-start;
         background-color: #f4f4f4;
      }

      .form-box {
         background-color: white;
         border: 2px solid #6B4226;
         padding: 30px 40px;
         border-radius: 12px;
         width: 100%;
         max-width: 450px;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }

      .form-box h2 {
         font-size: 22px;
         font-weight: bold;
         margin-bottom: 25px;
         text-align: center;
         color: #333;
      }

      .form-box .input-group {
         position: relative;
         margin-bottom: 20px;
      }

      .form-box input[type="password"],
      .form-box input[type="text"] {
         width: 100%;
         padding: 12px;
         padding-right: 40px;
         border: 1px solid #ccc;
         border-radius: 6px;
         font-size: 15px;
         box-sizing: border-box;
      }

      .form-box .toggle-password {
         position: absolute;
         top: 50%;
         right: 10px;
         transform: translateY(-50%);
         cursor: pointer;
         color: #888;
      }

      .form-box input[type="submit"] {
         width: 100%;
         padding: 12px;
         background-color: #008000;
         color: white;
         border: none;
         border-radius: 6px;
         font-size: 15px;
         font-weight: bold;
         cursor: pointer;
         transition: 0.3s;
      }

      .form-box input[type="submit"]:hover {
         background-color: #6B4226;
      }

      .message {
         text-align: center;
         font-size: 15px;
         color: #008000;
         margin-top: 10px;
      }
   </style>
</head>
<body>

<?php @include 'header.php'; ?>

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
   <div class="main-content">
      <div class="form-box">
         <h2>Your Password</h2>
         <form method="post" action="">
            <div class="input-group">
               <input type="password" name="current_pass" placeholder="Current Password" required id="current_pass">
               <i class="fas fa-eye toggle-password" onclick="togglePassword('current_pass')"></i>
            </div>
            <div class="input-group">
               <input type="password" name="new_pass" placeholder="New Password" required id="new_pass">
               <i class="fas fa-eye toggle-password" onclick="togglePassword('new_pass')"></i>
            </div>
            <div class="input-group">
               <input type="password" name="confirm_pass" placeholder="Confirm Password" required id="confirm_pass">
               <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm_pass')"></i>
            </div>
            <input type="submit" name="submit" value="Change Password">
         </form>
         <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
         <?php endif; ?>
      </div>
   </div>
</div>

<script>
function togglePassword(id) {
   const field = document.getElementById(id);
   field.type = field.type === 'password' ? 'text' : 'password';
}
</script>

<?php @include 'footer.php'; ?>
</body>
</html>