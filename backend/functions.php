<?php

function getCartCount($con, $siteprefix, $order_id) {
    $sql = "SELECT COUNT(*) as count FROM ".$siteprefix."order_items oi 
    LEFT JOIN ".$siteprefix."orders o ON oi.order_id = o.order_id 
    WHERE o.order_id='$order_id'";
    $sql2 = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($sql2);
    return $row['count'];
}

function checkActiveLog($active_log) {
    if ($active_log == "0") {
        header("Location: {$siteurl}signin.php");
        exit(); // Make sure to exit after the redirect
    }
}
function displayMessage($message) {
    echo "<div class='alert alert-warning'>$message</div>";
}

function formatNumber($number) {
    return number_format($number);
}

function convertHtmlEntities($input) {
    return html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
function removeAllWhitespace($string) {
    return preg_replace('/\s+/', '', $string);
}

function getFileExtension($filename) {
    // Get the extension and convert to uppercase
    return strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
}

function updateDisputeStatus($con, $siteprefix, $dispute_id, $status) {
    $status = mysqli_real_escape_string($con, $status);
    $dispute_id = mysqli_real_escape_string($con, $dispute_id);
    
    $sql = "UPDATE " . $siteprefix . "disputes 
            SET status = '$status', 
                created_at = NOW() 
            WHERE ticket_number = '$dispute_id'";
            
    $result = mysqli_query($con, $sql);
    
    if ($result) {
        return true;
    }
    return false;
}
function calculateRating($report_id, $con, $siteprefix) {
    $review_query = "SELECT SUM(rating) as total_rating, COUNT(*) as review_count FROM " . $siteprefix . "reviews WHERE report_id = '$report_id'";
    $review_result = mysqli_query($con, $review_query);
    $review_data = mysqli_fetch_assoc($review_result);

    $total_rating = $review_data['total_rating'];
    $review_count = $review_data['review_count'];

    if ($review_count > 0) {
        $average_rating = $total_rating / $review_count;
        $average_rating = min(max($average_rating, 1.0), 5.0); // Ensure rating is between 1.0 and 5.0
    } else {
        $average_rating = 0;
    }

    return array('average_rating' => $average_rating, 'review_count' => $review_count);
}

function ifLoggedin($active_log){
    if($active_log=="1"){ header("location: dashboard.php"); 
    }}

function generateRandomHardPassword($length = 10) {
return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_+=<>?'), 0, $length);
}

function hashPassword($password) {
    // Use password_hash() function to securely hash passwords
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    return $hashedPassword;
}

function getFirstWord($phrase) {
    // Split the phrase into words
    $words = explode(' ', trim($phrase));
    return isset($words[0]) ? $words[0] : '';
}

function getShortName($name) {
    // Split the name into words
    $words = explode(' ', trim($name));
    $shortName = '';
    foreach ($words as $word) {
        $shortName .= strtoupper($word[0]);
    }
    return $shortName;
}

function insertAlert($con, $user_id, $message, $date, $status) {
     $escapedMessage = mysqli_real_escape_string($con, $message);
 
     $query = "INSERT INTO pr_notifications (user, message, date, status) VALUES ('$user_id', '$escapedMessage', '$date', '$status')";
     $submit = mysqli_query($con, $query);
     if ($submit) { echo "";} 
     else { die('Could not connect: ' . mysqli_error($con)); }}

  
     function addCourseProgress($con, $user_id, $course_id, $section, $coursename) {
        // Check for duplicates
        $stmt = $con->prepare("SELECT 1 FROM pr_course_progress WHERE user_id = ? AND course_id = ? AND section = ?");
        $stmt->bind_param("iii", $user_id, $course_id, $section);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            showToast("Progress already recorded.");
            return false;
        }
    
        // Update the last progress entry's end_date
        $lastProgressQuery = "SELECT s FROM pr_course_progress WHERE user_id = ? AND course_id = ? AND end_date IS NULL ORDER BY s DESC LIMIT 1";
        $lastProgressStmt = $con->prepare($lastProgressQuery);
        $lastProgressStmt->bind_param("ii", $user_id, $course_id);
        $lastProgressStmt->execute();
        $lastProgressResult = $lastProgressStmt->get_result();
    
        if ($lastProgressResult->num_rows > 0) {
            $lastProgressRow = $lastProgressResult->fetch_assoc();
            $lastProgressId = $lastProgressRow['s'];
    
            // Update the end_date of the last progress entry
            $updateStmt = $con->prepare("UPDATE pr_course_progress SET end_date = NOW() WHERE s = ?");
            $updateStmt->bind_param("i", $lastProgressId);
            $updateStmt->execute();
        }
    
        // Insert new progress
        $stmt = $con->prepare("INSERT INTO pr_course_progress (s, user_id, section, course_id, start_date, end_date) VALUES (NULL, ?, ?, ?, NOW(), NULL)");
        $stmt->bind_param("iii", $user_id, $section, $course_id);
        if ($stmt->execute()) {
            showToast("Progress added successfully!");
            return true;
        }
        showToast("Failed to add progress.");
        return false;
    }
    
    

