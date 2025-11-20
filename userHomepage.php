<?php
session_start();
include("connect.php");
include_once 'sidebar.php';

// FIXED: Check if user is logged in and is a 'user' role
if (!isset($_SESSION['email']) || !isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: RegLog.php");
    exit();
}

$email = $_SESSION['email'];
$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

$query = mysqli_query($conn, "SELECT firstName FROM users WHERE email='$email'");
$firstName = ($row = mysqli_fetch_assoc($query)) ? htmlspecialchars($row['firstName']) : 'User';

$newsQuery = "SELECT * FROM news_updates ORDER BY date_posted DESC";
$newsResult = mysqli_query($conn, $newsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>413 Central Homepage</title>
  <link rel="stylesheet" href="plugins/userHomepage.css" />
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
</head>
<body>
  <div class="home">
    <div class="homepage-wrapper">
      <p class="greeting">Kumusta, <?php echo $firstName; ?>?</p>
      <p class="sub-label">Request Status</p>
      
      <div class="container">
        <div class="wrapper">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <?php
                $user_id = $_SESSION['user_id'];
                // FIXED: Only show requests from the logged-in user
                $sql = "
                    SELECT id, date_requested, 'Barangay Clearance' AS type, clearPur AS purpose, status AS status 
                    FROM brgyclearance
                    WHERE user_id = ?
                    UNION ALL
                    SELECT id, date_requested, 'Barangay ID' AS type, NULL AS purpose, status AS status 
                    FROM brgyid
                    WHERE user_id = ?
                    UNION ALL
                    SELECT id, date_requested, 'Barangay Indigency' AS type, purpose AS purpose, status AS status 
                    FROM brgyindigency
                    WHERE user_id = ?
                    UNION ALL
                    SELECT id, date_requested, 'Business Clearance' AS type, NULL AS purpose, status AS status 
                    FROM busclearance
                    WHERE user_id = ?
                    UNION ALL
                    SELECT id, date_requested, 'Complaint' AS type, issue AS purpose, status AS status 
                    FROM complaints
                    WHERE user_id = ?
                    ORDER BY date_requested DESC
                ";
                

                // Use prepared statement to prevent SQL injection
                if ($stmt = $conn->prepare($sql)) {
                    // Bind the user_id parameter 5 times (once for each table)
                    $stmt->bind_param("iiiii", $user_id, $user_id, $user_id, $user_id, $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        echo '<table class="table">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Requested on</th>";
                        echo "<th>Type of Document</th>";
                        echo "<th>Purpose</th>";
                        echo "<th>Status</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        while ($row = $result->fetch_assoc()) {
                            $statusClass = '';
                            $statusText = ucfirst(strtolower($row['status']));

                            switch (strtoupper($row['status'])) {
                                case 'PENDING':
                                    $statusClass = 'status-badge status-pending';
                                    break;
                                case 'REJECTED':
                                    $statusClass = 'status-badge status-rejected';
                                    break;
                                case 'APPROVED':
                                    $statusClass = 'status-badge status-approved';
                                    break;
                                case 'PROCESSING':
                                    $statusClass = 'status-badge status-approved';
                                    break;
                                case 'RESOLVED':
                                    $statusClass = 'status-badge status-approved';
                                    break;
                                default:
                                    $statusClass = 'status-badge';
                            }

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['date_requested']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['type']) . "</td>";
                            echo '<td style="word-wrap: break-word; max-width: 300px;">' . (!empty($row['purpose']) ? htmlspecialchars($row['purpose']) : '-') . "</td>";
                            echo "<td><span class=\"$statusClass\">$statusText</span></td>";
                            echo "</tr>";
                        }

                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo '<div class="alert alert-info" style="text-align:center;padding:20px;"><em>You have no requests yet. Submit a request to get started!</em></div>';
                    }
                    $stmt->close();
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                $conn->close();
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- News Carousel Section -->
      <div class="news-carousel-wrapper">
        <p class="sub-label">News Updates</p>
        <div class="carousel-container">
          <button class="carousel-btn left-btn"><i class='bx bx-chevron-left'></i></button>
          <div class="carousel-track">
            <?php if (mysqli_num_rows($newsResult) > 0): ?>
              <?php while ($news = mysqli_fetch_assoc($newsResult)): ?>
                <div class="news-card">
                  <h6><?php echo htmlspecialchars($news['title']); ?></h6>
                  <p><?php echo nl2br(htmlspecialchars($news['content'])); ?></p>
                  <small>Posted by <?php echo htmlspecialchars($news['posted_by']); ?> on <?php echo date("F j, Y g:i A", strtotime($news['date_posted'])); ?></small>
                </div>
              <?php endwhile; ?>
            <?php else: ?>
              <p>No announcements yet.</p>
            <?php endif; ?>
          </div>
          <button class="carousel-btn right-btn"><i class='bx bx-chevron-right'></i></button>
        </div>
      </div>

      <!-- FAQs Section -->
      <p class="sub-label">Frequently Asked Questions (FAQs)</p>
      <div class="faq-section">
  <div class="faq-item">
    <button class="faq-question">
      What are the official office hours of the Barangay Hall?
      <i class='bx bx-chevron-down'></i>
    </button>
    <div class="faq-answer">
      <p>
        Our standard office hours are Monday to Friday, from 7:00 am to 10:00 pm. We observe no noon break for front-line services.
        Please note: Weekend hours and hours for specific services like health centers or late-night security may vary.
        Please check the 'Contact Us' page for more details.
      </p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">
      How do I report a concern or request a service (e.g., street light repair, garbage collection, stray animals)?
      <i class='bx bx-chevron-down'></i>
    </button>
    <div class="faq-answer">
      <p>
        Online: Use our "Isumbong mo kay Kap!" forms on this website.
      <br>  In-Person: Visit the Barangay Hall and proceed to the Secretary's Office or Action Center.
      <br>  Hotline: Call our official hotline at 0912-345-6789.
      </p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">
      Where can I find the contact information for Barangay Officials and Department Heads?
      <i class='bx bx-chevron-down'></i>
    </button>
    <div class="faq-answer">
      <p>
        The complete list of elected officials and contact details for key departments (e.g., Treasurer, Health Center, Tanod) is available on our Barangay Officials' page.
      </p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">
      How much is the fee for a Barangay Clearance?
      <i class='bx bx-chevron-down'></i>
    </button>
    <div class="faq-answer">
      <p>
        The standard fee for a Barangay Clearance PHP 50.00. The fee may vary slightly depending on the purpose (e.g., for employment vs. for a business permit). Please confirm the exact amount at the Treasurer's Office upon application.
      </p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">
      What is the process and what are the requirements for getting a Barangay Clearance/Certificate of Residency?
      <i class='bx bx-chevron-down'></i>
    </button>
    <div class="faq-answer">
      <p>
        Requirements generally include:
        <br> - Valid Government-Issued ID (e.g., Driver's License, Passport, Voter's ID).
        <br> - Proof of Residency (e.g., utility bill under your name or a lease contract).
        <br> - Community Tax Certificate (Cedula) for the current year.
        <br> - Payment of the corresponding fee (fees vary depending on the purpose).
        <br> For procedure, please visit our 'Request Document' page or contact the Barangay Hall directly.
      </p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">
      How do I report an emergency or a crime within the Barangay?
      <i class='bx bx-chevron-down'></i>
    </button>
    <div class="faq-answer">
      <p>
        - For immediate emergencies (fire, medical, crime in progress), call the National Emergency Hotline 911 or the Barangay Tanod/Security Hotline at [Insert Tanod Hotline Number].
        <br> - For non-emergency security concerns, you can visit the Barangay Tanod Outpost or the Barangay Hall.
      </p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">
      How can I register to vote in the Barangay?
      <i class='bx bx-chevron-down'></i>
    </button>
    <div class="faq-answer">
      <p>
        Voter registration is handled by the Commission on Elections (COMELEC). The Barangay does not handle voter registration directly. We will post announcements on this website and on our official bulletin board regarding the schedules and locations of COMELEC's registration drives in our area.
      </p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">
      What social services and health programs does the Barangay offer?
      <i class='bx bx-chevron-down'></i>
    </button>
    <div class="faq-answer">
      <p>
       The Barangay regularly offers various programs, which may include: Free medical/dental checkups at the Barangay Health Center, Vaccination programs (e.g., for flu, COVID-19), Livelihood training and seminars, and Assistance to the elderly, PWDs, and children (through the BHW and BSW).
      </p>
    </div>
  </div>
</div>

    </div>

  <!-- Include Footer -->
     <?php include_once 'footer.php'; ?>

  </div>

  
  <script src="source/userHomepage.js"></script>
  
  <!-- Include Footer -->

</body>
</html>