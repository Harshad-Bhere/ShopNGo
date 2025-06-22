<?php
ini_set('display_errors', 0);
error_reporting(0);
session_start();
include 'header.php';

if (!isset($_SESSION['userId'])) {
    $_SESSION['message'] = "Please login to add products!";
    $_SESSION['redirect_url'] = 'addProduct.php';
    header('Location: login.php');
    exit;
}

$_SESSION['page'] = 'AddProduct';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));
    $price = floatval($_POST['price'] ?? 0);
    $stock_quantity = intval($_POST['stock_quantity'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $image_url = htmlspecialchars(trim($_POST['image_url'] ?? ''));

    $errors = [];

    if (empty($name)) {
        $errors[] = 'Product name is required.';
    }
    if (empty($description)) {
        $errors[] = 'Description is required.';
    }
    if ($price <= 0) {
        $errors[] = 'Price must be greater than 0.';
    }
    if ($stock_quantity < 0) {
        $errors[] = 'Stock quantity cannot be negative.';
    }
    if ($category_id <= 0) {
        $errors[] = 'Please select a valid category.';
    }
    if (empty($image_url)) {
        $errors[] = 'Image URL is required.';
    }

    if (empty($errors)) {
        $result = addProduct($name, $description, $price, $stock_quantity, $category_id, $image_url);
        if ($result === true) {
            $_SESSION['message'] = 'Product added successfully!';
            header('Location: product.php');
            exit;
        } else {
            $_SESSION['message'] = $result ?: 'Failed to add product.';
        }
    } else {
        $_SESSION['errors'] = $errors;
    }
}

$categories = getCategories();
?>

<body style="font-family: 'Inter', Arial, sans-serif; background: #5e88b2; min-height: 100vh; margin: 0; padding: 0;">
    <div class="container" style="max-width: 1100px; margin: 90px auto; padding: 25px; background: linear-gradient(135deg, #0f2a44, #1e4d77); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
        <h1 class="text-center" style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #e0e0e0; font-weight: 800; margin-bottom: 30px; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); letter-spacing: 1px;">Add New Product</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert <?php echo strpos($_SESSION['message'], 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?> text-center" style="background: linear-gradient(135deg, #2c5364, #4a7390); color: <?php echo strpos($_SESSION['message'], 'successfully') !== false ? '#e0e0e0' : '#ff6b6b'; ?>; border: <?php echo strpos($_SESSION['message'], 'successfully') !== false ? 'none' : '2px solid #ff6b6b'; ?>; border-radius: 10px; padding: 15px; margin-bottom: 20px; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 600; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors'])): ?>
            <div class="alert alert-danger text-center" style="background: linear-gradient(135deg, #2c5364, #4a7390); color: #ff6b6b; border: 2px solid #ff6b6b; border-radius: 10px; padding: 15px; margin-bottom: 20px; font-family: 'Poppins', sans-serif; font-size: 1rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li style="font-weight: 500; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <form method="post">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="name" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Product Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="description" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Description</label>
                <textarea name="description" id="description" class="form-control" rows="5" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="price" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Price (₹)</label>
                <input type="number" name="price" id="price" class="form-control" value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>" min="0.01" step="0.01" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; map: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="stock_quantity" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Stock Quantity</label>
                <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" value="<?php echo isset($_POST['stock_quantity']) ? htmlspecialchars($_POST['stock_quantity']) : ''; ?>" min="0" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="category_id" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Category</label>
                <select name="category_id" id="category_id" class="form-control" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['category_id']); ?>" <?php echo isset($_POST['category_id']) && $_POST['category_id'] == $category['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="image_url" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Image URL</label>
                <input type="text" name="image_url" id="image_url" class="form-control" value="<?php echo isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : ''; ?>" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
            </div>
            <button type="submit" class="btn btn-lg" style="padding: 12px 30px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; border: none; transition: all 0.3s ease; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';">Add Product</button>
        </form>
    </div>

    <style>
    @keyframes glow {
        from {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }
        to {
            box-shadow: 0 8px 15px rgba(44, 83, 100, 0.4);
        }
    }
    </style>
</body>

<?php include 'footer.php'; ?>