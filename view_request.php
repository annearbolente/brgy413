<?php
include("connect.php");

if (isset($_POST['request_id']) && isset($_POST['type'])) {
    $requestId = intval($_POST['request_id']);
    $type = trim($_POST['type']);
    $table = '';

    switch ($type) {
        case 'Barangay Clearance': $table = 'brgyclearance'; break;
        case 'Barangay ID': $table = 'brgyid'; break;
        case 'Barangay Indigency': $table = 'brgyindigency'; break;
        case 'Business Clearance': $table = 'busclearance'; break;
        case 'Complaint': $table = 'complaints'; break;
        default:
            echo "<p class='text-danger text-center'>Invalid request.</p>";
            exit;
    }

    // ✅ Correct join: use user_id not id=id
    $sql = "SELECT t.*, u.email AS user_email 
            FROM $table t
            LEFT JOIN users u ON t.user_id = u.id
            WHERE t.id = $requestId";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);

        echo "<table class='table table-bordered table-striped'>";

        if ($type === 'Barangay Clearance') {
            echo "<tr><th>Full Name</th><td>{$data['fullname']}</td></tr>";
            echo "<tr><th>Address</th><td>{$data['address']}</td></tr>";
            echo "<tr><th>Age</th><td>{$data['age']}</td></tr>";
            echo "<tr><th>Birthday</th><td>{$data['birthday']}</td></tr>";
            echo "<tr><th>Nationality</th><td>{$data['nationality']}</td></tr>";
            echo "<tr><th>Civil Status</th><td>{$data['civilStat']}</td></tr>";
            echo "<tr><th>Contact</th><td>{$data['contact']}</td></tr>";
            echo "<tr><th>Email</th><td>{$data['user_email']}</td></tr>";
            echo "<tr><th>Purpose</th><td>{$data['clearPur']}</td></tr>";
            echo "<tr><th>Gender</th><td>{$data['gender']}</td></tr>";
            echo "<tr><th>Date Requested</th><td>{$data['date_requested']}</td></tr>";
            echo "<tr><th>Status</th><td>{$data['status']}</td></tr>";
        }

        elseif ($type === 'Barangay ID') {
            echo "<tr><th>Barangay ID No.</th><td>{$data['brgyId']}</td></tr>";
            echo "<tr><th>Full Name</th><td>{$data['fname']}</td></tr>";
            echo "<tr><th>Birthday</th><td>{$data['birthday']}</td></tr>";
            echo "<tr><th>Date Requested</th><td>{$data['date_requested']}</td></tr>";
            echo "<tr><th>Status</th><td>{$data['status']}</td></tr>";
        }

        elseif ($type === 'Barangay Indigency') {
            echo "<tr><th>Name</th><td>{$data['name']}</td></tr>";
            echo "<tr><th>Address</th><td>{$data['address']}</td></tr>";
            echo "<tr><th>Purpose</th><td>{$data['purpose']}</td></tr>";
            echo "<tr><th>Date Requested</th><td>{$data['date_requested']}</td></tr>";
            echo "<tr><th>Status</th><td>{$data['status']}</td></tr>";
        }

        elseif ($type === 'Business Clearance') {
            echo "<tr><th>Business Name</th><td>{$data['busName']}</td></tr>";
            echo "<tr><th>Business Address</th><td>{$data['busAdd']}</td></tr>";
            echo "<tr><th>Nature of Business</th><td>{$data['natureOfBus']}</td></tr>";
            echo "<tr><th>Owner</th><td>{$data['opeFullName']}</td></tr>";
            echo "<tr><th>Email</th><td>{$data['user_email']}</td></tr>";
            echo "<tr><th>Date Requested</th><td>{$data['date_requested']}</td></tr>";
            echo "<tr><th>Status</th><td>{$data['status']}</td></tr>";
        }

        elseif ($type === 'Complaint') {
            echo "<tr><th>Name</th><td>{$data['name']}</td></tr>";
            echo "<tr><th>Email</th><td>{$data['user_email']}</td></tr>";
            echo "<tr><th>Contact</th><td>{$data['contact']}</td></tr>";
            echo "<tr><th>Issue</th><td>{$data['issue']}</td></tr>";
            echo "<tr><th>Location</th><td>{$data['location']}</td></tr>";
            echo "<tr><th>Message</th><td>{$data['message']}</td></tr>";
            if (!empty($data['picture'])) {
                echo "<tr><th>Evidence</th><td><img src='{$data['picture']}' alt='Evidence' style='max-width:100%;height:auto;'></td></tr>";
            }
            echo "<tr><th>Date Requested</th><td>{$data['date_requested']}</td></tr>";
            echo "<tr><th>Status</th><td>{$data['status']}</td></tr>";
        }

        echo "</table>";

    } else {
        echo "<p class='text-danger text-center'>No details found for this request.</p>";
    }
}
?>
