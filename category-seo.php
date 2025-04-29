<?php
include "backend/connect.php";
if (isset($_GET['slugs'])) {
    $raw_slug = $_GET['slugs'];
    $title_like = str_replace('-', ' ', $raw_slug);
    $category_name = mysqli_real_escape_string($con, strtolower($title_like));
}
$category_titles = [];
$sql = "SELECT * FROM pr_resource_types WHERE parent_id IS NULL";
$sql2 = mysqli_query($con, $sql);

while ($row = mysqli_fetch_array($sql2)) {
    $category_titles[] = $row['name'] .  $category_name;
}

// Join titles with a pipe separator
$page_title = implode(' | ', $category_titles);
?>