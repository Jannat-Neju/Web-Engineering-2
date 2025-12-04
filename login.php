<?php
include 'config.php';
session_start();

$emailErr = $passErr = "";
$generalErr = "";
$email = "";
$pass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   // Validate email
   if (empty($_POST["email"])) {
      $emailErr = "Email is required";
   } else {
      $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
      $email = mysqli_real_escape_string($conn, $filter_email);
   }

   // Validate password
   if (empty($_POST["pass"])) {
      $passErr = "Password is required";
   } else {
      $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
      $pass = mysqli_real_escape_string($conn, md5($filter_pass));
   }

   // Check login only if no validation errors
   if (empty($emailErr) && empty($passErr)) {
      $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');

      if (mysqli_num_rows($select_users) > 0) {
         $row = mysqli_fetch_assoc($select_users);

         // Fallback user_type
         if (!isset($row['user_type']) || empty($row['user_type'])) {
            $row['user_type'] = 'user';
         }

         if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin_page.php');

         } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            header('location:home.php');
         }

      } else {
         $generalErr = "Invalid credentials: Email or password is incorrect.";
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .error {
         color: red;
         font-size: 14px;
         margin-top: 5px;
      }
      .message {
         color: red;
         margin-bottom: 15px;
         text-align: center;
         font-weight: bold;
      }
   </style>
</head>
<body>

<section class="form-container">
   <form action="" method="post">
      <h3>Login Now</h3>

      <?php if (!empty($generalErr)) : ?>
         <div class="message"><?php echo $generalErr; ?></div>
      <?php endif; ?>

      <input type="email" name="email" class="box" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>">
      <?php if (!empty($emailErr)) : ?>
         <div class="error"><?php echo $emailErr; ?></div>
      <?php endif; ?>

      <input type="password" name="pass" class="box" placeholder="Enter your password">
      <?php if (!empty($passErr)) : ?>
         <div class="error"><?php echo $passErr; ?></div>
      <?php endif; ?>

      <input type="submit" class="btn" name="submit" value="Login Now">
      <p>Don't have an account? <a href="register.php">Register now</a></p>
   </form>
</section>

</body>
</html>