function isFavorite($userid, $course_id, $con, $siteprefix) {
    $query = "SELECT * FROM " . $siteprefix . "favorites WHERE user_id = '$userid' AND course_id = '$course_id'";
    $result = mysqli_query($con, $query);
    return mysqli_num_rows($result) > 0;
}

function insertWallet($con, $user_id, $amount, $type, $note, $date) {

    $query = "INSERT INTO pr_wallet_history (user, amount, reason, status, date) VALUES ('$user_id', '$amount', '$note', '$type' , '$date')";
    $submit = mysqli_query($con, $query);
    if ($submit) { echo "";} 
    else { die('Could not connect: ' . mysqli_error($con)); }}

function getRandomMotivationalQuote() {
    $motivational_quotes = [
        "Success is not final, failure is not fatal: it is the courage to continue that counts.",
        "The only way to do great work is to love what you do.",
        "Believe you can and you're halfway there.",
        "Every expert was once a beginner.",
        "The future depends on what you do today.",
        "Don't watch the clock; do what it does. Keep going.",
        "The secret of getting ahead is getting started.",
        "Learning is a journey, not a destination.",
        "Your only limit is your mind.",
        "Small progress is still progress."
    ];
    return htmlspecialchars($motivational_quotes[array_rand($motivational_quotes)]);
}

function sendEmail($vendorEmail, $vendorName, $siteName, $siteMail, $emailMessage, $emailSubject) {
    global $siteimg;
    global $adminlink;
    global $siteurl;
    

   $email_from = $siteMail;
   $email_to = $vendorEmail;
   $email_subject = "$emailSubject - $siteName";
   $email_message = "<div style='width:600px; padding:100px 60px; background-color:#000000; color:#fff;'>
   <p><img src='$siteurl/img/$siteimg' style='width:10%; height:auto;' /></p>
   <p style='font-size:14px; color:#fff;'> <span style='font-size:14px; color:#F57C00;'>Hello there, $vendorName,</span>
   $emailMessage</p>

<p>
Best regards,<br>
The Project Report Hub Team<br>
$siteMail | <a href='$siteurl' style='font-size:14px; font-weight:600; color:#F57C00;'>🌐 www.projectreporthub.ng</a></p>
</div>";

   // create email headers
   $header = 'From: ' . preg_replace('/[^\x20-\x7E]/', '', $siteName) . ' <' . filter_var($siteMail, FILTER_SANITIZE_EMAIL) . '>' . "\r\n";
   $header .= "Cc:$siteMail \r\n";
   $header .= 'Reply-To: ' . $siteMail . '' . "\r\n";
   $header .= "MIME-Version: 1.0\r\n";
   $header .= "Content-type: text/html\r\n";

   if (!@mail($email_to, $email_subject, $email_message, $header)) {
       echo '<center><font color="red">Mail cannot be submitted now due to server problems. Please try again.</font></center>';
   }
}

function sendEmail2($vendorEmail, $vendorName, $siteName, $siteMail, $emailMessage, $emailSubject, $attachments = []) {
    global $siteimg, $adminlink, $siteurl;

    $email_from = $siteMail;
    $email_to = $vendorEmail;
    $email_subject = "$emailSubject - $siteName";

    // Generate a unique boundary
    $boundary = md5(time());

    // Email headers
    $header = "From: \"$siteName\" <$siteMail>\r\n";
    $header .= "Cc: $siteMail\r\n";
    $header .= "Reply-To: $siteMail\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    // HTML email message
    $email_body = "--$boundary\r\n";
    $email_body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $email_body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $email_body .= "<div style='width:600px; padding:40px; background-color:#000080; color:#fff;'>
                        <p><img src='$siteurl/img/$siteimg' style='width:10%; height:auto;' /></p>
                        <p style='font-size:14px; color:#fff;'>
                            <span style='font-size:14px; color:#F57C00;'>Hello, $vendorName,</span><br>
                            $emailMessage
                        </p>

<p>
Best regards,<br>
The Project Report Hub Team<br>
$siteMail | <a href='$siteurl' style='font-size:14px; font-weight:600; color:#F57C00;'>🌐 www.projectreporthub.ng</a></p>
</div>\r\n";

    // Attach files
    foreach ($attachments as $file) {
        if (file_exists($file)) {
            $filename = basename($file);
            $filedata = chunk_split(base64_encode(file_get_contents($file)));

            $email_body .= "--$boundary\r\n";
            $email_body .= "Content-Type: application/octet-stream; name=\"$filename\"\r\n";
            $email_body .= "Content-Transfer-Encoding: base64\r\n";
            $email_body .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
            $email_body .= "$filedata\r\n";
        }
    }

    // Final boundary
    $email_body .= "--$boundary--";

    // Send email
    if (@mail($email_to, $email_subject, $email_body, $header)) {
        return true; // Success
    } else {
        return false; // Failed
    }
}


