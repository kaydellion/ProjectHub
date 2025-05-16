<?php 
include "seo.php";
include "header.php"; 

if (isset($_GET['slugs'])) {
    $slug = $_GET['slugs'];

    // Use prepared statement to match slug safely
    $stmt = $con->prepare("SELECT * FROM " . $siteprefix . "categories WHERE REPLACE(LOWER(category_name), ' ', '-') = ?");
    $slugFormatted = strtolower($slug);
    $stmt->bind_param("s", $slugFormatted);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $category_name = $row['category_name'];

        // ✅ Echo the category name
        echo "<h2 class='text-center mb-4'>" . htmlspecialchars($category_name) . "</h2>";

        // You can now use $id and $category_name for further processing
    } else {
        echo "<p>Category not found.</p>";
        exit();
    }
} else {
    header("Location: $siteurl/index.php");
    exit();
}

include "footer.php";
?>
