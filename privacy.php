<?php
session_start();
include("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - Barangay 413 Central</title>
    <style>
        .home {
            padding: 30px;
            display: flex;
            background: linear-gradient(90deg, #e2e2e2, #c9d6ff);
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .privacy-content {
            max-width: 1100px;
            margin: 0 auto;
        }
        /* TOP button wrapper: Aligns content (the link) to the LEFT */
        .top-back-button-wrapper {
            margin-bottom: 30px;
            text-align: left; /* Aligns the button to the left edge of the 1100px content box */
        }

        /* BOTTOM button wrapper: Aligns content (the link) to the CENTER */
        .bottom-back-button-wrapper {
            text-align: center; /* Centers the button */
            margin-top: 30px;
        }

        /* Back Link Styling */
        .back-link {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 10px 25px;
            border-radius: 20px;
            text-decoration: none;
            transition: background 0.3s, transform 0.2s;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.4);
        }

        .back-link:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .page-header h1 {
            color: #667eea;
            font-size: 36px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .page-header p {
            color: #666;
            font-size: 16px;
        }

        .effective-date {
            background: #e8eaf6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
            color: #5568d3;
        }

        .privacy-content h2 {
            color: #667eea;
            font-size: 24px;
            margin-top: 40px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            font-weight: 600;
        }

        .privacy-content h3 {
            color: #764ba2;
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .privacy-content p {
            margin-bottom: 15px;
            text-align: justify;
            color: #555;
            font-size: 15px;
            line-height: 1.8;
        }

        .privacy-content ul, 
        .privacy-content ol {
            margin-left: 30px;
            margin-bottom: 20px;
        }

        .privacy-content li {
            margin-bottom: 12px;
            color: #555;
            line-height: 1.6;
        }

        .highlight-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }

        .highlight-box p {
            margin-bottom: 0;
        }

        .contact-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-top: 40px;
        }

        .contact-section h3 {
            color: white !important;
            margin-bottom: 20px;
            border-bottom: 2px solid rgba(255,255,255,0.3) !important;
            padding-bottom: 10px;
        }

        .contact-section p {
            color: white;
            margin-bottom: 8px;
            text-align: left;
        }

        .privacy-content strong {
            color: #333;
            font-weight: 600;
        }

        .bottom-back-button {
            text-align: center;
            margin-top: 30px;
        }

        .footer-section {
            text-align: center;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #e0e0e0;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="home">
        <div class="privacy-content">
             <!-- TOP BUTTON: Aligned LEFT and uses history.back() -->
            <div class="top-back-button-wrapper">
                <a href="javascript:history.back()" class="back-link">← Go Back</a> 
            </div>

            <div class="page-header">
                <h1>Privacy Policy</h1>
                <p>Barangay 413 Central Online Services</p>
            </div>

            <div class="effective-date">
                Effective Date: October 26, 2025
            </div>

            <h2>1. Introduction</h2>
            <p>Welcome to the Barangay 413 Central Online Services platform. We are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our online services for barangay document requests and complaints.</p>

            <div class="highlight-box">
                <p><strong>Data Privacy Act Compliance:</strong> This Privacy Policy is in accordance with Republic Act No. 10173, also known as the Data Privacy Act of 2012 (DPA), and its Implementing Rules and Regulations (IRR).</p>
            </div>

            <h2>2. Information We Collect</h2>
            
            <h3>2.1 Personal Information</h3>
            <p>When you register or request services through our platform, we may collect the following personal information:</p>
            <ul>
                <li><strong>Basic Information:</strong> First name, last name, email address, contact number</li>
                <li><strong>Identification Information:</strong> Address, date of birth, civil status</li>
                <li><strong>Request Details:</strong> Purpose of request, type of document needed, complaint details</li>
                <li><strong>Account Information:</strong> Username, password (encrypted), account status</li>
            </ul>

            <h3>2.2 Automatically Collected Information</h3>
            <ul>
                <li>Login timestamps and activity logs</li>
                <li>Device information and IP address</li>
                <li>Browser type and operating system</li>
                <li>Pages visited and time spent on the platform</li>
            </ul>

            <h2>3. How We Use Your Information</h2>
            <p>We collect and use your personal information for the following purposes:</p>
            <ol>
                <li><strong>Service Delivery:</strong> To process your requests for Barangay Clearance, Barangay ID, Certificate of Indigency, Business Clearance, and complaints</li>
                <li><strong>Account Management:</strong> To create and manage your user account</li>
                <li><strong>Communication:</strong> To send you notifications about your request status and important updates</li>
                <li><strong>Record Keeping:</strong> To maintain barangay records as required by law</li>
                <li><strong>Service Improvement:</strong> To analyze usage patterns and improve our online services</li>
                <li><strong>Legal Compliance:</strong> To comply with legal obligations and government requirements</li>
            </ol>

            <h2>4. Legal Basis for Processing</h2>
            <p>We process your personal data based on the following legal grounds:</p>
            <ul>
                <li><strong>Consent:</strong> You provide explicit consent when registering and using our services</li>
                <li><strong>Legal Obligation:</strong> Processing is necessary to comply with Local Government Code requirements</li>
                <li><strong>Public Interest:</strong> Processing is necessary for the performance of barangay functions and public service delivery</li>
                <li><strong>Legitimate Interest:</strong> To improve our services and ensure platform security</li>
            </ul>

            <h2>5. Information Sharing and Disclosure</h2>
            
            <h3>5.1 We DO NOT sell your personal information to third parties.</h3>
            
            <h3>5.2 We may share your information with:</h3>
            <ul>
                <li><strong>Barangay Officials:</strong> Authorized barangay staff who need access to process your requests</li>
                <li><strong>Government Agencies:</strong> When required by law or for verification purposes</li>
                <li><strong>Legal Authorities:</strong> When required by court order or legal process</li>
            </ul>

            <h3>5.3 Data Transfer</h3>
            <p>Your personal data is stored within the Philippines and is not transferred internationally without your explicit consent, except when required by law.</p>

            <h2>6. Data Security</h2>
            <p>We implement appropriate technical and organizational security measures to protect your personal information:</p>
            <ul>
                <li>Password encryption using industry-standard algorithms</li>
                <li>Secure database storage with access controls</li>
                <li>Regular security audits and updates</li>
                <li>Limited access to personal data based on role authorization</li>
                <li>SSL/TLS encryption for data transmission</li>
                <li>Regular backups to prevent data loss</li>
            </ul>

            <div class="highlight-box">
                <p><strong>Important:</strong> While we strive to protect your personal information, no method of transmission over the internet is 100% secure. Please use a strong password and keep your login credentials confidential.</p>
            </div>

            <h2>7. Data Retention</h2>
            <p>We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. Specifically:</p>
            <ul>
                <li><strong>Active Accounts:</strong> Data retained while account is active</li>
                <li><strong>Completed Requests:</strong> Retained for 5 years from completion date as per Local Government records retention requirements</li>
                <li><strong>Inactive Accounts:</strong> May be deleted after 2 years of inactivity with prior notice</li>
            </ul>

            <h2>8. Your Rights as a Data Subject</h2>
            <p>Under the Data Privacy Act, you have the following rights:</p>
            <ol>
                <li><strong>Right to be Informed:</strong> You have the right to know how your data is being processed</li>
                <li><strong>Right to Access:</strong> You can request a copy of your personal data we hold</li>
                <li><strong>Right to Correction:</strong> You can request correction of inaccurate or incomplete data</li>
                <li><strong>Right to Erasure:</strong> You can request deletion of your data under certain circumstances</li>
                <li><strong>Right to Object:</strong> You can object to certain types of processing</li>
                <li><strong>Right to Data Portability:</strong> You can request your data in a structured, machine-readable format</li>
                <li><strong>Right to File a Complaint:</strong> You can file a complaint with the National Privacy Commission</li>
            </ol>

            <p>To exercise any of these rights, please contact our Data Protection Officer using the contact information provided below.</p>

            <h2>9. Cookies and Tracking Technologies</h2>
            <p>Our platform uses cookies and similar technologies to:</p>
            <ul>
                <li>Maintain your login session</li>
                <li>Remember your preferences</li>
                <li>Analyze platform usage and performance</li>
                <li>Enhance user experience</li>
            </ul>
            <p>You can control cookies through your browser settings, but disabling cookies may affect platform functionality.</p>

            <h2>10. Children's Privacy</h2>
            <p>Our services are not intended for individuals under 18 years of age. We do not knowingly collect personal information from minors. If we become aware that we have collected personal information from a minor without parental consent, we will take steps to delete that information.</p>

            <h2>11. Changes to This Privacy Policy</h2>
            <p>We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. We will notify you of any material changes by:</p>
            <ul>
                <li>Posting the updated policy on our platform</li>
                <li>Sending a notification to your registered email address</li>
                <li>Displaying a prominent notice on the platform</li>
            </ul>
            <p>Your continued use of our services after such changes constitutes acceptance of the updated Privacy Policy.</p>

            <h2>12. Third-Party Links</h2>
            <p>Our platform may contain links to third-party websites. We are not responsible for the privacy practices of these external sites. We encourage you to read the privacy policies of any third-party sites you visit.</p>

            <h2>13. Data Breach Notification</h2>
            <p>In the event of a data breach that may affect your personal information, we will:</p>
            <ul>
                <li>Notify affected users within 72 hours of becoming aware of the breach</li>
                <li>Report the breach to the National Privacy Commission as required by law</li>
                <li>Take immediate steps to secure our systems and prevent further breaches</li>
                <li>Provide guidance on steps you can take to protect yourself</li>
            </ul>

            <div class="contact-section">
                <h3>14. Contact Information</h3>
                <p><strong>Data Protection Officer</strong></p>
                <p>Barangay 413 Central, Sampaloc, Manila</p>
                <p>Email: brgy413.official@gmail.com</p>
                <p>Phone: (02) 7001-5070</p>
                <p>Office Hours: Monday to Friday, 8:00 AM - 5:00 PM</p>
                
                <p style="margin-top: 20px;"><strong>National Privacy Commission</strong></p>
                <p>For complaints or inquiries regarding data privacy:</p>
                <p>Email: info@privacy.gov.ph</p>
                <p>Website: www.privacy.gov.ph</p>
                <p>Hotline: (02) 8234-2228</p>
            </div>

            <h2>15. Consent</h2>
            <p>By using our platform and services, you acknowledge that you have read, understood, and agree to be bound by this Privacy Policy. You consent to the collection, use, and disclosure of your personal information as described herein.</p>

            <div class="highlight-box">
                <p><strong>Your Privacy Matters:</strong> We are committed to protecting your personal information and respecting your privacy rights. If you have any questions, concerns, or requests regarding this Privacy Policy or our data practices, please don't hesitate to contact us.</p>
            </div>

            <div class="bottom-back-button-wrapper">
                <a href="javascript:history.back()" class="back-link">← Go Back</a>
            </div>

            <div class="footer-section">
                <p>&copy; 2025 Barangay 413 Central. All rights reserved.</p>
                <p>This Privacy Policy is compliant with the Data Privacy Act of 2012 (R.A. 10173)</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>