<?php
include "backend/connect.php";
$category_titles = [];
$sql = "SELECT * FROM " . $siteprefix . "resource_types WHERE parent_id IS NULL LIMIT 6";
$sql2 = mysqli_query($con, $sql);

while ($row = mysqli_fetch_array($sql2)) {
    $category_titles[] = $row['name'] .  $category_name;
}

// Join titles with a pipe separator
$page_title = implode(' | ', $category_titles);
?>