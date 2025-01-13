<?php

include_once('../VisitorController.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['name'];
    $IC = $_POST['IC'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $visit_date = $_POST['visit_date'];
    $valid_days = $_POST['valid_days'];
    $owner_id = $_SESSION['owner_id'];

    $visitor_code = $name . '_' . date('Ymd', strtotime($visit_date));

    $status = 'approved';

    $visitorManagement = new VisitorController();

    if ($visitorManagement->ApplyVisitor($name, $IC, $email, $phone, $visitor_code, $visit_date, $status, $owner_id, $valid_days)) {
        $success = true;
    }else{
        $success = false;
    }

    //$sucess = '';
}


?>

<?php include('header.php')?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .visitor-wrapper {
            background: #f8f9fc;
            min-height: calc(100vh - 60px);
            padding: 20px;
        }

        .form-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            padding: 30px;
            margin-top: 20px;
            transition: transform 0.3s ease;
        }

        .form-container:hover {
            transform: translateY(-5px);
        }

        .page-title {
            color: #4e73df;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e3e6f0;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            color: #4e73df;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e3e6f0;
            padding: 12px 20px;
            padding-left: 45px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 43px;
            color: #6c757d;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section {
            background: #f8f9fc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .form-section-title {
            color: #4e73df;
            margin-bottom: 20px;
            font-size: 1.1rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="visitor-wrapper">
        <div class="container">
            <?php if (!empty($success)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success!</strong> Visitor has been applied successfully!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <h2 class="page-title">
                    <i class="fas fa-user-plus me-2"></i>Apply New Visitor
                </h2>

                <form action="visitors.php" method="POST">
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Visitor Name:</label>
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="IC">Visitor IC:</label>
                                    <i class="fas fa-id-card input-icon"></i>
                                    <input type="text" class="form-control" id="IC" name="IC" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-address-card me-2"></i>Contact Information
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Visitor Email:</label>
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Visitor Phone:</label>
                                    <i class="fas fa-phone input-icon"></i>
                                    <input type="text" class="form-control" id="phone" name="phone" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-calendar-alt me-2"></i>Visit Details
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="visit_date">Visit Date:</label>
                                    <i class="fas fa-calendar input-icon"></i>
                                    <input type="date" class="form-control" id="visit_date" name="visit_date" 
                                           required min="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valid_days">QR Code Validity (in days):</label>
                                    <i class="fas fa-clock input-icon"></i>
                                    <input type="number" class="form-control" id="valid_days" name="valid_days" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="owner_id" value="<?php echo $_SESSION['owner_id']; ?>">

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Apply Visitor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Phone number formatting
    document.getElementById('phone').addEventListener('input', function(e) {
        let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
        e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    // Auto-dismiss alerts
    const alertMessage = document.querySelector('.alert');
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.classList.add('fade');
            setTimeout(() => alertMessage.remove(), 150);
        }, 3000);
    }
    </script>
</body>
</html>
<?php include('../footer.php')?>
