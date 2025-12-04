<?php
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
   exit();
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contacts.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Messages</title>

<!-- font awesome cdn link -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
    --green: #008000;
    --brown: #6B4226;
}

*{box-sizing:border-box;margin:0;padding:0;font-family:"Poppins",sans-serif;}
body{background:#f7f7f7;color:#222;display:flex;min-height:100vh;}

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
    margin-left:260px;
    padding:30px;
    width:calc(100% - 260px);
}
.title{color:var(--brown);font-size:28px;margin-bottom:20px;text-transform:uppercase;}

/* Messages Box Container */
.box-container{
    display:grid;
    grid-template-columns: repeat(auto-fill,minmax(300px,1fr));
    gap:20px;
}

.box{
    background:#fff;
    border-radius:10px;
    padding:20px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}
.box:hover{transform:translateY(-5px);box-shadow:0 5px 15px rgba(0,0,0,0.15);}
.box p{margin:10px 0;font-size:16px;}
.box p span{font-weight:500;}
.box .delete-btn{
    display:inline-block;
    background:var(--green);
    color:#fff;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    margin-top:10px;
    transition: background 0.3s;
}
.box .delete-btn:hover{background:#056b00;}

/* Responsive */
@media(max-width:720px){
    .main-content{margin-left:0;padding:20px;}
    .box-container{grid-template-columns:1fr;}
}
</style>

</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h1 class="title">Messages</h1>
    <div class="box-container">
        <?php
        $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
        if(mysqli_num_rows($select_message) > 0){
            while($fetch_message = mysqli_fetch_assoc($select_message)){
        ?>
        <div class="box">
            <p>User ID : <span><?php echo $fetch_message['user_id']; ?></span></p>
            <p>Name : <span><?php echo $fetch_message['name']; ?></span></p>
            <p>Number : <span><?php echo $fetch_message['number']; ?></span></p>
            <p>Email : <span><?php echo $fetch_message['email']; ?></span></p>
            <p>Message : <span><?php echo $fetch_message['message']; ?></span></p>
            <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');" class="delete-btn">
                <i class="fas fa-trash"></i> Delete
            </a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">You have no messages!</p>';
        }
        ?>
    </div>
</div>

<script src="js/admin_script.js"></script>
</body>
</html>
