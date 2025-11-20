<?php
// generate_user_report.php
require 'vendor/autoload.php';
require 'connect.php'; // Includes the $pdo object

use Dompdf\Dompdf;

// FIX: Remove unused 'mode' logic and assume attachment 0 (View Inline)

if (!isset($pdo)) {
    die("Error: PDO connection not established in connect.php. Cannot generate report.");
}

// Define report details
$report_title = "List of Registered Users and Mail Accounts";

// 2. Fetch user data
$sql = "SELECT id, firstName, lastName, email FROM users ORDER BY lastName, firstName";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        die("No users found in the database.");
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// 3. Load HTML content from template
ob_start();
include 'user_report_template.php'; 
$html = ob_get_clean();

// 4. Setup and render Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');

$dompdf->getOptions()->set('isFontSubsettingEnabled', true); 
$dompdf->getOptions()->set('isRemoteEnabled', false); 

$dompdf->render();

// 5. Output the PDF to the browser for viewing (Inline => 0)
$filename = "barangay_user_report_" . date('Ymd_His') . ".pdf";

// Ensure this is set to 0 to view inline in the browser's PDF viewer.
$dompdf->stream($filename, ["Attachment" => 0]); 
exit;