<?php
@include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
   header('location:login.php');
   exit();
}

// Delete user
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `users` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_users.php');
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Users</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
    --green: #008000;
    --brown: #6B4226;
    --light-bg: #f8f8f8;
    --shadow: rgba(0, 0, 0, 0.1);
}

*{box-sizing:border-box;margin:0;padding:0;font-family:"Poppins",sans-serif;}
body{background:var(--light-bg);color:#222;display:flex;min-height:100vh;}

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

/* User Cards */
.user-card{
    background:#fff;
    border-radius:10px;
    box-shadow:0 3px 10px var(--shadow);
    padding:20px;
    margin-bottom:20px;
    position:relative;
    transition:0.3s ease;
}
.user-card:hover{transform:translateY(-3px);}

/* Top Section with Buttons */
.user-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.user-info{
    display:flex;
    flex-direction:column;
}
.user-info h3{
    font-size:18px;
    color:var(--brown);
    margin-bottom:4px;
}
.user-info span{
    font-size:14px;
    color:#555;
}

/* Action Buttons */
.action-buttons{
    display:flex;
    gap:10px;
}
.action-btn{
    background:var(--green);
    border:none;
    color:#fff;
    padding:8px 12px;
    border-radius:8px;
    cursor:pointer;
    transition:0.3s;
}
.action-btn.delete{background:#c0392b;}
.action-btn:hover{opacity:0.8;}

/* Hidden Details */
.user-details{
    display:none;
    margin-top:15px;
    background:#f9f9f9;
    padding:15px;
    border-radius:8px;
    border-left:4px solid var(--green);
}
.user-details p{margin:8px 0;font-size:15px;}
.user-details span{font-weight:500;}

/* Responsive */
@media(max-width:720px){
    .main-content{margin-left:0;padding:20px;}
}
</style>

</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h1 class="title">Users Account</h1>

    <?php
    $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
    if(mysqli_num_rows($select_users) > 0){
        while($fetch_users = mysqli_fetch_assoc($select_users)){
    ?>
    <div class="user-card">
        <div class="user-header">
            <div class="user-info">
                <h3><?php echo $fetch_users['name']; ?></h3>
                <span><?php echo $fetch_users['email']; ?></span>
            </div>
            <div class="action-buttons">
                <button class="action-btn view"><i class="fas fa-eye"></i></button>
                <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                </a>
            </div>
        </div>

        <div class="user-details">
            <p><span>User ID:</span> <?php echo $fetch_users['id']; ?></p>
            <p><span>Email:</span> <?php echo $fetch_users['email']; ?></p>
            <p><span>Phone:</span> <?php echo !empty($fetch_users['phone']) ? $fetch_users['phone'] : 'Not provided'; ?></p>
            <p><span>Address:</span> <?php echo !empty($fetch_users['address']) ? $fetch_users['address'] : 'Not provided'; ?></p>
            <p><span>User Type:</span> 
                <strong style="color:<?php echo $fetch_users['user_type']=='admin'?'orange':'#008000'; ?>">
                <?php echo ucfirst($fetch_users['user_type']); ?>
                </strong>
            </p>
        </div>
    </div>
    <?php
        }
    } else {
        echo '<p style="color:#666;">No users found!</p>';
    }
    ?>
</div>

<script>
// Toggle details visibility
document.querySelectorAll('.view').forEach((btn)=>{
    btn.addEventListener('click', ()=>{
        const details = btn.closest('.user-card').querySelector('.user-details');
        details.style.display = (details.style.display === 'block') ? 'none' : 'block';
        btn.classList.toggle('active');
        btn.innerHTML = btn.classList.contains('active') ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    });
});
</script>

</body>
</html>
