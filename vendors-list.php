<?php
include 'navbar.php';
include 'db_connect.php';

$service_type = $_GET['service'] ?? '';

$service_mapping = [
    'plumbing' => 'Plumber',
    'electrician' => 'Electrician', 
    'cleaning' => 'Cleaning',
    'painter' => 'Painter',
    'acrepair' => 'AC Repair',
    'carpenter' => 'Carpenter',
    'mason' => 'Mason',
    'pestcontrol' => 'Pest Control'
];

$db_service_type = $service_mapping[$service_type] ?? $service_type;

$service_data = [
    'Plumbing' => ['Plumber', 'plumber.png'],
    'Electrician' => ['Electrician', 'electrician.png'],
    'Cleaning' => ['Cleaning', 'cleaner1.png'],
    'Painter' => ['Painter', 'painter.png'],
    'ACRepair' => ['AC Repair', 'acRepair1.png'],
    'Carpenter' => ['Carpenter', 'carpenter.png'],
    'Mason' => ['Mason', 'mason.png'],
    'PestControl' => ['Pest Control', 'pestControl.jpg']
];

$service_name = $service_data[$db_service_type][0] ?? 'Unknown Service';
$service_image = $service_data[$db_service_type][1] ?? 'default.png';

$sql = "SELECT * FROM vendors WHERE service_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $db_service_type); // Use $db_service_type here
$stmt->execute();
$result = $stmt->get_result();
$vendors = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $service_name; ?> Vendors - Local Service Finder</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .vendor-card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 25px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
    }
    
    .vendor-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
    
    /* Vendor Profile Picture Styles */
    .vendor-profile-pic {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #2563eb;
      margin-bottom: 15px;
    }
    
    .vendor-profile-pic-placeholder {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: #2563eb;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 2rem;
      margin-bottom: 15px;
      border: 3px solid #2563eb;
    }
    
    .vendor-info {
      text-align: left;
      margin-top: 15px;
    }
    
    .vendor-info p {
      margin: 8px 0;
      color: #555;
    }
    
    .vendor-info strong {
      color: #333;
    }
    
    .service-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .service-header img {
      width: 80px;
      height: 80px;
      object-fit: contain;
      margin-bottom: 15px;
    }
    
    .book-btn {
      background: #2563eb;
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 15px;
      font-size: 14px;
      font-weight: 500;
      transition: background 0.3s;
      width: 100%;
    }
    
    .book-btn:hover {
      background: #1e40af;
    }
    
    .services-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 25px;
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <section class="services-section">
    <div class="service-header">
      <img src="images/<?php echo $service_image; ?>" alt="<?php echo $service_name; ?>">
      <h2><?php echo $service_name; ?> Vendors</h2>
      <p style="color: #666;">Available service providers in your area</p>
    </div>
    
    <?php if(count($vendors) > 0): ?>
      <div class="services-grid">
        <?php foreach($vendors as $vendor): ?>
          <div class="vendor-card">
            <!-- Vendor Profile Picture -->
            <div class="vendor-profile">
              <?php 
              $vendor_profile_pic = !empty($vendor['profile_image']) ? $vendor['profile_image'] : 'default.png';
              ?>
              
              <?php if ($vendor_profile_pic && $vendor_profile_pic !== 'default.png'): ?>
                <img src="uploads/profiles/<?php echo $vendor_profile_pic; ?>" 
                     alt="<?php echo htmlspecialchars($vendor['name']); ?>" 
                     class="vendor-profile-pic">
              <?php else: ?>
                <div class="vendor-profile-pic-placeholder">
                  <i class="fa-solid fa-user"></i>
                </div>
              <?php endif; ?>
            </div>
            
            <h3 style="color: #2563eb; margin-bottom: 15px;"><?php echo htmlspecialchars($vendor['name']); ?></h3>
            
            <div class="vendor-info">
              <p><strong>📞 Phone:</strong> <?php echo htmlspecialchars($vendor['phone']); ?></p>
              <p><strong>📍 Location:</strong> <?php echo htmlspecialchars($vendor['location']); ?></p>
              <p><strong>📧 Email:</strong> <?php echo htmlspecialchars($vendor['email']); ?></p>
              
              <!-- Pricing Information -->
              <div style="margin-top: 10px; padding: 10px; background: #e8f5e8; border-radius: 6px; border-left: 4px solid #28a745;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                  <div>
                    <strong>💰 Hourly Rate:</strong> TK <?php echo number_format($vendor['hourly_rate'], 2); ?>
                  </div>
                  <div>
                    <strong>💼 Min. Charge:</strong> TK <?php echo number_format($vendor['min_charge'], 2); ?>
                  </div>
                </div>
              </div>

              <!-- Service Description -->
              <?php if(!empty($vendor['service_description'])): ?>
                <div style="margin-top: 10px; padding: 10px; background: #fff3cd; border-radius: 6px; border-left: 4px solid #ffc107;">
                  <strong>📝 Service Details:</strong>
                  <p style="margin: 5px 0 0 0; font-size: 0.9rem; color: #856404;"><?php echo htmlspecialchars($vendor['service_description']); ?></p>
                </div>
              <?php endif; ?>

              <!-- Ratings Display -->
              <div style="margin-top: 10px;">
                <?php if($vendor['avg_rating'] > 0): ?>
                  <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="color: #ffc107;">
                      <?php
                      $full_stars = floor($vendor['avg_rating']);
                      $half_star = ($vendor['avg_rating'] - $full_stars) >= 0.5;
                      $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                      
                      for($i = 0; $i < $full_stars; $i++) {
                          echo '⭐';
                      }
                      if($half_star) {
                          echo '⭐';
                      }
                      for($i = 0; $i < $empty_stars; $i++) {
                          echo '☆';
                      }
                      ?>
                    </div>
                    <span style="font-weight: 500; color: #666;">
                      <?php echo number_format($vendor['avg_rating'], 1); ?> (<?php echo $vendor['total_reviews']; ?> reviews)
                    </span>
                  </div>
                <?php else: ?>
                  <div style="color: #666; font-size: 0.9rem;">
                    No ratings yet
                  </div>
                <?php endif; ?>
              </div>
              <!-- End Ratings Display -->
              
              <div style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px; border-left: 4px solid #2563eb;">
                <strong>Service Provided: </strong><?php echo $service_name; ?>
              </div>
              
              <!-- Booking Button -->
              <button onclick="openBookingModal(<?php echo $vendor['id']; ?>, '<?php echo $service_name; ?>')" 
                      class="book-btn">
                <i class="fa-solid fa-calendar-check"></i> Book This Service
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div style="text-align: center; padding: 60px 20px;">
        <i class="fa-solid fa-users-slash" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
        <h3 style="color: #666; margin-bottom: 15px;">No Vendors Available</h3>
        <p style="color: #888; margin-bottom: 25px;">There are no <?php echo strtolower($service_name); ?> vendors registered yet.</p>
        <a href="services.php" class="login-btn" style="display: inline-block; margin-top: 10px; text-decoration: none;">Back to Services</a>
      </div>
    <?php endif; ?>
    
    <div style="text-align: center; margin-top: 40px;">
      <a href="services.php" style="color: #2563eb; text-decoration: none; font-weight: 500; font-size: 1.1rem;">
        <i class="fa-solid fa-arrow-left"></i> Back to All Services
      </a>
    </div>
  </section>

  <!-- Booking Modal -->
  <div id="bookingModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 500px;">
      <h3>Book Service</h3>
      <form id="bookingForm" action="book-service.php" method="POST">
        <input type="hidden" name="vendor_id" id="vendor_id">
        <input type="hidden" name="service_type" id="service_type">
        
        <div style="margin-bottom: 15px;">
          <label>Service Description:</label>
          <textarea name="service_description" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="Describe what you need..." required></textarea>
        </div>
        
        <div style="margin-bottom: 15px;">
          <label>Preferred Date & Time:</label>
          <input type="datetime-local" name="booking_date" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
        </div>
        
        <div style="margin-bottom: 15px;">
          <label>Your Address:</label>
          <textarea name="address" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="Enter your full address..." required></textarea>
        </div>
        
        <div style="display: flex; gap: 10px;">
          <button type="submit" style="background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Book Now</button>
          <button type="button" onclick="closeBookingModal()" style="background: #666; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Cancel</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-column">
      <h4>About Us</h4>
      <p>Local Service helps you connect with trusted providers in your area quickly and securely.</p>
    </div>
    <div class="footer-column">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
    </div>
    <div class="footer-column">
      <h4>Popular Services</h4>
      <ul>
        <li><a href="#">Plumber</a></li>
        <li><a href="#">Electrician</a></li>
        <li><a href="#">Cleaning</a></li>
        <li><a href="#">Painter</a></li>
      </ul>
    </div>
  </footer>

  <script>
  function openBookingModal(vendorId, serviceType) {
      
      // Check if user is logged in
      
      <?php if(!isset($_SESSION['user_id'])): ?>
          alert('Please login first to book a service!');
          window.location.href = 'login.php';
          return;
      <?php endif; ?>
      
      document.getElementById('vendor_id').value = vendorId;
      document.getElementById('service_type').value = serviceType;
      document.getElementById('bookingModal').style.display = 'flex';
  }

  function closeBookingModal() {
      document.getElementById('bookingModal').style.display = 'none';
  }

  document.getElementById('bookingModal').addEventListener('click', function(e) {
      if (e.target.id === 'bookingModal') {
          closeBookingModal();
      }
  });
  </script>
</body>
</html>
