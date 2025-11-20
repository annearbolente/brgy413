<?php
session_start();

include 'connect.php'; 
include_once 'sidebar.php';

$user = $_SESSION['user'] ?? null;
$display_message = '';  
$messageType = '';
$formSubmitted = false;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['contact'])) {  
    $fullname = trim($_POST['fullname'] ?? '');  
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $suggestions = trim($_POST['suggestions'] ?? '');

    $user_id = $user['id'] ?? null;

    // Updated query to include rating and suggestions
    $query = "INSERT INTO contact (user_id, fullname, email, subject, message, rating, suggestions) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        $display_message = "Database prepare error: " . $conn->error;
        $messageType = 'error';
    } else {
        $stmt->bind_param("issssss", $user_id, $fullname, $email, $subject, $message, $rating, $suggestions);
        $result = $stmt->execute();
        if (!$result) {
            $display_message = "Database insert error: " . $stmt->error;
            $messageType = 'error';
        } else {
            $mail = new PHPMailer(true);
            try {                      
                $mail->isSMTP();                                            
                $mail->Host       = 'smtp.gmail.com';  
                $mail->SMTPAuth   = true;                                   
                $mail->Username   = 'brgy413.official@gmail.com';                     
                $mail->Password   = 'fwdu yrrq nssy yzna';                               
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;                                   

                $mail->setFrom($email, $fullname);                
                $mail->addAddress('brgy413.official@gmail.com', 'Barangay 413 Official Account');
                $mail->addReplyTo($email, $fullname);
                
                // Enhanced email content with rating and suggestions
                $mail->isHTML(true);                  
                $mail->Subject = $subject;
                $emailBody = "<h3>New Contact Form Submission</h3>";
                $emailBody .= "<p><strong>From:</strong> " . htmlspecialchars($fullname) . "</p>";
                $emailBody .= "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
                $emailBody .= "<p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>";
                $emailBody .= "<p><strong>Rating:</strong> " . str_repeat("⭐", $rating) . " ($rating/5)</p>";
                $emailBody .= "<p><strong>Message:</strong></p>";
                $emailBody .= "<p>" . nl2br(htmlspecialchars($message)) . "</p>";
                if (!empty($suggestions)) {
                    $emailBody .= "<p><strong>Website Improvement Suggestions:</strong></p>";
                    $emailBody .= "<p>" . nl2br(htmlspecialchars($suggestions)) . "</p>";
                }
                $mail->Body = $emailBody;

                $mail->send();
                $display_message = 'Message has been sent successfully!';
                $messageType = 'success';
                $formSubmitted = true;
            } catch (Exception $e) {
                $display_message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                $messageType = 'error';
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="icon" href="website_icon/webicon.png" type="image/png" sizes="64x64">
    <link rel="stylesheet" href="plugins/adminHomepage.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>
        /* Contact Page Specific Styles */
        .contact-layout {
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-top: 20px;
        }

        .contact-info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .contact-info-card {
            background: white;
            border-radius: 12px;
            padding: 25px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
        }

        .contact-info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .contact-card-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #182052 0%, #f2c516 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .contact-card-icon i {
            font-size: 28px;
            color: white;
        }

        .contact-info-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: #182052;
            margin-bottom: 8px;
        }

        .contact-info-card p {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
            margin: 0;
        }

        .contact-form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .form-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-card-header i {
            font-size: 28px;
            color: #182052;
        }

        .form-card-header h2 {
            font-size: 22px;
            font-weight: 600;
            color: #182052;
            margin: 0;
        }

        .contact-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-group label i {
            color: #182052;
            font-size: 16px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: all 0.3s ease;
            background: white;
            color: #333;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #182052;
            box-shadow: 0 0 0 3px rgba(24, 32, 82, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            line-height: 1.6;
        }

        /* Star Rating Styles */
        .rating-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .rating-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .rating-label i {
            color: #182052;
            font-size: 16px;
        }

        .stars-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .star-rating {
            display: flex;
            gap: 8px;
            font-size: 32px;
        }

        .star {
            cursor: pointer;
            color: #ddd;
            transition: all 0.2s ease;
        }

        .star:hover,
        .star.active {
            color: #f2c516;
            transform: scale(1.1);
        }

        .star.hovered {
            color: #f2c516;
        }

        .rating-text {
            font-size: 14px;
            color: #666;
            font-weight: 500;
            min-width: 100px;
        }

        .rating-description {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 10px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .toast {
            position: fixed;
            top: 100px;
            right: 30px;
            background: white;
            padding: 18px 25px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            display: none;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            border-left: 4px solid #28a745;
            animation: slideIn 0.3s ease;
        }

        .toast.error {
            border-left-color: #dc3545;
        }

        .toast.show {
            display: flex;
        }

        .toast i {
            font-size: 24px;
            color: #28a745;
        }

        .toast.error i {
            color: #dc3545;
        }

        .toast span {
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media screen and (max-width: 1200px) {
            .contact-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media screen and (max-width: 768px) {
            .contact-info-grid {
                grid-template-columns: 1fr;
            }

            .form-row-2 {
                grid-template-columns: 1fr;
            }

            .contact-form-container {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }

            .toast {
                right: 15px;
                left: 15px;
                top: 80px;
            }

            .stars-wrapper {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }

        @media screen and (max-width: 480px) {
            .contact-card-icon {
                width: 50px;
                height: 50px;
            }

            .contact-card-icon i {
                font-size: 24px;
            }

            .contact-info-card h3 {
                font-size: 15px;
            }

            .contact-info-card p {
                font-size: 12px;
            }

            .form-card-header h2 {
                font-size: 18px;
            }

            .star-rating {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

<div class="home">
    <div class="homepage-wrapper">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <p class="greeting">Contact Us</p>
                <h4>Get in touch with Barangay 413</h4>
            </div>
        </div>

        <!-- Toast Notification -->
        <?php if ($display_message): ?>
        <div id="toast" class="toast <?php echo $messageType === 'error' ? 'error' : ''; ?> show">
            <i class='bx <?php echo $messageType === 'error' ? 'bx-error-circle' : 'bx-check-circle'; ?>'></i>
            <span><?php echo htmlspecialchars($display_message); ?></span>
        </div>
        <?php endif; ?>

        <!-- Contact Layout -->
        <div class="contact-layout">
            <!-- Info Cards Grid -->
            <div class="contact-info-grid">
                <div class="contact-info-card">
                    <div class="contact-card-icon">
                        <i class='bx bx-map-alt'></i>
                    </div>
                    <h3>Address</h3>
                    <p>Zone 42, District 4<br>Manila</p>
                </div>

                <div class="contact-info-card">
                    <div class="contact-card-icon">
                        <i class='bx bx-phone'></i>
                    </div>
                    <h3>Phone</h3>
                    <p>Call us at <a href="tel:+639667403386">0966-740-3386</a></p>
                </div>

                <div class="contact-info-card">
                    <div class="contact-card-icon">
                        <i class='bx bx-envelope'></i>
                    </div>
                    <h3>Email</h3>
                    <p> <a href="mailto:brgy413.official@gmail.com">brgy413.official@gmail.com</a></p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-container">
                <div class="form-card-header">
                    <i class='bx bx-message-square-edit'></i>
                    <h2>Send Us A Message</h2>
                </div>

                <form action="contact.php" method="post" class="contact-form" id="contactForm">
                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="fullname">
                                <i class='bx bx-user'></i> Full Name *
                            </label>
                            <input 
                                type="text" 
                                id="fullname" 
                                name="fullname" 
                                placeholder="Enter your fullname" 
                                value="<?php echo $formSubmitted ? htmlspecialchars($user['name'] ?? '') : htmlspecialchars($user['name'] ?? $_POST['fullname'] ?? ''); ?>" 
                                required
                            />
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <i class='bx bx-envelope'></i> Email Address *
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="Enter your email" 
                                value="<?php echo $formSubmitted ? htmlspecialchars($user['email'] ?? '') : htmlspecialchars($user['email'] ?? $_POST['email'] ?? ''); ?>" 
                                required
                            />
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">
                            <i class='bx bx-bookmark'></i> Subject *
                        </label>
                        <input 
                            type="text" 
                            id="subject" 
                            name="subject" 
                            placeholder="Enter your subject" 
                            value="<?php echo $formSubmitted ? '' : htmlspecialchars($_POST['subject'] ?? ''); ?>" 
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="message">
                            <i class='bx bx-message-detail'></i> Message *
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            placeholder="Enter Message" 
                            rows="6"
                            required
                        ><?php echo $formSubmitted ? '' : htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>

                    <!-- Rating Section -->
                    <div class="form-group">
                        <div class="rating-container">
                            <div class="rating-label">
                                <i class='bx bx-star'></i> Rate Our Website
                            </div>
                            <div class="stars-wrapper">
                                <div class="star-rating" id="starRating">
                                    <span class="star" data-value="1">★</span>
                                    <span class="star" data-value="2">★</span>
                                    <span class="star" data-value="3">★</span>
                                    <span class="star" data-value="4">★</span>
                                    <span class="star" data-value="5">★</span>
                                </div>
                                <div class="rating-text" id="ratingText">No rating</div>
                            </div>
                            <div class="rating-description">Help us improve by rating your experience</div>
                            <input type="hidden" id="rating" name="rating" value="0">
                        </div>
                    </div>

                    <!-- Suggestions Section -->
                    <div class="form-group">
                        <label for="suggestions">
                            <i class='bx bx-bulb'></i> Website Improvement Suggestions
                        </label>
                        <textarea 
                            id="suggestions" 
                            name="suggestions" 
                            placeholder="Share your ideas on how we can improve our website (optional)" 
                            rows="4"
                        ><?php echo $formSubmitted ? '' : htmlspecialchars($_POST['suggestions'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="reset" class="btn btn-secondary">
                            <i class='bx bx-reset'></i> Reset Form
                        </button>
                        <button type="submit" name="contact" class="btn btn-primary">
                            <i class='bx bx-send'></i> Submit Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include_once 'footer.php'; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star Rating Functionality
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('ratingText');
    let selectedRating = 0;

    const ratingLabels = {
        1: 'Poor',
        2: 'Fair',
        3: 'Good',
        4: 'Very Good',
        5: 'Excellent'
    };

    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    function updateRatingText(rating) {
        if (rating === 0) {
            ratingText.textContent = 'No rating';
        } else {
            ratingText.textContent = `${ratingLabels[rating]} (${rating}/5)`;
        }
    }

    stars.forEach(star => {
        star.addEventListener('click', function() {
            selectedRating = parseInt(this.getAttribute('data-value'));
            ratingInput.value = selectedRating;
            updateStars(selectedRating);
            updateRatingText(selectedRating);
        });

        star.addEventListener('mouseenter', function() {
            const hoverValue = parseInt(this.getAttribute('data-value'));
            stars.forEach((s, index) => {
                if (index < hoverValue) {
                    s.classList.add('hovered');
                } else {
                    s.classList.remove('hovered');
                }
            });
            updateRatingText(hoverValue);
        });

        star.addEventListener('mouseleave', function() {
            stars.forEach(s => s.classList.remove('hovered'));
            updateRatingText(selectedRating);
        });
    });

    // Auto-hide toast
    setTimeout(function() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.remove('show');
        }
    }, 3000);

    // Handle form reset
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        selectedRating = 0;
        ratingInput.value = 0;
        updateStars(0);
        updateRatingText(0);
    });

    // Clear form fields if submission was successful
    <?php if ($formSubmitted): ?>
    const form = document.getElementById('contactForm');
    if (form) {
        document.getElementById('subject').value = '';
        document.getElementById('message').value = '';
        document.getElementById('suggestions').value = '';
        selectedRating = 0;
        ratingInput.value = 0;
        updateStars(0);
        updateRatingText(0);
        
        <?php if (!isset($user['name'])): ?>
        document.getElementById('fullname').value = '';
        document.getElementById('email').value = '';
        <?php endif; ?>
    }
    <?php endif; ?>
});
</script>

</body>
</html>