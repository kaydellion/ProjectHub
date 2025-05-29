<?php include "header.php"; ?>
<?php

//delete-record
// Validate inputs
if (
    isset($_GET['action'], $_GET['table'], $_GET['item'], $_GET['page']) &&
    $_GET['action'] === 'delete'
) {
    $table = $_GET['table'];
    $item = intval($_GET['item']); // cast to int to prevent SQL injection
    $page = $_GET['page'];

    if (deleteRecord($table, $item)) {
        $_SESSION['message'] = "Record deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete the record.";
    }

    header("Location: $page");
    exit();
} else {
    // Invalid access
    http_response_code(403);
    echo "403 Forbidden – Invalid delete request.";
    exit();
}

?>
<?php include "footer.php"; ?>