function showToast($message) {
    echo '<div id="toast-wrapper" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11;"></div>';
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var wrapper = document.getElementById("toast-wrapper");

            // Create a new toast container
            var toast = document.createElement("div");
            toast.className = "toast align-items-center text-white bg-primary border-0 mb-2";
            toast.setAttribute("role", "alert");
            toast.setAttribute("aria-live", "assertive");
            toast.setAttribute("aria-atomic", "true");

            // Create toast content
            var toastContent = `
                <div class="d-flex">
                    <div class="toast-body">' . addslashes($message) . '</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>`;
            
            toast.innerHTML = toastContent;
            wrapper.appendChild(toast);

            // Initialize and show the toast
            var bootstrapToast = new bootstrap.Toast(toast, { delay: 5000 });
            bootstrapToast.show();
        });
    </script>';
}

function notifyDisputeRecipient($con, $siteprefix, $dispute_id) {
    // Get recipient ID
    $query = "SELECT recipient_id FROM ".$siteprefix."disputes WHERE ticket_number = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $dispute_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $recipient_id = $row['recipient_id'];
    
    if (!$recipient_id) {
        return false;
    }

    $message = "There has been a new update on dispute ($dispute_id). Please check the ticket for more details.";
    $rDetails = getUserDetails($con, $siteprefix, $recipient_id);
    $r_email = $rDetails['email'];
    $r_name = $rDetails['display_name'];
    $r_emailSubject = "Dispute Update ($dispute_id)";
    $r_emailMessage = "<p>There has been a new update on dispute ($dispute_id). Login to your dashboard to check</p>";
    
    //sendEmail($r_email, $r_name, $siteName, $siteMail, $r_emailMessage, $r_emailSubject);
    
    $date = date('Y-m-d H:i:s');
    $status = 0;
    $link = "ticket.php?ticket_number=$dispute_id";
    $msgtype = "Dispute Update";
    
    return insertAlert($con, $recipient_id, $message, $date, $status);
}

//function to get username and email
function getUserDetails($con, $siteprefix, $user_id) {
    $query = "SELECT * FROM " . $siteprefix . "users WHERE s = '$user_id'";
    $result = mysqli_query($con, $query);
    return mysqli_fetch_assoc($result);
}

function insertadminAlert($con, $message, $link, $date, $msgtype, $status) {
    $escapedMessage = mysqli_real_escape_string($con, $message);

     $query = "INSERT INTO pr_alerts(message,link, date,type, status) VALUES ('$escapedMessage','$link',  '$date', '$msgtype', '$status')";
     $submit = mysqli_query($con, $query);
     if ($submit) { echo "";} 
     else { die('Could not connect: ' . mysqli_error($con)); }}


function checkPassword($password, $hashedPassword) {
    // Use password_verify() function to check if the password matches the hashed password
    return password_verify($password, $hashedPassword);
}

function limitDescription($description, $wordLimit = 15) {
    // Strip HTML tags from the description
    $description = strip_tags($description);
    
    // Explode the description into words
    $words = explode(' ', $description);
    
    // Extract the limited number of words
    $limitedDescription = implode(' ', array_slice($words, 0, $wordLimit));
    
    return $limitedDescription;
}  

function limitDescriptionshort($description, $wordLimit = 4) {
    // Strip HTML tags from the description
    $description = strip_tags($description);
    
    // Explode the description into words
    $words = explode(' ', $description);
    
    // Extract the limited number of words
    $limitedDescription = implode(' ', array_slice($words, 0, $wordLimit));
    
    return $limitedDescription;
}  

function getUserColor($status) {
    switch ($status) { 
        case 'affiliate':
            return 'info'; // Info for pending payment
        case 'inprogress':
        case 'user':
            return 'success'; // Warning for inprogress or pending review
        default:
            return 'success'; // Success for all other statuses
    }
}


