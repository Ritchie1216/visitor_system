<?php

include_once('../OwnerController.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $unit = $_POST['unit'];

    $ownerController = new OwnerController();
    if ($ownerController->OwnerRegistation($name, $email, $password, $unit, $phone)) {
        $success = true;
    }else{
        $success = false;
    }
}

?>

<?php include_once('header.php'); ?>

<div class="registration-wrapper">
    <div class="container mt-5">
        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Success!</strong> Owner registration was successful.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h2 class="card-title text-center mb-4">
                    <i class="fas fa-user-plus me-2"></i>Owner Registration
                </h2>

                <form action="OwnerRegistration.php" method="POST" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="name" name="name" 
                                       placeholder="Enter name" required>
                                <label for="name"><i class="fas fa-user me-2"></i>Name</label>
                                <div class="invalid-feedback">Please enter a name</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Enter email" required>
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
                                <div class="invalid-feedback">Please enter a valid email</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Enter password" required>
                                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                <div class="invalid-feedback">Please enter a password</div>
                                <span class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="Enter phone" required>
                                <label for="phone"><i class="fas fa-phone me-2"></i>Phone Number</label>
                                <div class="invalid-feedback">Please enter a phone number</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="unit" name="unit" 
                                       placeholder="Enter unit" required>
                                <label for="unit"><i class="fas fa-building me-2"></i>Unit</label>
                                <div class="invalid-feedback">Please enter a unit number</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="history.back()">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Register Owner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.registration-wrapper {
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

.form-floating {
    position: relative;
}

.form-control {
    border: 2px solid #e3e6f0;
    border-radius: 10px;
    padding: 1rem;
    height: 3.5rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
}

.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #6c757d;
    z-index: 10;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #4e73df;
    border-color: #4e73df;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.alert {
    border-radius: 10px;
    border: none;
}

.form-floating > label {
    padding-left: 1rem;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    color: #4e73df;
}
</style>

<script>
// Form validation
(function () {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

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
</script>

<?php include_once('../footer.php'); ?>
