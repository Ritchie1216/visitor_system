<?php

include_once('../VisitorController.php');
include_once('header.php');

if (!isset($_GET['visitor_id'])) {
    header('Location: dashboard.php');
    exit;
}

$visitor_id = $_GET['visitor_id'];
$visitorController = new VisitorController();
$result = $visitorController->viewQRCode($visitor_id);

if (!$result || $result->num_rows === 0) {
    header('Location: dashboard.php');
    exit;
}

$visitor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View QR Code</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        .qr-wrapper {
            background: #f8f9fc;
            min-height: calc(100vh - 60px);
            padding: 20px;
        }

        .qr-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
            margin-top: 20px;
            transition: transform 0.3s ease;
            max-width: 600px;
            margin: 0 auto;
        }

        .qr-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .qr-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e3e6f0;
        }

        .qr-title {
            color: #4e73df;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .visitor-info {
            background: #f8f9fc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .info-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .info-label {
            color: #4e73df;
            font-weight: 500;
            min-width: 120px;
            margin-right: 15px;
        }

        .info-value {
            color: #5a5c69;
        }

        .qr-image-container {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .qr-image {
            max-width: 250px;
            height: auto;
            padding: 10px;
            border: 2px solid #e3e6f0;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .qr-image:hover {
            transform: scale(1.05);
        }

        .validity-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            background: #e8f5e9;
            color: #2e7d32;
            margin-top: 15px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        @media print {
            .no-print {
                display: none;
            }
            .qr-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="qr-wrapper">
        <div class="container">
            <div class="qr-container animate__animated animate__fadeIn">
                <div class="qr-header">
                    <h2 class="qr-title">
                        <i class="fas fa-qrcode me-2"></i>Visitor QR Code
                    </h2>
                    <p class="text-muted mb-0">Scan this QR code for visitor verification</p>
                </div>

                <div class="visitor-info animate__animated animate__fadeInUp">
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-user me-2"></i>Name:
                        </span>
                        <span class="info-value"><?php echo htmlspecialchars($visitor['name']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-calendar me-2"></i>Visit Date:
                        </span>
                        <span class="info-value"><?php echo htmlspecialchars($visitor['visit_date']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">
                            <i class="fas fa-clock me-2"></i>Valid Days:
                        </span>
                        <span class="info-value"><?php echo htmlspecialchars($visitor['valid_days']); ?> days</span>
                    </div>
                </div>

                <div class="qr-image-container animate__animated animate__fadeInUp">
                    <img src="<?php echo htmlspecialchars($visitor['qr_code']); ?>" 
                         alt="QR Code" class="qr-image">
                    <div class="validity-badge">
                        <i class="fas fa-check-circle me-2"></i>Valid for <?php echo htmlspecialchars($visitor['valid_days']); ?> days
                    </div>
                </div>

                <div class="actions no-print">
                    <button class="btn btn-outline-secondary" onclick="window.location.href='dashboard.php'">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </button>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print QR Code
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // 添加动画延迟
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.animate__animated');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${index * 0.2}s`;
        });
        });
    </script>
</body>
</html>

<?php include_once('../footer.php'); ?>
