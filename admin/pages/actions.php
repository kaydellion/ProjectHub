<?php


$sql = "SELECT * FROM  ".$siteprefix."alerts WHERE status='0' ORDER BY s DESC LIMIT 5";
$sql2 = mysqli_query($con,$sql);
$notification_count = mysqli_num_rows($sql2);
 
if (isset($_GET['action']) && $_GET['action'] == 'read-message') {
    $sql = "UPDATE dv_alerts SET status='1' WHERE status='0'";
    $sql2 = mysqli_query($con,$sql);
    $message="All notifications marked as read.";
    showToast($message);
    header("refresh:2; url=notifications.php");
}


//upload-report
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addcourse'])) {
    $reportId = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $subcategory = isset($_POST['subcategory']) ? $_POST['subcategory'] : null;
    $pricing = $_POST['pricing'];
    $price = !empty($_POST['price']) ? $_POST['price'] : '0';
    $tags = $_POST['tags'];
    $loyalty = isset($_POST['loyalty']) ? 1 : 0;
    $documentTypes = isset($_POST['documentSelect']) ? $_POST['documentSelect'] : [];
  
    // Upload images
    $uploadDir = '../../uploads/';
    $fileuploadDir = '../../documents/';
    $fileKey='images';
    global $fileName;
    $message="";
    $status="pending";

    $reportImages = handleMultipleFileUpload($fileKey, $uploadDir);
    if (!is_array($reportImages)) { $reportImages = [];}
    $uploadedFiles = [];
    foreach ($reportImages as $image) {
        $stmt = $con->prepare("INSERT INTO  ".$siteprefix."reports_images (report_id, picture, updated_at) VALUES (?, ?, current_timestamp())");
        $stmt->bind_param("ss", $reportId, $image);
        if ($stmt->execute()) {
            $uploadedFiles[] = $image;
        } else {
            $message.="Error: " . $stmt->error;
        }
        $stmt->close();
    }

 
    // Handle file uploads
    $fileFields = [
        'file_word' => 'word',
        'file_excel' => 'excel',
        'file_pdf' => 'pdf',
        'file_powerpoint' => 'powerpoint',
        'file_text' => 'text'
    ];

    foreach ($fileFields as $fileField => $docType) {
        if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] == UPLOAD_ERR_OK) {
            $filePath = handleFileUpload($fileField, $fileuploadDir);
            $pagesField = 'pages_' . $docType;
            $pages = isset($_POST[$pagesField]) ? $_POST[$pagesField] : 0;

            $stmt = $con->prepare("INSERT INTO  ".$siteprefix."reports_files (report_id, title, pages, updated_at) VALUES (?, ?, ?, current_timestamp())");
            $stmt->bind_param("ssi", $reportId, $filePath, $pages);

            if ($stmt->execute()) {
                $message.="File uploaded and record added successfully!";
            } else {
                $message.="Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }


  
    // Insert data into the database
    $sql = "INSERT INTO ".$siteprefix."reports (s, id, title, description, category, subcategory, pricing, price, tags, loyalty, user, created_date, updated_date, status) VALUES (NULL, '$reportId', '$title', '$description', '$category', '$subcategory', '$pricing', '$price', '$tags', '$loyalty', '$user_id', current_timestamp(), current_timestamp(), '$status')";
    if (mysqli_query($con, $sql)) {
        $message .= "Report added successfully!";
    } else {
        $message .= "Error: " . mysqli_error($con);
    }

    showSuccessModal('Processed',$message);
    header("refresh:2; url=reports.php");
  }

//delete-record
  if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $table = $_GET['table'];
    $item = $_GET['item'];
    $page = $_GET['page'];
    
    if (deleteRecord($table, $item)) {
        $message="Record deleted successfully.";
    } else {
         $message="Failed to delete the record.";
    }

    showToast($message);
    header("refresh:1; url=$page");
}
?>










