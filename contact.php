<?php
@include 'config.php';
session_start();

$message = []; // initialize to avoid undefined-variable / foreach errors
$user_id = $_SESSION['user_id'] ?? null;

// error_reporting(E_ALL); ini_set('display_errors', 1);

if (isset($_POST['send'])) {

    if (!$user_id) {
        $message[] = '⚠ Please login or register to send a message!';
    } else {

        if (!isset($conn) || !$conn) {
            $message[] = 'Database connection error. Please try again later.';
        } else {
            // Clean inputs (use null coalescing to avoid undefined index)
            $name   = trim($_POST['name'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $number = trim($_POST['number'] ?? '');
            $msgtxt = trim($_POST['message'] ?? '');

            // Optional: basic validation
            if ($name === '' || $email === '' || $msgtxt === '') {
                $message[] = 'Please fill all required fields.';
            } else {
                // Check duplicate message (prepared statement)
                $chk = $conn->prepare(
                    "SELECT 1 FROM `message` 
                     WHERE user_id=? AND name=? AND email=? AND `number`=? AND `message`=?"
                );

                if ($chk) {
                    $chk->bind_param("issss", $user_id, $name, $email, $number, $msgtxt);
                    $chk->execute();
                    $chk->store_result();

                    if ($chk->num_rows > 0) {
                        $message[] = 'Message already sent!';
                    } else {
                        // Insert
                        $ins = $conn->prepare(
                            "INSERT INTO `message` (user_id, name, email, `number`, `message`) 
                             VALUES (?, ?, ?, ?, ?)"
                        );

                        if ($ins) {
                            $ins->bind_param("issss", $user_id, $name, $email, $number, $msgtxt);
                            if ($ins->execute()) {
                                $message[] = 'Message sent successfully!';
                            } else {
                                $message[] = 'Insert failed: ' . $ins->error;
                            }
                            $ins->close();
                        } else {
                            $message[] = 'Insert prepare failed: ' . $conn->error;
                        }
                    }
                    $chk->close();
                } else {
                    $message[] = 'Select prepare failed: ' . $conn->error;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php @include 'header.php'; ?>

<section class="heading">
    <h3>Contact Us</h3>
    <p><a href="home.php">home</a> / contact</p>
</section>

<section class="contact">
    <?php if (!empty($message) && is_array($message)): ?>
        <?php foreach ($message as $m): ?>
            <p style="color:#d32f2f; font-weight:600; text-align:center; margin-bottom:10px;">
                <?php echo htmlspecialchars($m); ?>
            </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <form action="" method="POST">
        <h3>Send us a message!</h3>
        <input type="text" name="name" placeholder="Enter your name" class="box" required>
        <input type="email" name="email" placeholder="Enter your email" class="box" required>

        <!-- Phone: no restrictions -->
        <input type="text" name="number" placeholder="Enter your phone number" class="box" required>

        <textarea name="message" class="box" placeholder="Enter your message" required cols="30" rows="10"></textarea>
        <input type="submit" value="Send Message" name="send" class="btn">
    </form>
</section>

<?php @include 'footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
