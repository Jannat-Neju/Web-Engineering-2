<?php
@include 'config.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';
require 'fpdf/fpdf.php'; // using FPDF for PDF generation
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}

// Validate order ID
if (!isset($_GET['order_id'])) {
    die("Invalid order ID.");
}

$order_id = intval($_GET['order_id']);
$order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE id = '$order_id'") or die('Query failed');

if (mysqli_num_rows($order_query) === 0) {
    die("Order not found!");
}

$order = mysqli_fetch_assoc($order_query);

// ------------------- PDF GENERATION -------------------
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Invoice #' . $order['id'], 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, 'Date: ' . $order['placed_on'], 0, 1);
$pdf->Cell(0, 8, 'Name: ' . $order['name'], 0, 1);
$pdf->Cell(0, 8, 'Phone: ' . $order['number'], 0, 1);
$pdf->Cell(0, 8, 'Email: ' . $order['email'], 0, 1);
$pdf->Cell(0, 8, 'Address: ' . $order['address'], 0, 1);
$pdf->Ln(8);
$pdf->Cell(0, 8, 'Payment Method: ' . $order['method'], 0, 1);
$pdf->Cell(0, 8, 'Ordered Items: ' . $order['total_products'], 0, 1);
$pdf->Cell(0, 8, 'Total Price: $' . $order['total_price'], 0, 1);
$pdf->Cell(0, 8, 'Payment Status: ' . $order['payment_status'], 0, 1);

$pdf_filename = 'invoice_' . $order['id'] . '.pdf';
$pdf->Output('F', $pdf_filename); // Save PDF locally

// ------------------- EMAIL SENDING -------------------
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'jannatneju200012@gmail.com'; // your email
    $mail->Password = 'oxcu ktrq elkx hkxn'; // Gmail App Password (not regular password)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('your_email@gmail.com', 'BotaniQ Shop');
    $mail->addAddress($order['email'], $order['name']);

    // Attach PDF
    $mail->addAttachment($pdf_filename);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Your Invoice #' . $order['id'];
    $mail->Body    = '
        <h3>Dear ' . htmlspecialchars($order['name']) . ',</h3>
        <p>Thank you for your purchase! Please find your invoice attached.</p>
        <p><b>Order ID:</b> ' . $order['id'] . '</p>
        <p><b>Total:</b> $' . $order['total_price'] . '</p>
        <p>We appreciate your business!</p>
        <br>
        <p>Best regards,<br>BotaniQ Shop Team</p>
    ';

    $mail->send();
    $message = "Invoice has been sent successfully to " . $order['email'] . "!";

} catch (Exception $e) {
    $message = "Invoice could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

// Delete local PDF file after sending
if (file_exists($pdf_filename)) {
    unlink($pdf_filename);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice Status</title>
<style>
body {
   font-family: Poppins, sans-serif;
   background: #f9f9f9;
   color: #333;
   text-align: center;
   padding-top: 80px;
}
.message-box {
   background: #fff;
   padding: 40px;
   max-width: 500px;
   margin: auto;
   border-radius: 10px;
   box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.success { color: green; font-weight: 600; }
.error { color: red; font-weight: 600; }
a {
   display:inline-block;
   margin-top:20px;
   background:#6B4226;
   color:#fff;
   padding:10px 20px;
   border-radius:6px;
   text-decoration:none;
}
a:hover { background:#008000; }
</style>
</head>
<body>

<div class="message-box">
   <h2>Invoice Status</h2>
   <p class="<?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'error'; ?>">
      <?php echo $message; ?>
   </p>
   <a href="admin_orders.php">Go Back to Orders</a>
</div>

</body>
</html>
