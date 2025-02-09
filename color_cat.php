<!-- What changes to make while uploading to live**
$host = 'localhost';
$user = 'mahalaxm_admin';
$password = 'Aditya@6365';
$dbname = 'mahalaxm_color_palette';
** Change all of this as it is to make file live**
end -->

<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'color_palette';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from database
$categories_sql = "SELECT id, category_name, hex_codes FROM categories";
$categories_result = $conn->query($categories_sql);

// Fetch colors from database
$colors_sql = "SELECT color_name, color_code, hex_code, category_id FROM colors";
$colors_result = $conn->query($colors_sql);

// Organize colors by category
$colors_by_category = [];
if ($colors_result->num_rows > 0) {
    while ($row = $colors_result->fetch_assoc()) {
        $colors_by_category[$row['category_id']][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Color Selector</title>
  <style>
    body {
      font-family: 'Axiforma', sans-serif;
      margin: 0;
      padding: 0;
      line-height: 1.6;
    }

    .header {
      position: relative;
      width: 100%;
      overflow: hidden;
      text-align: center;
    }

    .header img {
      width: 100%;
      height: auto;
      display: block;
    }

    .header-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      color: white;
      font-size: 25px; 
      font-weight: normal;
      text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
      max-width: 90%;
    }

    .header-text p {
      font-size: 17px;
      margin-top: 5px;
    }

    .category-section {
      display: flex;
      justify-content: center;
      gap: 20px;
      padding: 20px;
      flex-wrap: wrap;
      margin-top: 20px;
    }

    .category-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      width: 150px;
      cursor: pointer;
    }

    .category-card .color-swatch {
      width: 100%;
      height: 60px;
      border-radius: 5px;
      margin-bottom: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
      .category-section {
        justify-content: center;
        gap: 10px;
      }
      .category-card {
        width: 120px;
      }
    }

    @media (max-width: 480px) {
      .category-section {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        justify-content: center;
      }
      .category-card {
        width: 80px;
      }
      .category-card .color-swatch {
        height: 40px;
      }
    }

    .main-container {
      display: flex;
      flex-wrap: wrap;
      margin: 20px;
    }

    .colors {
      flex: 3;
      padding: 20px;
    }

    .color-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
      margin-top: 10px;
    }

    .color-card {
      position: relative;
      width: 150px;
      height: 150px;
      border-top-right-radius: 25px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .color-card .like-icon {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: white;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
    }

    .color-card .like-icon img {
      width: 16px;
      height: 16px;
    }

    .color-details {
      margin-top: 8px;
      text-align: left;
      padding-left: 5px;
    }

    .color-name {
      font-size: 14px;
      font-weight: bold;
    }

    .color-code {
      font-size: 12px;
      color: #666;
    }

  </style>
</head>
<body>
<header class="header">
  <img src="WebBanner1.webp" alt="Color Palette">
  <div class="header-text">
    <p>COLOR IDEAS FOR YOUR HOME</p>
    <h1>Choose the best colours for your home from 2200 Shades</h1>
  </div>
</header>

<section class="category-section">
    <?php
    if ($categories_result->num_rows > 0) {
        while ($category = $categories_result->fetch_assoc()) {
            $hex_codes = explode(',', $category['hex_codes']); 
            
            echo '<div class="category-card" data-category-id="' . $category['id'] . '">';
            echo '<div class="color-swatch" style="background: linear-gradient(to right, ' . implode(', ', $hex_codes) . ');"></div>';
            echo '<p>' . $category['category_name'] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No categories found</p>';
    }
    ?>
</section>


<main class="main-container">
  <section class="colors">
    <h2>Available Colours</h2>
    <div class="color-grid">
      <?php
      foreach ($colors_by_category as $category_id => $colors) {
          
          foreach ($colors as $color) {
              echo "
              <div>
                <div class='color-card' style='background-color: {$color['hex_code']};'>
                  <div class='like-icon'>
                    <img src='./favorite.png' alt='Like'>
                  </div>
                </div>
                <div class='color-details'>
                  <div class='color-name'>{$color['color_name']}</div>
                  <div class='color-code'>{$color['color_code']}</div>
                </div>
              </div>
              ";
          }
      }
      ?>
    </div>
  </section>
</main>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const categoryCards = document.querySelectorAll(".category-card");
    const colorGrid = document.querySelector(".color-grid");

    categoryCards.forEach(card => {
        card.addEventListener("click", function () {
            const categoryId = this.getAttribute("data-category-id");
            fetchColors(categoryId);
        });
    });

    function fetchColors(categoryId) {
        fetch(`fetch_colors.php?category_id=${categoryId}`)
            .then(response => response.text())
            .then(data => {
                colorGrid.innerHTML = data;
            })
            .catch(error => console.error("Error fetching colors:", error));
    }
});
</script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
