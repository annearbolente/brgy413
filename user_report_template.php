<?php
// user_report_template.php

// Define a default title just in case
$report_title = $report_title ?? 'Users Mail Accounts Report';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
body { 
    font-family: DejaVu Sans, Arial, sans-serif; /* For better character support */
    font-size: 12px; 
    margin: 0;
    padding: 0;
}
h2 { 
    text-align: center; 
    margin-top: 10px; 
    color: #333;
}
table { 
    width: 100%; 
    border-collapse: collapse; 
    margin-top: 20px; 
}
th, td { 
    border: 1px solid #333; 
    padding: 8px; 
    text-align: left; 
}
th { 
    background: #e9ecef; 
    font-weight: bold;
    color: #212529;
}

/* Dompdf specific styles for Header/Footer */
@page {
    margin-top: 60px; /* Space for the header */
    margin-bottom: 50px; /* Space for the footer */
    header: html_page_header;
    footer: html_page_footer;
}

#html_page_header, #html_page_footer {
    position: fixed;
    left: 0;
    right: 0;
    font-size: 10px;
    /* Ensure the container is text-aligned center */
    text-align: center;
}
#html_page_header {
    top: -50px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
    line-height: 1.5;
}
/* Style for individual lines inside the header to ensure block behavior */
.header-line {
    display: block; 
    margin: 0;
    padding: 0;
    line-height: 1.2;
}
#html_page_footer {
    bottom: -40px;
    border-top: 1px solid #ccc;
    padding-top: 5px;
}
.page-number:before {
    content: "Page " counter(page) " of " counter(pages);
    font-style: italic;
}
</style>
</head>
<body>

<div id="html_page_header">
    <span class="header-line" style="font-size: 14px; font-weight: bold;">Barangay 413 Central Official Report</span>
    <span class="header-line"><?= $report_title ?></span>
    <span class="header-line" style="font-size: 9px;">Generated on: <?= date('Y-m-d H:i') ?></span>
</div>

<div id="html_page_footer">
    <span class="page-number"></span> | Confidential Report
</div>

<h2><?= $report_title ?></h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email Account</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['firstName']) ?></td>
            <td><?= htmlspecialchars($user['lastName']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>