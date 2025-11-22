
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="navbar">
  <a href="index.php" class="logoLink">
    <div class="logo">
      <i class="fa-solid fa-location-dot"></i>
      <span class="logoTxt">Local Service Finder</span>
    </div>
  </a>
  <nav>
    <a href="index.php">Home</a>
    <a href="services.php">Services</a>
    <a href="about.php">About</a>
    <a href="contact.php">Contact</a>
    
    <?php if(isset($_SESSION['vendor_id'])): ?>
      <!-- Show vendor options when vendor logged in -->
      <a href="vendor-dashboard.php">Vendor Dashboard</a>
      <a href="vendor-logout.php" class="login-btn">Vendor Logout</a>
      
    <?php elseif(isset($_SESSION['user_id'])): ?>
      <!-- Show user options when user  logged in -->
      <a href="cDashboard.php">Dashboard</a>
      <a href="cLogout.php" class="login-btn">Logout</a>
      
    <?php else: ?>
      <!-- Show login options for everyone -->
      <a href="vendor.php">Become a Vendor</a>
      <a href="login.php" class="login-btn">User LogIn</a>
    <?php endif; ?>
  </nav>

</header>
