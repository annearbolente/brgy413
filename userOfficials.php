<?php
session_start();
include "connect.php";
include_once "sidebar.php";

// Fetch all officials
$officials = [];
$query = "SELECT id, name, position, description, email, contact, picture FROM brgyofficials ORDER BY id ASC";
if ($result = $conn->query($query)) {
    while ($row = $result->fetch_assoc()) {
        $officials[] = $row;
    }
    $result->free();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Officials</title>
    <link rel="icon" href="website_icon/webicon.png" type="image/png" sizes="64x64">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="plugins/userHomepage.css">
    <style>
        /* Additional styles for officials page */
        .officials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 24px;
            margin-top: 30px;
        }

        .official-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .official-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .official-card-header {
            padding: 25px 20px 15px;
            border-bottom: 1px solid #f0f0f0;
            text-align: center;
        }

        .official-avatar {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #f2c516;
            margin: 0 auto 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .official-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 160px;
            height: 160px;
            background: linear-gradient(135deg, #dfe9ff, #f7f7ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            font-weight: 700;
            color: #182052;
            border-radius: 50%;
            border: 4px solid #eef2fb;
            margin: 0 auto 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .official-card-body {
            padding: 20px;
            text-align: center;
        }

        .official-position {
            display: inline-block;
            background-color: #e7f3ff;
            color: #0066cc;
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .official-name {
            font-size: 22px;
            font-weight: 700;
            color: #182052;
            margin: 10px 0;
        }

        .official-description {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin: 15px 0 20px;
        }

        .official-contact {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            text-align: left;
        }

        .contact-item i {
            color: #182052;
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .contact-item span {
            word-break: break-word;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .empty-state i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            color: #182052;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 16px;
            color: #666;
        }

        @media screen and (max-width: 1024px) {
            .officials-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
            }
            
            .official-avatar,
            .avatar-placeholder {
                width: 140px;
                height: 140px;
                font-size: 56px;
            }
        }

        @media screen and (max-width: 768px) {
            .officials-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .official-avatar,
            .avatar-placeholder {
                width: 120px;
                height: 120px;
                font-size: 48px;
            }

            .official-name {
                font-size: 20px;
            }

            .official-description {
                font-size: 13px;
            }

            .contact-item {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<div class="home">
    <div class="homepage-wrapper">
        <h1 class="greeting">Barangay Officials</h1>
        <p class="subtext">Meet our dedicated barangay officials serving the community</p>

        <div class="officials-grid">
            <?php if (empty($officials)): ?>
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <h3>No Officials Listed</h3>
                    <p>Check back later for official information.</p>
                </div>
            <?php else: ?>
                <?php foreach ($officials as $official): ?>
                    <div class="official-card">
                        <div class="official-card-header">
                            <?php if (!empty($official['picture']) && file_exists($official['picture'])): ?>
                                <div class="official-avatar">
                                    <img src="<?php echo htmlspecialchars($official['picture']); ?>" alt="<?php echo htmlspecialchars($official['name']); ?>">
                                </div>
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <?php echo strtoupper(substr($official['name'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="official-card-body">
                            <span class="official-position"><?php echo htmlspecialchars($official['position']); ?></span>
                            <h3 class="official-name"><?php echo htmlspecialchars($official['name']); ?></h3>
                            <p class="official-description"><?php echo htmlspecialchars($official['description']); ?></p>
                            <div class="official-contact">
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo htmlspecialchars($official['email']); ?></span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo htmlspecialchars($official['contact']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
<!-- Include Footer -->
  <?php include_once 'footer.php'; ?>
</div>

</body>
</html>