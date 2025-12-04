<!-- admin_sidebar.php -->
<div class="sidebar">
   <h2>Admin Panel</h2>
   <a href="admin_page.php"><i class="fas fa-home"></i> Dashboard</a>
   <a href="admin_products.php"><i class="fas fa-box"></i> Products</a>
   <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
   <a href="admin_users.php"><i class="fas fa-users"></i> Users</a>
   <a href="admin_contacts.php"><i class="fas fa-envelope"></i> Messages</a>
   <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<style>
   .sidebar {
      width: 240px;
      background: #6B4226;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding: 25px 20px;
      display: flex;
      flex-direction: column;
      box-shadow: 3px 0 10px rgba(0,0,0,0.2);
   }

   .sidebar h2 {
      color: #fff;
      margin-bottom: 35px;
      font-size: 22px;
      text-transform: uppercase;
      letter-spacing: 1px;
      text-align: center;
   }

   .sidebar a {
      color: #fff;
      text-decoration: none;
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
      font-weight: 500;
   }

   .sidebar a:hover {
      background-color: #008000;
   }

   .sidebar a.logout {
      margin-top: 15px;
      background: #008000;
      color: #fff;
      text-align: center;
      font-weight: 600;
      transition: 0.3s;
   }

   .sidebar a.logout:hover {
      background: #fff; 
      color: #6B4226;
   }

   @media (max-width: 768px) {
      .sidebar {
         position: static;
         width: 100%;
         height: auto;
         flex-direction: row;
         justify-content: space-around;
      }
   }
</style>
