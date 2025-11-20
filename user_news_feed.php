<?php

session_start();
include 'connect.php';
include_once 'sidebar.php';

$sql = "SELECT * FROM news_updates ORDER BY date_posted DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Barangay News & Updates</title>
  <link rel="icon" href="website_icon/webicon.png" type="image/png" sizes="64x64">
  <link rel="stylesheet" href="plugins/userHomepage.css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
  <div class="home">
    <div class="homepage-wrapper">
      
      <!-- Page Header -->
      <div class="page-header">
        <div>
          <h2>
            <i class='bx bxs-news'></i>
            News & Updates
          </h2>
          <p>Stay informed with the latest announcements and updates from Barangay 413</p>
        </div>
      </div>

      <!-- News Grid -->
      <?php if ($result->num_rows > 0): ?>
        <div class="news-grid">
          <?php while ($row = $result->fetch_assoc()): 
            // Get first letter of posted_by for avatar
            $firstLetter = strtoupper(substr($row['posted_by'], 0, 1));
            
            // Format date
            $datePosted = date("M j, Y", strtotime($row['date_posted']));
            $timePosted = date("g:i A", strtotime($row['date_posted']));
          ?>
            <div class="news-card-modern">
              
              <!-- Card Header with Title -->
              <div class="news-card-header">
                <h3 class="news-card-title"><?php echo htmlspecialchars($row['title']); ?></h3>
              </div>

              <!-- Card Body with Content -->
              <div class="news-card-body">
                <div class="news-card-content">
                  <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                </div>

                <!-- Card Footer with Author & Date -->
                <div class="news-card-footer">
                  <div class="news-author">
                    <div class="author-avatar">
                      <?php echo $firstLetter; ?>
                    </div>
                    <div class="author-info">
                      <p class="author-name"><?php echo htmlspecialchars($row['posted_by']); ?></p>
                      <p class="author-role">Barangay Official</p>
                    </div>
                  </div>
                  
                  <div class="news-date">
                    <i class='bx bx-calendar'></i>
                    <span><?php echo $datePosted; ?></span>
                  </div>
                </div>
              </div>

            </div>
          <?php endwhile; ?>
        </div>

      <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
          <i class='bx bx-news'></i>
          <h3>No News Available</h3>
          <p>There are currently no news updates. Please check back later.</p>
        </div>
      <?php endif; ?>

    </div>

    <!-- Include Footer -->
    <?php include_once 'footer.php'; ?>
  </div>

  <script>
    // Add smooth scroll behavior
    document.addEventListener('DOMContentLoaded', function() {
      const newsCards = document.querySelectorAll('.news-card-modern');
      
      // Add stagger animation on load
      newsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
          card.style.transition = 'all 0.5s ease';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, index * 100);
      });
    });
  </script>
</body>
</html>