function getBadgeColor($status) {
    switch ($status) {
        case 'cancelled':
            return 'danger'; // Gray for pending contract
        case 'Inactive':
                return 'danger'; // Gray for pending contract
        case 'draft':
            return 'info'; // Info for pending payment
        case 'awaiting-response':
            return 'info'; // Info for pending payment
        case 'pending':
                return 'warning'; // Info for pending payment
        case 'inprogress':
        case 'approved':
            return 'success';
        case 'resolved':
            return 'success';
        case 'under-review':
            return 'danger'; // Gray for pending contract
        default:
            return 'success'; // Success for all other statuses
    }
}

function formatDateTime2($dateTime) {
    if (empty($dateTime)) { return '';  }
    $timestamp = strtotime($dateTime);
    // Check if the input contains both date and time
    $hasTime = strpos($dateTime, 'T') !== false;
    if ($hasTime) { return date('M j, Y \a\t h:i A', $timestamp); } else {
     return date('M j, Y', $timestamp);
}}


function formatDateTime($dateTimeString) {
    // Create a DateTime object from the input string
    $dateTime = new DateTime($dateTimeString);

    // Format the date and time
    $formattedDate = $dateTime->format('M d Y');
    $formattedTime = $dateTime->format('H:i:s');

    // Combine the formatted date and time
    $formattedDateTime = $formattedDate . ' ' . $formattedTime;

    return $formattedDateTime;
}


function handleMultipleFileUpload($fileKey, $uploadDir) {
    $uploadedFiles = [];

    if (isset($_FILES[$fileKey])) {
        $fileCount = count($_FILES[$fileKey]['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES[$fileKey]['error'][$i] === UPLOAD_ERR_OK) {
                $fileExtension = pathinfo($_FILES[$fileKey]['name'][$i], PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $fileExtension;
                $uploadedFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES[$fileKey]['tmp_name'][$i], $uploadedFile)) {
                    $uploadedFiles[] = $fileName; // Add the new file name to the array
                } else {
                    $uploadedFiles[] = "Failed to move the uploaded file.";
                }
            } else {
                $uploadedFiles[] = "No file uploaded or an error occurred.";
            }
        }
    }

    return $uploadedFiles; // Return the array of uploaded file names or error messages
}

function handleFileUpload($fileKey, $uploadDir, $fileName = null) {
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
        $fileExtension = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
        if ($fileName === null) {
            $fileName = uniqid() . '.' . $fileExtension;
        } else {
            $fileName .= '.' . $fileExtension;
        }

        $uploadedFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $uploadedFile)) {
            return $fileName; // Return the new file name
        } else {
            return "Failed to move the uploaded file.";
        }
    } else {
        return "No file uploaded or an error occurred.";
    }
}


function deleteRecord($table, $item) {
    global $con;
    global $siteprefix;

    $sql = "DELETE FROM " . $siteprefix . $table . " WHERE s = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $item);

     return $stmt->execute();
}

function formatDuration($total_duration) {
    $hours = floor($total_duration / 60);
    $minutes = $total_duration % 60;
    return sprintf("%02d:%02d", $hours, $minutes);
}

function refreshPage($params = [], $delay = 2000) {
    $url = $_SERVER['PHP_SELF'];
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    echo "<script>
    setTimeout(function() {
        window.location.href = '" . $url . "';
    }, $delay);
    </script>";
}



function showSuccessModal($statusAction,$statusMessage) {
    echo '<div class="modal fade" id="statusSuccessModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">';
    echo '<div class="modal-dialog modal-dialog-centered modal-sm" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-body text-center p-lg-4">';
    echo '<svg version="1.1" class="lazyload" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">';
    echo '<circle class="path circle" fill="none" stroke="#198754" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />';
    echo '<polyline class="path check" fill="none" stroke="#198754" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />';
    echo '</svg>';
    echo '<h4 class="text-success mt-3">' . $statusAction. '</h4>';
    echo '<p class="mt-3">' . $statusMessage. '</p>';
    echo '<button type="button" class="btn btn-sm mt-3 btn-success" data-dismiss="modal">Okay</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo 'var myModal = new bootstrap.Modal(document.getElementById("statusSuccessModal"));';
    echo 'myModal.show();';
    echo '});';
    echo '</script>';
}

