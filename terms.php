
<?php
session_start();
include("connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - Barangay 413 Central</title>
    <style>
         .home {
            padding: 30px;
            display: flex;
            background: linear-gradient(90deg, #e2e2e2, #c9d6ff);
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .terms-content {
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

        .terms-content h2 {
            color: #667eea;
            font-size: 24px;
            margin-top: 40px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            font-weight: 600;
        }

        .terms-content h3 {
            color: #764ba2;
            font-size: 18px;
            margin-top: 25px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .terms-content p {
            margin-bottom: 15px;
            text-align: justify;
            color: #555;
            font-size: 15px;
            line-height: 1.8;
        }

        .terms-content ul, 
        .terms-content ol {
            margin-left: 30px;
            margin-bottom: 20px;
        }

        .terms-content li {
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

        .terms-content strong {
            color: #333;
            font-weight: 600;
        }

        .back-link {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 30px;
            transition: background 0.3s;
            font-weight: 600;
        }

        .back-link:hover {
            background: #5568d3;
            color: white;
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
        <div class="terms-content">
            <!-- TOP BUTTON: Aligned LEFT and uses history.back() -->
            <div class="top-back-button-wrapper">
                <a href="javascript:history.back()" class="back-link">← Go Back</a> 
            </div>

            <div class="page-header">
                <h1>Terms and Conditions</h1>
                <p>Barangay 413 Central Online Services</p>
            </div>

            <div class="effective-date">
                Last Updated: October 26, 2025
            </div>

            <h2>1. Acceptance of Terms</h2>
            <p>Welcome to Barangay 413 Central Online Services. By accessing or using our platform, you agree to be bound by these Terms and Conditions ("Terms"). If you do not agree with any part of these Terms, you may not access or use our services.</p>

            <div class="highlight-box">
                <p><strong>Important:</strong> Please read these Terms carefully before using our services. Your continued use of the platform constitutes acceptance of these Terms and any amendments.</p>
            </div>

            <h2>2. Definitions</h2>
            <ul>
                <li><strong>"Platform"</strong> refers to the Barangay 413 Central Online Services website and all associated services</li>
                <li><strong>"User"</strong> refers to any individual who accesses or uses the Platform</li>
                <li><strong>"Services"</strong> refers to all online services provided, including document requests and complaint filing</li>
                <li><strong>"We," "Us," "Our"</strong> refers to Barangay 413 Central</li>
                <li><strong>"You," "Your"</strong> refers to the User of the Platform</li>
            </ul>

            <h2>3. Eligibility and Registration</h2>
            
            <h3>3.1 Age Requirement</h3>
            <p>You must be at least 18 years old to use our services. If you are under 18, you may only use the Platform under the supervision of a parent or legal guardian.</p>

            <h3>3.2 Account Registration</h3>
            <p>To access certain services, you must create an account by providing:</p>
            <ul>
                <li>Accurate and complete personal information</li>
                <li>A valid email address</li>
                <li>A secure password</li>
                <li>Proof of residence in Barangay 413 Central (when required)</li>
            </ul>

            <h3>3.3 Account Responsibility</h3>
            <ul>
                <li>You are responsible for maintaining the confidentiality of your account credentials</li>
                <li>You are responsible for all activities that occur under your account</li>
                <li>You must notify us immediately of any unauthorized use of your account</li>
                <li>We reserve the right to suspend or terminate accounts that violate these Terms</li>
            </ul>

            <h2>4. Acceptable Use</h2>

            <h3>4.1 Permitted Use</h3>
            <p>You may use the Platform to:</p>
            <ul>
                <li>Request barangay documents (Clearance, ID, Certificate of Indigency, Business Clearance)</li>
                <li>File complaints or reports</li>
                <li>Check the status of your requests</li>
                <li>Receive notifications from the barangay</li>
            </ul>

            <h3>4.2 Prohibited Activities</h3>
            <p>You agree NOT to:</p>
            <ul>
                <li>Provide false, misleading, or fraudulent information</li>
                <li>Impersonate another person or entity</li>
                <li>Use the Platform for any illegal or unauthorized purpose</li>
                <li>Attempt to gain unauthorized access to the Platform or other users' accounts</li>
                <li>Upload or transmit viruses, malware, or any harmful code</li>
                <li>Harass, abuse, or threaten barangay officials or other users</li>
                <li>Submit duplicate or spam requests</li>
                <li>Use automated systems or bots to access the Platform</li>
                <li>Interfere with or disrupt the Platform's operation</li>
                <li>Scrape, collect, or harvest data from the Platform</li>
            </ul>

            <div class="highlight-box">
                <p><strong>Violation Warning:</strong> Violation of these prohibited activities may result in immediate account suspension or termination, and may be reported to appropriate authorities.</p>
            </div>

            <h2>5. Services and Requests</h2>

            <h3>5.1 Document Requests</h3>
            <ul>
                <li>All document requests are subject to verification and approval by barangay officials</li>
                <li>Processing times may vary depending on the type of document and current workload</li>
                <li>We reserve the right to reject requests that do not meet requirements or contain false information</li>
                <li>Approval or rejection decisions are final and subject to barangay discretion</li>
            </ul>

            <h3>5.2 Complaints</h3>
            <ul>
                <li>Complaints must be factual and made in good faith</li>
                <li>False or malicious complaints may result in legal action</li>
                <li>Complaints are handled according to barangay procedures and applicable laws</li>
                <li>We do not guarantee specific outcomes for complaints</li>
            </ul>

            <h3>5.3 Service Availability</h3>
            <ul>
                <li>We strive to maintain 24/7 platform availability but do not guarantee uninterrupted service</li>
                <li>We may suspend services temporarily for maintenance or updates</li>
                <li>We are not liable for service interruptions caused by factors beyond our control</li>
            </ul>

            <h2>6. Fees and Payments</h2>
            <p>Certain services may require payment of fees as established by barangay ordinances:</p>
            <ul>
                <li>Fees are subject to change with proper notice</li>
                <li>Payment must be made through authorized channels only</li>
                <li>Fees are non-refundable once services are rendered</li>
                <li>Official receipts will be issued for all payments</li>
            </ul>

            <h2>7. Intellectual Property Rights</h2>

            <h3>7.1 Platform Ownership</h3>
            <p>All content, features, and functionality of the Platform, including but not limited to text, graphics, logos, images, and software, are owned by Barangay 413 Central or its licensors and are protected by copyright, trademark, and other intellectual property laws.</p>

            <h3>7.2 License to Use</h3>
            <p>We grant you a limited, non-exclusive, non-transferable license to access and use the Platform for its intended purposes. This license does not include the right to:</p>
            <ul>
                <li>Modify, copy, or reproduce Platform content</li>
                <li>Reverse engineer or decompile any Platform software</li>
                <li>Remove copyright or proprietary notices</li>
                <li>Use Platform content for commercial purposes without permission</li>
            </ul>

            <h2>8. User Content and Submissions</h2>

            <h3>8.1 Your Content</h3>
            <p>When you submit information, documents, or complaints through the Platform, you grant us:</p>
            <ul>
                <li>The right to use, store, and process your submissions for service delivery</li>
                <li>The right to share your information with authorized barangay personnel</li>
                <li>The right to retain your information as required by law</li>
            </ul>

            <h3>8.2 Content Standards</h3>
            <p>All user content must:</p>
            <ul>
                <li>Be accurate and truthful</li>
                <li>Comply with applicable laws and regulations</li>
                <li>Not contain offensive, defamatory, or inappropriate material</li>
                <li>Not violate the rights of others</li>
            </ul>

            <h2>9. Privacy and Data Protection</h2>
            <p>Your use of the Platform is also governed by our Privacy Policy, which is incorporated into these Terms by reference. Please review our Privacy Policy to understand how we collect, use, and protect your personal information.</p>

            <h2>10. Disclaimers and Limitations of Liability</h2>

            <h3>10.1 "As Is" Service</h3>
            <p>The Platform is provided "as is" and "as available" without warranties of any kind, either express or implied, including but not limited to:</p>
            <ul>
                <li>Warranties of merchantability or fitness for a particular purpose</li>
                <li>Warranties that the service will be uninterrupted or error-free</li>
                <li>Warranties regarding the accuracy or reliability of information</li>
            </ul>

            <h3>10.2 Limitation of Liability</h3>
            <p>To the fullest extent permitted by law, Barangay 413 Central shall not be liable for:</p>
            <ul>
                <li>Any indirect, incidental, special, or consequential damages</li>
                <li>Loss of profits, data, or goodwill</li>
                <li>Service interruptions or delays</li>
                <li>Unauthorized access to your account or data</li>
                <li>Errors or omissions in Platform content</li>
            </ul>

            <div class="highlight-box">
                <p><strong>Maximum Liability:</strong> Our total liability to you for any claims arising from your use of the Platform shall not exceed the amount of fees (if any) you paid to use the services in the past 12 months.</p>
            </div>

            <h2>11. Indemnification</h2>
            <p>You agree to indemnify, defend, and hold harmless Barangay 413 Central, its officials, employees, and agents from any claims, damages, losses, liabilities, and expenses (including legal fees) arising from:</p>
            <ul>
                <li>Your use of the Platform</li>
                <li>Your violation of these Terms</li>
                <li>Your violation of any rights of another person or entity</li>
                <li>Your submission of false or misleading information</li>
            </ul>

            <h2>12. Termination</h2>

            <h3>12.1 Termination by You</h3>
            <p>You may terminate your account at any time by contacting us or using the account deletion feature. Upon termination, you will no longer have access to your account and any pending requests.</p>

            <h3>12.2 Termination by Us</h3>
            <p>We reserve the right to suspend or terminate your account immediately, without prior notice, if:</p>
            <ul>
                <li>You violate these Terms</li>
                <li>You engage in fraudulent or illegal activities</li>
                <li>Your account remains inactive for an extended period</li>
                <li>We are required to do so by law</li>
                <li>We decide to discontinue the Platform</li>
            </ul>

            <h3>12.3 Effect of Termination</h3>
            <p>Upon termination:</p>
            <ul>
                <li>Your right to use the Platform will immediately cease</li>
                <li>We may delete your account and data (subject to legal retention requirements)</li>
                <li>Outstanding obligations and liabilities will survive termination</li>
                <li>Sections regarding intellectual property, disclaimers, and limitations of liability will continue to apply</li>
            </ul>

            <h2>13. Modification of Terms</h2>
            <p>We reserve the right to modify these Terms at any time. We will notify you of material changes by:</p>
            <ul>
                <li>Posting the updated Terms on the Platform</li>
                <li>Sending an email notification to your registered email address</li>
                <li>Displaying a prominent notice on the Platform</li>
            </ul>
            <p>Your continued use of the Platform after such modifications constitutes acceptance of the updated Terms.</p>

            <h2>14. Governing Law and Dispute Resolution</h2>

            <h3>14.1 Governing Law</h3>
            <p>These Terms shall be governed by and construed in accordance with the laws of the Republic of the Philippines, without regard to its conflict of law provisions.</p>

            <h3>14.2 Jurisdiction</h3>
            <p>Any disputes arising from these Terms or your use of the Platform shall be subject to the exclusive jurisdiction of the courts of Manila, Philippines.</p>

            <h3>14.3 Dispute Resolution</h3>
            <p>Before filing any legal action, you agree to attempt to resolve disputes through:</p>
            <ul>
                <li>Direct communication with Barangay 413 Central officials</li>
                <li>Mediation or conciliation proceedings, if appropriate</li>
                <li>Barangay-level dispute resolution mechanisms</li>
            </ul>

            <h2>15. Severability</h2>
            <p>If any provision of these Terms is found to be invalid, illegal, or unenforceable, the remaining provisions shall continue in full force and effect. The invalid provision shall be modified to the minimum extent necessary to make it valid and enforceable.</p>

            <h2>16. Waiver</h2>
            <p>Our failure to enforce any right or provision of these Terms shall not constitute a waiver of such right or provision. Any waiver must be in writing and signed by an authorized representative of Barangay 413 Central.</p>

            <h2>17. Entire Agreement</h2>
            <p>These Terms, together with our Privacy Policy, constitute the entire agreement between you and Barangay 413 Central regarding the use of the Platform and supersede all prior agreements and understandings.</p>

            <h2>18. Force Majeure</h2>
            <p>We shall not be liable for any failure or delay in performance due to circumstances beyond our reasonable control, including but not limited to:</p>
            <ul>
                <li>Acts of God, natural disasters, or severe weather</li>
                <li>War, terrorism, or civil unrest</li>
                <li>Government actions or restrictions</li>
                <li>Internet or telecommunications failures</li>
                <li>Power outages or equipment failures</li>
                <li>Pandemics or public health emergencies</li>
            </ul>

            <h2>19. Assignment</h2>
            <p>You may not assign or transfer these Terms or your rights and obligations under these Terms without our prior written consent. We may assign these Terms to any successor or affiliated entity without restriction.</p>

            <h2>20. Notifications</h2>
            <p>We may send you notifications regarding the Platform through:</p>
            <ul>
                <li>Email to your registered email address</li>
                <li>Push notifications through the Platform</li>
                <li>Posted notices on the Platform</li>
                <li>SMS to your registered mobile number</li>
            </ul>
            <p>You are responsible for keeping your contact information up to date.</p>

            <h2>21. Accessibility</h2>
            <p>We strive to make our Platform accessible to all users, including those with disabilities. If you encounter any accessibility issues, please contact us so we can work to improve your experience.</p>

            <h2>22. Translation</h2>
            <p>These Terms may be translated into other languages for convenience. In case of any conflict between the English version and a translated version, the English version shall prevail.</p>

            <div class="contact-section">
                <h3>23. Contact Information</h3>
                <p>For questions, concerns, or support regarding these Terms and Conditions:</p>
                <p><strong>Barangay 413 Central</strong></p>
                <p>Address: Barangay 413, Sampaloc, Manila</p>
                <p>Email: brgy413.official@gmail.com</p>
                <p>Phone: (02) 7001-5070</p>
                <p>Office Hours: Monday to Friday, 8:00 AM - 5:00 PM</p>
            </div>

            <h2>24. Acknowledgment</h2>
            <p>By creating an account and using our Platform, you acknowledge that:</p>
            <ul>
                <li>You have read and understood these Terms and Conditions</li>
                <li>You agree to be bound by these Terms</li>
                <li>You have reviewed our Privacy Policy</li>
                <li>You are legally capable of entering into binding agreements</li>
                <li>All information you provide is accurate and truthful</li>
            </ul>

            <div class="highlight-box">
                <p><strong>User Agreement:</strong> Your use of the Barangay 413 Central Online Services platform constitutes your acceptance of these Terms and Conditions. If you do not agree with these Terms, please do not use our services.</p>
            </div>

            <div class="bottom-back-button-wrapper">
                <a href="javascript:history.back()" class="back-link">← Go Back</a>
            </div>

            <div class="footer-section">
                <p>&copy; 2025 Barangay 413 Central. All rights reserved.</p>
                <p>These Terms and Conditions are effective as of October 26, 2025</p>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();