<?php
include_once('../VisitorController.php');
include_once('header.php');

if (!isset($_SESSION['owner_id'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['visitor_id'])) {
    $visitor_id = $_GET['visitor_id'];
    $visitorController = new VisitorController();
    
    // 验证访客是否属于当前登录的业主
    $result = $visitorController->viewQRCode($visitor_id);
    if ($result && $result->num_rows > 0) {
        $visitor = $result->fetch_assoc();
        if ($visitor['owner_id'] == $_SESSION['owner_id']) {
            if ($visitorController->DeleteVisitor($visitor_id)) {
                header('Location: dashboard.php?msg=Visitor deleted successfully');
            } else {
                header('Location: dashboard.php?error=Failed to delete visitor');
            }
        } else {
            header('Location: dashboard.php?error=Unauthorized access');
        }
    } else {
        header('Location: dashboard.php?error=Visitor not found');
    }
} else {
    header('Location: dashboard.php');
}
exit;
?> 