<?php
include_once('../VisitorController.php');

$visitorManagement = new VisitorController();

// Check if visitor_id is set in GET parameters
if (!isset($_GET['visitor_id'])) {
    header('Location: visitors.php');
    exit();
}

$visitor_id = $_GET['visitor_id'];
$result = $visitorManagement->viewQRCode($visitor_id);

if (!$result || $result->num_rows === 0) {
    header('Location: visitors.php');
    exit();
}

$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $name = $_POST['name'] ?? '';
        $IC = $_POST['IC'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $visit_date = $_POST['visit_date'] ?? '';
        $valid_days = $_POST['valid_days'] ?? '';
        $visitor_id = $_GET['visitor_id'] ?? '';

        if (empty($name) || empty($IC) || empty($email) || empty($phone) || 
            empty($visit_date) || empty($valid_days) || empty($visitor_id)) {
            $error = "All fields are required";
        } else {
            $visitor_code = $name . '_' . date('Ymd', strtotime($visit_date));
            $status = 'approved';

            $result = $visitorManagement->UpdateVisitor(
                $name, 
                $IC, 
                $email, 
                $phone, 
                $visitor_code, 
                $visit_date, 
                $status, 
                $visitor_id, 
                $valid_days
            );

            if ($result) {
                $success = true;
                // Redirect after successful update
                header("Location: dashboard.php?success=1");
                exit();
            } else {
                $error = "Failed to update visitor";
            }
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        $error = "An error occurred while processing your request";
    }
}
?>

<?php include('header.php')?>
<html>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Visitor has been updated successfully!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="container">
        <form action="update_visitor_date.php?visitor_id=<?php echo htmlspecialchars($visitor_id); ?>" method="POST">
            <div class="form-group">
                <label for="name">Visitor Name:</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="IC">Visitor IC:</label>
                <input type="text" class="form-control" id="IC" name="IC" 
                       value="<?php echo htmlspecialchars($row['IC']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Visitor Email:</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Visitor Phone:</label>
                <input type="text" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($row['phone']); ?>" required>
            </div>

            <div class="form-group">
                <label for="visit_date">Visit Date:</label>
                <input type="date" class="form-control" id="visit_date" name="visit_date" 
                       value="<?php echo htmlspecialchars($row['visit_date']); ?>"
                       min="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label for="valid_days">QR Code Validity (in days):</label>
                <input type="number" class="form-control" id="valid_days" name="valid_days" 
                       value="<?php echo htmlspecialchars($row['valid_days']); ?>" required>
            </div>

            <input type="hidden" name="visitor_id" value="<?php echo htmlspecialchars($visitor_id); ?>">

            <button type="submit" class="btn btn-primary">Update Visitor</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</html>

<?php include('../footer.php')?>