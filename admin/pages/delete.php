<?php


include "../../backend/connect.php";  


if (
    isset($_POST['action']) && $_POST['action'] === 'delete' &&
    isset($_POST['table']) && !empty($_POST['table']) &&
    isset($_POST['item']) && is_numeric($_POST['item'])
) {
    $table =  $_POST['table'];
    $item = intval($_POST['item']);
    $page = isset($_POST['page']) ? $_POST['page'] : 'reports.php';

    if (deleteRecord($table, $item)) {
        $message = "Record deleted successfully.";
    } else {
        $message = "Failed to delete the record.";
    }

    showToast($message);
    header("refresh:1; url=$page");
    exit;
} else {
    $message = "Invalid delete request.";
    showToast($message);
    header("refresh:1; url=reports.php");
    exit;
}


?>
