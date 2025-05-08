<?php
$db_username = "projectr_project"; 
$db_pass = "Y34GgwK(]h82Yg"; 
$db_name = "projectr_project";
$conn = mysqli_connect ("$db_host","$db_username","$db_pass","$db_name");

if (isset($_GET['slugs'])|| isset($_GET['slug'])) {
    if (isset($_GET['slug'])) {
        $raw_slug = $_GET['slug'];
    } else {
        $raw_slug = $_GET['slugs'];
    }
    $title_like = str_replace('-', ' ', $raw_slug);
    $category_name = mysqli_real_escape_string($conn, ucwords($title_like));
}

if(isset($_GET['slugs'])){
$category_titles = [];
$sql = "SELECT * FROM pr_resource_types WHERE parent_id IS NULL";
$sql2 = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($sql2)) {
    $category_titles[] = $row['name'] . ' on '. $category_name;
    $page_title = implode(' | ', $category_titles);
}} else {
$page_title = $category_name;
}

?>