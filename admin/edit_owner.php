<?php
include_once '../db.php';
include_once '../OwnerController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $unit = $_POST['unit'];
    $phone = $_POST['phone'];

    $ownerController = new OwnerController();
    $result = $ownerController->OwenrModify($name, $email, $password, $unit, $phone, $id);

    if ($result) {
        header('Location: owner.php?msg=Owner updated successfully');
        exit;
    } else {
        $error = "Error updating owner.";
    }
} else {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        die('Invalid owner ID.');
    }

    $ownerController = new OwnerController();
    $owner = $ownerController->GetOwnerById($id);

    if (!$owner) {
        die('Owner not found.');
    }
}
?>

<?php include_once('header.php'); ?>

<div class="edit-wrapper">
    <div class="container mt-5">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="card-title">
                        <i class="fas fa-user-edit me-2"></i>Edit Owner
                    </h2>
                    <span class="badge bg-primary">ID: <?php echo $owner['id']; ?></span>
                </div>

                <form action="edit_owner.php" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?php echo $owner['id']; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="<?php echo htmlspecialchars($owner['name']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($owner['email']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Leave blank to keep the current password.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone">Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($owner['phone']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="unit">Unit</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    <input type="text" class="form-control" id="unit" name="unit" 
                                           value="<?php echo htmlspecialchars($owner['unit']); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="owner.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.edit-wrapper {
    background: #f8f9fc;
    min-height: calc(100vh - 60px);
    padding: 20px;
}

.card {
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.form-group label {
    font-weight: 500;
    color: #4e73df;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background: transparent;
    border-right: none;
    color: #6c757d;
}

.form-control {
    border-left: none;
    border: 1px solid #e3e6f0;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
}

.input-group .form-control:focus {
    border-left: none;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-primary {
    background: #4e73df;
    border-color: #4e73df;
}

.badge {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.9rem;
}

.alert {
    border-radius: 10px;
    border: none;
}
</style>

<script>
// Password visibility toggle
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

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

<?php include_once('../footer.php'); ?>
