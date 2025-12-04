<?php
include 'config.php';

$name = $email = "";
$nameErr = $emailErr = $passErr = $cpassErr = "";
$generalErr = "";
$message = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   // Name
   if (empty($_POST["name"])) {
      $nameErr = "Name is required";
   } else {
      $filter_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
      $name = mysqli_real_escape_string($conn, $filter_name);
   }

   // Email
   if (empty($_POST["email"])) {
      $emailErr = "Email is required";
   } else {
      $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
      $email = mysqli_real_escape_string($conn, $filter_email);
   }

   // Password
   if (empty($_POST["pass"])) {
      $passErr = "Password is required";
   } else {
      $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
      $pass = mysqli_real_escape_string($conn, md5($filter_pass));
   }

   // Confirm Password
   if (empty($_POST["cpass"])) {
      $cpassErr = "Confirm password is required";
   } else {
      $filter_cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);
      $cpass = mysqli_real_escape_string($conn, md5($filter_cpass));
   }

   // Check everything before proceeding
   if (empty($nameErr) && empty($emailErr) && empty($passErr) && empty($cpassErr)) {
      $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'") or die('Query failed');

      if (mysqli_num_rows($select_users) > 0) {
         $generalErr = "User with this email already exists!";
      } elseif ($pass !== $cpass) {
         $generalErr = "Confirm password doesn't match!";
      } else {
         mysqli_query($conn, "INSERT INTO `users`(name, email, password, user_type) VALUES('$name', '$email', '$pass', 'user')") or die('Insert failed');
         header('location:login.php');
         exit;
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <title>Register</title>
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
      <h3>Register Now</h3>

      <?php if (!empty($generalErr)) : ?>
         <div class="message"><?php echo $generalErr; ?></div>
      <?php endif; ?>

      <input type="text" name="name" class="box" placeholder="Enter your name" value="<?php echo htmlspecialchars($name); ?>">
      <?php if (!empty($nameErr)) : ?>
         <div class="error"><?php echo $nameErr; ?></div>
      <?php endif; ?>

      <input type="email" name="email" class="box" placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>">
      <?php if (!empty($emailErr)) : ?>
         <div class="error"><?php echo $emailErr; ?></div>
      <?php endif; ?>

      <input type="password" name="pass" class="box" placeholder="Enter your password">
      <?php if (!empty($passErr)) : ?>
         <div class="error"><?php echo $passErr; ?></div>
      <?php endif; ?>

      <input type="password" name="cpass" class="box" placeholder="Confirm your password">
      <?php if (!empty($cpassErr)) : ?>
         <div class="error"><?php echo $cpassErr; ?></div>
      <?php endif; ?>

      <input type="submit" name="submit" class="btn" value="Register Now">
      <p>Already have an account? <a href="login.php">Login now</a></p>
   </form>
</section>

</body>
</html>
