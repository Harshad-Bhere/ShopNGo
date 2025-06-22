<?php
ini_set('display_errors', 0);
error_reporting(0);
session_start();
$_SESSION['page'] = 'Cart';
include 'header.php';
if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "Please login!";
    $_SESSION['redirect_url'] = "cart.php";
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isdelete = isset($_POST['delete']);
    if ($isdelete) {
        $deleteProductId = htmlspecialchars(trim($_POST['delete'] ?? ''));
        unset($_SESSION['cart'][$deleteProductId]);
    } else {
        foreach ($_POST['quantity'] as $product_id => $quantity) {
            if (is_numeric($quantity) && $quantity > 0) {
                $_SESSION['cart'][$product_id] = (float)$quantity;
            } else {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    updateCart($_SESSION['cart'], $isdelete);
    header('Location: cart.php');
    exit;
}
if (!isset($_SESSION['cartId'])) {
    $_SESSION['cartId'] = getCartIdByUserId();
}
populateCart();
$cart = $_SESSION['cart'];
$productIds = [];
foreach ($cart as $key => $item) {
    $productIds[] = $key;
}
$products = getProducts($productIds);
// Initialize total
$total = 0;
?>

<body style="font-family: 'Inter', Arial, sans-serif; background: #5e88b2; min-height: 100vh; margin: 0; padding: 0;">
    <div class="container" style="max-width: 1100px; margin-top: 90px ; padding: 25px; background: linear-gradient(135deg, #0f2a44, #1e4d77); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); font-family: 'Inter', sans-serif;">
        <h1 class="text-center" style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #e0e0e0; margin-bottom: 30px; font-weight: 800; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); letter-spacing: 1px;">Your Shopping Cart</h1>
        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <form method="post">
                <table class="table table-bordered" style="background: linear-gradient(135deg, #2c5364, #4a7390); border: none; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                    <thead class="head-back" style="background: linear-gradient(45deg, #0f2027, #2c5364); color: #e0e0e0; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; text-transform: uppercase;">
                        <tr>
                            <th style="padding: 15px; text-align: center; border: none;">Image</th>
                            <th style="padding: 15px; text-align: center; border: none;">Product</th>
                            <th style="padding: 15px; text-align: center; border: none;">Price</th>
                            <th style="padding: 15px; text-align: center; border: none;">Quantity</th>
                            <th style="padding: 15px; text-align: center; border: none;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $product_id => $quantity): ?>
                            <?php
                            // Ensure product exists in the array
                            if (!isset($products[$product_id])) {
                                continue;
                            }
                            $product = $products[$product_id];
                            $subtotal = $product['price'] * $quantity;
                            $total += $subtotal;
                            ?>
                            <tr style="background: #2c5364;">
                                <td style="padding: 15px; text-align: center; vertical-align: middle; border: none;">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid" style="height: 100px; width: auto; border-radius: 8px; object-fit: cover; filter: saturate(1.2) brightness(1.1); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                </td>
                                <td style="padding: 15px; text-align: center; vertical-align: middle; font-family: 'Poppins', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 600; border: none;"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td style="padding: 15px; text-align: center; vertical-align: middle; font-family: 'Poppins', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 600; border: none;">₹<?php echo number_format($product['price'], 2); ?></td>
                                <td class="quantity" style="padding: 15px; text-align: center; vertical-align: middle; border: none;">
                                    <input type="number" name="quantity[<?php echo htmlspecialchars($product_id); ?>]" value="<?php echo htmlspecialchars($quantity); ?>" min="1" max="20" step="1" class="form-control" style="width: 100px; padding: 8px; border: 2px solid #2c5364; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 0.9rem; background: linear-gradient(135deg, #2c5364, #4a7390); color: #e0e0e0; margin: 0 auto 10px; display: block; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                                    <button type="submit" class="btn btn-outline-danger" name="delete" value="<?php echo htmlspecialchars($product_id); ?>" style="padding: 8px 15px; border: 2px solid #dc3545; border-radius: 6px; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 600; color: #dc3545; background: #2c5364; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.3)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 4px rgba(0, 0, 0, 0.2)';">
                                        Remove
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16" style="margin-left: 5px;">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                        </svg>
                                    </button>
                                </td>
                                <td style="padding: 15px; text-align: center; vertical-align: middle; font-family: 'Poppins', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 600; border: none;">₹<?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-right" style="margin-top: 25px;">
                    <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.8rem; color: #e0e0e0; font-weight: 800; margin-bottom: 15px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Total: ₹<?php echo number_format($total, 2); ?></h3>
                    <button type="submit" class="btn btn-primary btn-lg custom-button1" style="padding: 12px 30px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; border: none; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); transition: all 0.3s ease; animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';">Update Cart</button>
                </div>
            </form>
            <div class="product" style="text-align: right; margin-top: 20px;">
                <a href="checkout.php" class="btn btn-success btn-lg mt-4 custom-button1" style="padding: 12px 30px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; text-decoration: none; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); transition: all 0.3s ease; animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="text-center" style="padding: 30px; background: linear-gradient(135deg, #2c5364, #4a7390); border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); margin-top: 20px;">
                <p style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; color: #e0e0e0; font-weight: 600; margin-bottom: 15px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">Your cart is empty.</p>
                <img src="../images/empty_cart.png" alt="Empty Cart" class="img-fluid heightAuto" style="max-width: 300px; filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));">
            </div>
        <?php endif; ?>
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