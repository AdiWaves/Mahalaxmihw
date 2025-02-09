<!-- Changes to make in fetch_color to make it live
$host = 'localhost';
$user = 'mahalaxm_admin';
$password = 'Aditya@6365';
$dbname = 'mahalaxm_color_palette';
**Make this changes as it is to make fetch_color live**
Also check for index.php or color_cat.php for same changes**
End -->

<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'color_palette';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);
    $sql = "SELECT color_name, color_code, hex_code FROM colors WHERE category_id = $category_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
            <div>
              <div class='color-card' style='background-color: {$row['hex_code']};'>
                <div class='like-icon'>
                  <img src='./favorite.png' alt='Like'>
                </div>
              </div>
              <div class='color-details'>
                <div class='color-name'>{$row['color_name']}</div>
                <div class='color-code'>{$row['color_code']}</div>
              </div>
            </div>
            ";
        }
    } else {
        echo "<p>No colors available for this category.</p>";
    }
} else {
    echo "<p>Invalid category ID.</p>";
}

$conn->close();
?>
