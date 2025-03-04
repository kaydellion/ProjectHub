<?php                   include "../../backend/connect.php";

                        if (isset($_GET['parent_id'])) {
                          $parent_id = mysqli_real_escape_string($con, $_GET['parent_id']);
                          
                          $query = "SELECT id, category_name AS title FROM " . $siteprefix . "categories WHERE parent_id = '$parent_id'";
                          $result = mysqli_query($con, $query);
                          
                          $subcategories = array();
                          while ($row = mysqli_fetch_assoc($result)) {
                            $subcategories[] = array(
                              's' => $row['id'],
                              'title' => $row['title']
                            );
                          }
                          
                          header('Content-Type: application/json');
                          echo json_encode($subcategories);
                        }
                        ?>