<?php
include_once('../db.php');
include_once('header.php');
include_once('../VisitorController.php');

if (!isset($_SESSION['owner_id'])) {
    echo "Owner ID is not set. Please log in.";
    exit;
}

$owner_id = $_SESSION['owner_id'];
$Visitor = new VisitorController();
$result = $Visitor->getVisitors($owner_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .dashboard-wrapper {
            background: #f8f9fc;
            min-height: calc(100vh - 60px);
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .table-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .table-header {
            background: #f8f9fc;
            padding: 15px 20px;
            border-bottom: 2px solid #e3e6f0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            color: #4e73df;
            font-weight: 600;
            padding: 15px;
        }

        .table td {
            vertical-align: middle;
            padding: 15px;
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 0 5px;
            width: 120px;
            text-align: center;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .visitor-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #e3e6f0;
        }

        .search-box {
            position: relative;
            margin-bottom: 20px;
        }

        .search-box input {
            padding: 12px 20px;
            padding-left: 40px;
            border-radius: 10px;
            border: 2px solid #e3e6f0;
            width: 100%;
            transition: all 0.3s ease;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .search-box input:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
        }

        .visitor-initial {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 0 2px 10px rgba(102,126,234,0.2);
            margin-right: 15px;
            transition: all 0.3s ease;
        }

        .visitor-initial:hover {
            transform: scale(1.1);
            box-shadow: 0 5px 15px rgba(102,126,234,0.3);
        }

        /* 添加一些随机颜色变体 */
        .visitor-initial[data-initial="A"],
        .visitor-initial[data-initial="F"],
        .visitor-initial[data-initial="K"],
        .visitor-initial[data-initial="P"],
        .visitor-initial[data-initial="U"] {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 100%);
        }

        .visitor-initial[data-initial="B"],
        .visitor-initial[data-initial="G"],
        .visitor-initial[data-initial="L"],
        .visitor-initial[data-initial="Q"],
        .visitor-initial[data-initial="V"] {
            background: linear-gradient(135deg, #4CAF50 0%, #8BC34A 100%);
        }

        .visitor-initial[data-initial="C"],
        .visitor-initial[data-initial="H"],
        .visitor-initial[data-initial="M"],
        .visitor-initial[data-initial="R"],
        .visitor-initial[data-initial="W"] {
            background: linear-gradient(135deg, #FF9800 0%, #FFC107 100%);
        }

        .visitor-initial[data-initial="D"],
        .visitor-initial[data-initial="I"],
        .visitor-initial[data-initial="N"],
        .visitor-initial[data-initial="S"],
        .visitor-initial[data-initial="X"] {
            background: linear-gradient(135deg, #9C27B0 0%, #E040FB 100%);
        }

        .visitor-initial[data-initial="E"],
        .visitor-initial[data-initial="J"],
        .visitor-initial[data-initial="O"],
        .visitor-initial[data-initial="T"],
        .visitor-initial[data-initial="Y"],
        .visitor-initial[data-initial="Z"] {
            background: linear-gradient(135deg, #00BCD4 0%, #03A9F4 100%);
        }

        .btn-group {
            display: flex;
            gap: 8px;
        }

        .swal2-popup {
            border-radius: 15px !important;
            padding: 2em !important;
        }

        .swal2-title {
            color: #4e73df !important;
            font-size: 1.5em !important;
        }

        .swal2-content {
            font-size: 1.1em !important;
        }

        .swal2-confirm {
            background: linear-gradient(135deg, #d33 0%, #ff4444 100%) !important;
            border-radius: 8px !important;
            padding: 12px 25px !important;
        }

        .swal2-cancel {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
            border-radius: 8px !important;
            padding: 12px 25px !important;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translate3d(0, -30px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        @keyframes fadeOutUp {
            from {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
            to {
                opacity: 0;
                transform: translate3d(0, -30px, 0);
            }
        }

        .btn-action i {
            margin-right: 8px !important;
        }

        .table th:nth-child(3),
        .table th:nth-child(4) {
            width: 140px;
        }

        .alert-popup {
            padding: 2em !important;
            border-radius: 20px !important;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(10px) !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }

        .alert-title {
            font-size: 2.5em !important;
            margin-bottom: 0.3em !important;
        }

        .alert-title i {
            background: linear-gradient(45deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.2em;
        }

        .alert-message {
            color: #495057;
            font-size: 1.1em;
            font-weight: 500;
            margin: 0.5em 0;
        }

        .alert-content {
            padding: 1em 0;
        }

        .alert-button {
            background: linear-gradient(45deg, #4e73df, #224abe) !important;
            border: none !important;
            padding: 12px 30px !important;
            font-weight: 500 !important;
            letter-spacing: 0.5px !important;
        }

        .swal2-timer-progress-bar {
            background: linear-gradient(to right, #28a745, #20c997) !important;
            height: 3px !important;
        }
    </style>
</head>
<body>
<div class="dashboard-wrapper">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header animate__animated animate__fadeIn">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Visitor Management</h2>
                    <p class="mb-0">Manage and monitor your visitors</p>
                </div>
                <button class="btn btn-light" onclick="window.location.href='visitors.php'">
                    <i class="fas fa-plus me-2"></i>Add New Visitor
                </button>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search visitors..." onkeyup="searchTable()">
        </div>

        <!-- Visitors Table -->
        <div class="table-card animate__animated animate__fadeInUp">
            <div class="table-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Your Visitors</h5>
            </div>
        <div class="table-responsive">
                <table class="table" id="visitorsTable">
                    <thead>
                    <tr>
                        <th>Visitor Name</th>
                        <th>Visit Date</th>
                        <th>QR Code</th>
                            <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="animate__animated animate__fadeIn">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="visitor-initial" data-initial="<?php echo strtoupper(substr($row['name'], 0, 1)); ?>">
                                                <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="visitor-badge bg-light">
                                            <i class="far fa-calendar-alt me-2"></i>
                                            <?php echo htmlspecialchars($row['visit_date']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="view_qrcode.php?visitor_id=<?php echo htmlspecialchars($row['id']); ?>" 
                                           class="btn btn-primary btn-action">
                                            <i class="fas fa-qrcode me-2"></i>View QR
                                        </a>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="update_visitor_date.php?visitor_id=<?php echo htmlspecialchars($row['id']); ?>" 
                                               class="btn btn-info btn-action">
                                                <i class="fas fa-edit me-2"></i>Edit
                                            </a>
                                            <button class="btn btn-danger btn-action" 
                                                    onclick="confirmDelete(<?php echo htmlspecialchars($row['id']); ?>)">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <i class="fas fa-users"></i>
                                        <h5>No Visitors Found</h5>
                                        <p>You haven't added any visitors yet.</p>
                                    </div>
                                </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</div>

<script>
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('visitorsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const nameCell = rows[i].getElementsByTagName('td')[0];
        if (nameCell) {
            const nameText = nameCell.textContent || nameCell.innerText;
            if (nameText.toLowerCase().indexOf(filter) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}

// 添加表格行动画
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.1}s`;
    });
});

// 添加删除确认函数
function confirmDelete(visitorId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This visitor will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        customClass: {
            popup: 'animated fadeInDown faster',
            title: 'text-danger'
        },
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // 显示加载状态
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // 执行删除
            window.location.href = `delete_visitor.php?visitor_id=${visitorId}`;
        }
    });
}

// 检查URL参数显示操作结果
window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const error = urlParams.get('error');
    
    if (msg) {
        Swal.fire({
            title: '<i class="fas fa-check-circle text-success"></i>',
            html: `<div class="alert-message">${msg}</div>`,
            icon: 'success',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            customClass: {
                popup: 'animated fadeInDown faster alert-popup',
                title: 'alert-title',
                htmlContainer: 'alert-content'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        });
    } else if (error) {
        Swal.fire({
            title: '<i class="fas fa-exclamation-circle text-danger"></i>',
            html: `<div class="alert-message">${error}</div>`,
            icon: 'error',
            confirmButtonText: 'OK',
            customClass: {
                popup: 'animated fadeInDown faster alert-popup',
                title: 'alert-title',
                htmlContainer: 'alert-content',
                confirmButton: 'alert-button'
            }
        });
    }
});
</script>

<?php include_once('../footer.php'); ?>
</body>
</html>
