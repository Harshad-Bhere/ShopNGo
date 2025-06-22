<?php 
ini_set('display_errors', 0);
error_reporting(0);
session_start();
$_SESSION['page'] = 'Product';
include 'header.php';

$product_id = htmlspecialchars(trim($_GET['id'] ?? 0));
$product = getProductById($product_id);

if (!$product) {
    echo '<h2 class="text-center" style="font-family: \'Playfair Display\', serif; font-size: 1.8rem; color: #e0e0e0; font-weight: 700; margin: 40px 0; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); letter-spacing: 1px;">Product not found</h2>';
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['email'])) {
        $_SESSION['message'] = "Please login!";
        $_SESSION['redirect_url'] = 'productDetails.php?id=' . $product_id;
        header('Location: login.php');
        exit;
    }

    $quantity = 1;

    try {
        $quantity = intval($_POST['quantity']);
    }
    catch(Exception $e) {

    }

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;

        if($_SESSION['cart'][$product_id] > 20) {
            $_SESSION['cart'][$product_id] = 20;
        }
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    updateCart($_SESSION['cart'], false);

    header('Location: cart.php');
    exit;
}
?>

<body style="font-family: 'Inter', Arial, sans-serif; background: #5e88b2; min-height: 100vh; margin: 0; padding: 0;">
    <div class="container product" style="max-width: 1100px; margin: 90px auto; padding: 25px; background: linear-gradient(135deg, #0f2a44, #1e4d77); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
        <div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -15px;">
            <div class="col-md-7" style="width: 58.33%; padding: 15px;">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid heightAuto" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 450px; object-fit: cover; border-radius: 10px; filter: saturate(1.2) brightness(1.1); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.02)';" onmouseout="this.style.transform='scale(1)';">
            </div>
            <div class="col-md-4" style="width: 33.33%; padding: 15px;">
                <h1 style="font-family: 'Playfair Display', serif; font-size: 2rem; color: #e0e0e0; font-weight: 800; margin-bottom: 15px; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); letter-spacing: 1px;"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p style="font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 500; margin-bottom: 20px; line-height: 1.5; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"><?php echo htmlspecialchars($product['description']); ?></p>
                <p style="font-family: 'Poppins', sans-serif; font-size: 1.3rem; color: #e0e0e0; font-weight: 700; margin-bottom: 20px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);"><strong>₹<?php echo number_format($product['price'], 2); ?> per lb</strong></p>
                <form action="" method="post">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                    <div class="form-group quantitySpinner" style="margin-bottom: 20px;">
                        <label for="quantity" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Quantity (in lbs):</label>
                        <input type="number" name="quantity" id="quantity" min="1" max="20" step="1" value="1" class="form-control mb-3" style="width: 100px; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg custom-button1" style="display: inline-block; padding: 12px 30px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; border: none; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';">Add to Cart</button>
                </form>
            </div>
        </div>
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