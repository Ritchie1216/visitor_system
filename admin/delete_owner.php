<?php
include_once '../db.php';
include_once '../OwnerController.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $ownerController = new OwnerController();
    $result = $ownerController->OwnerDelete($id);

    if ($result) {
        header('Location: owner.php?msg=Owner deleted successfully');
    } else {
        echo "Error deleting owner.";
    }
} else {
    echo "Invalid request.";
}
?>
