<?php
include_once '../OwnerController.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $owner = $_POST['Owner'];
    $password = $_POST['password'];

    $ownerController = new OwnerController();
    $result = $ownerController->OwnerLogin($owner, $password);

    if($result){
        header('Location: dashboard.php');
        exit();
    }else{
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 2px solid #eee;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #a1c4fd;
            box-shadow: 0 0 10px rgba(161, 196, 253, 0.5);
        }
        .btn-primary {
            border-radius: 10px;
            padding: 12px 30px;
            background: linear-gradient(45deg, #a1c4fd, #c2e9fb);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .input-group-text {
            background: transparent;
            border: none;
            color: #666;
        }
        .form-label {
            color: #4a5568;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <main class="container animate__animated animate__fadeIn">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger animate__animated animate__shakeX" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form action="index.php" method="post" class="login-card p-4">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-home me-2"></i> Owner Login
                    </h2>
                    
                    <div class="form-group">
                        <label class="form-label" for="Owner">Owner Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input type="email" 
                                   name="Owner" 
                                   id="Owner" 
                                   class="form-control" 
                                   placeholder="Enter your email"
                                   required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="Password">Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <input type="password" 
                                   name="password" 
                                   id="Password" 
                                   class="form-control" 
                                   placeholder="Enter your password"
                                   required>
                            <div class="input-group-append">
                                <span class="input-group-text" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="togglePassword"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('Password');
            const toggleIcon = document.getElementById('togglePassword');
            
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

        // 添加输入动画效果
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // 自动隐藏错误消息
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
<?php include_once('../footer.php');?>
