<?php
@include 'config.php';

if (session_status() === PHP_SESSION_NONE) {
   session_start();
}

$user_id = $_SESSION['user_id'] ?? null;

$user = null;
$wishlist_num_rows = 0;
$cart_num_rows = 0;

if (!isset($_SESSION['wishlist'])) {
   $_SESSION['wishlist'] = [];
}
if (!isset($_SESSION['cart'])) {
   $_SESSION['cart'] = [];
}

if ($user_id) {
   $user_query = mysqli_query($conn, "SELECT name, email, image FROM users WHERE id = '$user_id'");
   $user = mysqli_fetch_assoc($user_query);

   $wishlist_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM wishlist WHERE user_id = '$user_id'");
   $wishlist_num_rows = mysqli_fetch_assoc($wishlist_result)['total'];

   $cart_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM cart WHERE user_id = '$user_id'");
   $cart_num_rows = mysqli_fetch_assoc($cart_result)['total'];
} else {
   $wishlist_num_rows = count($_SESSION['wishlist']);
   $cart_num_rows = count($_SESSION['cart']);
}
?>

<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>';
   }
}
?>

<header class="header">
   <div class="flex">

      <a href="home.php" class="logo">
         <img src="teaimg/download.png" alt="Logo" class="logo-img">
         <span>BotaniQ.</span>
      </a>

<style>
/* --- LOGO --- */
.logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    font-size: 24px;
    font-weight: 700;
    color: #008000;
}
.logo-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
    object-fit: cover;
}

/* --- NAVIGATION --- */
.navbar {
    position: absolute;
    top: 100%;
    right: -100%;
    background: #fff;
    width: 260px;
    padding: 20px;
    transition: 0.3s ease;
    z-index: 999;
}
.navbar.active {
    right: 0;
}
.navbar ul {
    list-style: none;
}
.navbar ul li {
    position: relative;
}
.navbar ul li ul {
    position: absolute;
    top: 120%;
    left: 0;
    background: #fff;
    display: none;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.navbar ul li:hover ul {
    display: block;
}

/* Desktop */
@media (min-width: 768px) {
    .navbar {
        position: static;
        right: 0;
        width: auto;
        background: none;
        padding: 0;
        display: flex;
    }
    .navbar ul {
        display: flex;
        gap: 20px;
    }
}

/* --- ICONS AREA --- */
.icons {
    display: flex;
    align-items: center;
    gap: 15px;
}

/* Icon button */
.icon-btn {
    font-size: 20px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 3px;
}
.icon-btn i {
    font-size: 21px;
}

/* Hamburger */
#menu-btn {
    font-size: 24px;
    cursor: pointer;
    display: none;
}
@media (max-width: 768px) {
    #menu-btn {
        display: block;
    }
}

/* User Avatar Dropdown */
.user-dropdown {
    position: relative;
}
.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    cursor: pointer;
    object-fit: cover;
}
.dropdown-menu {
    display: none;
    position: absolute;
    top: 120%;
    right: 0;
    background: #fff;
    min-width: 180px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    padding: 10px 0;
    border-radius: 5px;
}
.dropdown-menu.show {
    display: block;
}
</style>

<nav class="navbar">
   <ul>
      <li><a href="home.php">home</a></li>
      <li><a href="#">pages +</a>
         <ul>
            <li><a href="about.php">about</a></li>
            <li><a href="contact.php">contact</a></li>
         </ul>
      </li>
      <li><a href="shop.php">shop</a></li>
      <li><a href="#">account +</a>
         <ul>
            <?php if ($user_id): ?>
               <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
               <li><a href="login.php">Login</a></li>
               <li><a href="register.php">Register</a></li>
            <?php endif; ?>
         </ul>
      </li>
   </ul>
</nav>

<div class="icons">

   <a href="search_page.php" class="icon-btn">
      <i class="fas fa-search"></i>
   </a>

   <a href="wishlist.php" class="icon-btn">
      <i class="fas fa-heart"></i><span>(<?= $wishlist_num_rows ?>)</span>
   </a>

   <a href="cart.php" class="icon-btn">
      <i class="fas fa-shopping-cart"></i><span>(<?= $cart_num_rows ?>)</span>
   </a>

   <!-- Hamburger at the END -->
   <div id="menu-btn" class="fas fa-bars"></div>

   <?php if ($user_id): ?>
   <div class="user-dropdown">
      <div class="dropdown-toggle">
         <img src="<?= !empty($user['image']) ? 'uploaded_images/'.$user['image'] : 'images/default-avatar.png' ?>"
              class="user-avatar">
      </div>
      <ul class="dropdown-menu">
         <li class="dropdown-header"><?= htmlspecialchars($user['name']) ?></li>
         <li><a href="my_profile.php">My Profile</a></li>
         <li><a href="logout.php">Logout</a></li>
      </ul>
   </div>
   <?php endif; ?>
</div>

   </div>
</header>

<script>
// Close dropdowns
document.addEventListener("click", function () {
    document.querySelectorAll(".dropdown-menu").forEach(menu => menu.classList.remove("show"));
});

// Toggle user menu
const dropdownToggle = document.querySelector(".dropdown-toggle");
if (dropdownToggle) {
    dropdownToggle.addEventListener("click", function (e) {
        e.stopPropagation();
        document.querySelector(".dropdown-menu").classList.toggle("show");
    });
}

// Hamburger
const menuBtn = document.querySelector('#menu-btn');
const navbar = document.querySelector('.navbar');

menuBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    navbar.classList.toggle('active');
});

// Close when clicking outside
document.addEventListener('click', function () {
    navbar.classList.remove('active');
});
</script>