function showErrorModal($statusAction, $statusMessage) {
    echo '<div class="modal fade" id="statusErrorsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">';
    echo '<div class="modal-dialog modal-dialog-centered modal-sm" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-body text-center p-lg-4">';
     echo '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">';
    echo '<circle class="path circle" fill="none" stroke="#db3646" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />';
    echo '<polyline class="path check" fill="none" stroke="#db3646" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />';
    echo '</svg>';
    echo '<h4 class="text-danger mt-3">' . $statusAction. '</h4>';
    echo '<p class="mt-3">' . $statusMessage. '</p>';
    echo '<button type="button" class="btn btn-sm mt-3 btn-danger" data-dismiss="modal">Okay</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo 'var myModal = new bootstrap.Modal(document.getElementById("statusErrorsModal"));';
    echo 'myModal.show();';
    echo '});';
    echo '</script>';
}


function insertWithdraw($con, $user_id, $amount,$bank, $bankname, $bankno, $date, $status) {
$query = "INSERT INTO pr_withdrawal (user,amount,bank,bank_name,bank_number, date, status) VALUES ('$user_id', '$amount', '$bank','$bankname','$bankno','$date', '$status')";
$insert = mysqli_query($con, "UPDATE pr_users SET wallet = CAST(wallet AS DECIMAL(10,2)) - CAST('$amount' AS DECIMAL(10,2)) WHERE s='$user_id'") or die('Could not connect: ' . mysqli_error($con));
    $submit = mysqli_query($con, $query);
    if ($submit) { echo "";} 
    else { die('Could not connect: ' . mysqli_error($con)); }}


    function decreaseDownloads($con, $user_id) {
        // Decrease downloads by 1
        $update = mysqli_query($con, "UPDATE pr_users SET downloads = downloads - 1 WHERE s = '$user_id'") 
        or die('Could not connect: ' . mysqli_error($con));
    }


    function increaseDownloads($con, $user_id) {
        // Increase downloads by 1
        $update = mysqli_query($con, "UPDATE pr_users SET downloads = downloads + 1 WHERE s = '$user_id'") 
        or die('Could not connect: ' . mysqli_error($con));
    }
    
    


function showSuccessModal2($statusAction,$statusMessage) {
    echo '<div class="modal fade" id="statusSuccessModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">';
    echo '<div class="modal-dialog modal-dialog-centered modal-sm" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-body text-center p-lg-4">';
    echo '<svg version="1.1" class="lazyload" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">';
    echo '<circle class="path circle" fill="none" stroke="#198754" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />';
    echo '<polyline class="path check" fill="none" stroke="#198754" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />';
    echo '</svg>';
    echo '<h4 class="text-success mt-3">' . $statusAction. '</h4>';
    echo '<p class="mt-3">' . $statusMessage. '</p>';
    echo '<button type="button" class="btn btn-sm mt-3 btn-success" data-dismiss="modal">Okay</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo 'var myModal = new bootstrap.Modal(document.getElementById("statusSuccessModal"));';
    echo 'myModal.show();';
    echo '});';
    echo '</script>';
}

function showErrorModal2($statusAction, $statusMessage) {
    echo '<div class="modal fade" id="statusErrorsModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">';
    echo '<div class="modal-dialog modal-dialog-centered modal-sm" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-body text-center p-lg-4">';
     echo '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">';
    echo '<circle class="path circle" fill="none" stroke="#db3646" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" />';
    echo '<polyline class="path check" fill="none" stroke="#db3646" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 " />';
    echo '</svg>';
    echo '<h4 class="text-danger mt-3">' . $statusAction. '</h4>';
    echo '<p class="mt-3">' . $statusMessage. '</p>';
    echo '<button type="button" class="btn btn-sm mt-3 btn-danger" data-dismiss="modal">Okay</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo 'var myModal = new bootstrap.Modal(document.getElementById("statusErrorsModal"));';
    echo 'myModal.show();';
    echo '});';
    echo '</script>';
}

function getUserRole() {
    // Replace with your actual role-checking logic
    return $_SESSION['user_role'] ?? 'guest';
}

function getReadonlyAttribute() {
    $role = getUserRole();
    if ($role === 'admin') {
        return ''; // No restriction
    } elseif ($role === 'sub-admin') {
        return 'readonly';
    }
    return '';
}

function getDisplayClass() {
    $role = getUserRole();
    return ($role === 'admin') ? 'd-none' : '';
}

function redirectToDashboardIfSubAdmin() {
    // Assuming roles are strings like 'admin', 'subadmin', 'editor', etc.
    $userRole=getUserRole();
    if ($userRole === 'sub-admin') {
        header("Location: /dashboard.php");
        exit();
    }
}

function debug($data) {
    echo '<div class="alert alert-warning" role="alert" style="margin: 20px;">';
    echo '<pre style="margin: 0;">' . print_r($data, true) . '</pre>';
    echo '</div>';
}

?>