<?php 
ini_set('display_errors', 0);
error_reporting(0);
session_start();
$_SESSION['page'] = 'Product';
include 'header.php'; 
?>

<body style="font-family: 'Inter', Arial, sans-serif; background: #5e88b2; min-height: 100vh; margin: 0; padding: 0;">
<div style="max-width: 1250px; margin: 90px auto; padding: 40px; background: linear-gradient(90deg, #0f2a44, #1e4d77); border-radius: 15px; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);">
    <h1 style="text-align: center; font-family: 'Playfair Display', serif; font-size: 3.5rem; color: #FFD700; margin-bottom: 40px; text-shadow: 0 3px 6px rgba(0, 0, 0, 0.6); letter-spacing: 2px; text-transform: uppercase;">Our Collection</h1>

    <?php
    // Function to get all products or filter by category
    function filterProducts($category = null) {
        $products = getAllProducts();

        if ($category) {
            return array_filter($products, function ($product) use ($category) {
                return $product['category'] === $category;
            });
        }
        return $products;
    }

    // Check if a category filter is set
    $selected_category = isset($_GET['category']) ? $_GET['category'] : null;
    $products = filterProducts($selected_category);

    function display_products($products) {
        echo '<div style="display: flex; justify-content: space-between; gap: 25px; margin-top: 30px; flex-wrap: wrap;">';
        foreach ($products as $product) {
            $pricePerLb = number_format($product['price'], 2);

            echo '
            <div style="width: 280px; background: linear-gradient(135deg, #F5F5DC, #d4d4a3); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); overflow: hidden; transition: transform 0.4s ease, box-shadow 0.4s ease;" onmouseover="this.style.transform=\'scale(1.05)\'; this.style.boxShadow=\'0 8px 16px rgba(0, 0, 0, 0.5)\';" onmouseout="this.style.transform=\'scale(1)\'; this.style.boxShadow=\'0 6px 12px rgba(0, 0, 0, 0.4)\';">
                <img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '" style="width: 100%; height: 200px; object-fit: cover; border-radius: 12px 12px 0 0; filter: saturate(1.2) brightness(1.1); transition: transform 0.4s ease;" onmouseover="this.style.transform=\'scale(1.02)\';" onmouseout="this.style.transform=\'scale(1)\';">
                <div style="padding: 20px; text-align: center; background: #ffffff; border-top: 3px solid #FFD700;">
                    <h5 style="font-family: \'Poppins\', sans-serif; font-size: 1.5rem; color: #0f2a44; margin-bottom: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">' . htmlspecialchars($product['name']) . '</h5>
                    <p style="font-family: \'Inter\', sans-serif; font-size: 1rem; color: #2d3748; margin-bottom: 15px; line-height: 1.6; height: 60px; overflow: hidden;">' . htmlspecialchars($product['description']) . '</p>
                    <p style="font-family: \'Poppins\', sans-serif; font-size: 1.3rem; color: #228B22; font-weight: 900; margin-bottom: 20px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);">₹' . $pricePerLb . ' / Kg</p>
                    <a href="productDetails.php?id=' . htmlspecialchars($product['product_id']) . '" style="display: inline-block; padding: 12px 28px; background: linear-gradient(45deg, #228B22, #FFD700); color: #0f2a44; font-family: \'Poppins\', sans-serif; font-size: 1rem; font-weight: 800; text-transform: uppercase; border-radius: 8px; text-decoration: none; transition: all 0.4s ease; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform=\'scale(1.05)\'; this.style.boxShadow=\'0 6px 12px rgba(0, 0, 0, 0.4)\';" onmouseout="this.style.transform=\'scale(1)\'; this.style.boxShadow=\'0 4px 8px rgba(0, 0, 0, 0.3)\';">View Details</a>
                </div>
            </div>
            ';
        }
        echo '</div>';
    }
    ?>

    <!-- Filter Form -->
    <form method="GET" style="margin-bottom: 40px; display: flex; justify-content: flex-end; align-items: center; gap: 15px; background: rgba(255, 255, 255, 0.15); padding: 15px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; flex-direction: column; align-items: flex-start;">
            <label for="category" style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; color: #FFFFFF; font-weight: 800; margin-bottom: 10px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); letter-spacing: 1px;">Select Category</label>
            <select name="category" id="category" onchange="this.form.submit()" style="width: 200px; padding: 12px; border: 3px solid #FFD700; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; background: #ffffff; color: #0f2a44; font-weight: 600; transition: border-color 0.4s ease, box-shadow 0.4s ease;" onfocus="this.style.borderColor='#228B22'; this.style.boxShadow='0 0 8px rgba(34, 139, 34, 0.5)';" onblur="this.style.borderColor='#FFD700'; this.style.boxShadow='none';">
                <option value="" style="font-family: 'Inter', sans-serif;">All Categories</option>
                <option value="Vegetables" <?php if ($selected_category == 'Vegetables') echo 'selected'; ?> style="font-family: 'Inter', sans-serif;">Vegetables</option>
                <option value="Fruits" <?php if ($selected_category == 'Fruits') echo 'selected'; ?> style="font-family: 'Inter', sans-serif;">Fruits</option>
                <option value="Dairy" <?php if ($selected_category == 'Dairy') echo 'selected'; ?> style="font-family: 'Inter', sans-serif;">Dairy</option>
            </select>
        </div>
    </form>

    <?php display_products($products); ?>
</div>

<style>
@keyframes glow {
    from {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    to {
        box-shadow: 0 6px 12px rgba(255, 215, 0, 0.4);
    }
}
</style>

<?php include 'footer.php'; ?>
</body>