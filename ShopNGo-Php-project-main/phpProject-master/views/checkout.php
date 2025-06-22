<?php
ini_set('display_errors', 0);
error_reporting(0);

session_start();
$_SESSION['page'] = 'Checkout';
include 'header.php';

if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "Please login!";
    $_SESSION['redirect_url'] = "checkout.php";
    header('Location: login.php');
    exit;
}

populateCart();

$cart = $_SESSION['cart'];
$productIds = array_keys($cart);
$products = getProducts($productIds);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $street = htmlspecialchars(trim($_POST['street'] ?? ''));
    $city = htmlspecialchars(trim($_POST['city'] ?? ''));
    $state = htmlspecialchars(trim($_POST['state'] ?? ''));
    $postal_code = htmlspecialchars(trim($_POST['postal_code'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $payment_method = htmlspecialchars(trim($_POST['payment_method'] ?? ''));

    // Validation for required fields
    if (empty($street)) $errors[] = 'Street address is required.';
    if (empty($city)) $errors[] = 'City is required.';
    if (empty($state)) $errors[] = 'State is required.';
    
    // Postal code validation for India (format: 110001 or similar)
    if (!preg_match('/^\d{6}$/', $postal_code)) {
        $errors[] = 'Postal code must be a 6-digit number.';
    }

    // Phone number validation for India (format: 9xxxxxxxxx or 8xxxxxxxxx)
    if (!preg_match('/^[7-9]\d{9}$/', $phone)) {
        $errors[] = 'Phone number must be in the format 9xxxxxxxxx or 8xxxxxxxxx (10 digits).';
    }

    if (empty($payment_method)) $errors[] = 'Payment method is required.';

    if (empty($errors)) {
        $shipping_address = [
            'street' => $street,
            'city' => $city,
            'state' => $state,
            'postal_code' => $postal_code,
            'country' => 'India',
            'phone' => $phone
        ];

        placeOrder($shipping_address);
        unset($_SESSION['cart'], $_SESSION['cartId']);
        $_SESSION['message'] = "Order successfully placed!";
        header('Location: orders.php');
        exit;
    }
}

$address = getAddressByUserId();
$phone = getPhoneByUserId();
$total = 0;
?>

<body style="font-family: 'Inter', Arial, sans-serif; background: #5e88b2; min-height: 100vh; margin: 0; padding: 0;">
<div class="container" style="max-width: 1100px; margin: 90px auto; padding: 25px; background: linear-gradient(135deg, #0f2a44, #1e4d77); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success text-center" style="background: linear-gradient(135deg, #2c5364, #4a7390); color: #e0e0e0; border: none; border-radius: 10px; padding: 15px; margin-bottom: 20px; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 600; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <h1 class="text-center" style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #e0e0e0; margin-bottom: 30px; font-weight: 800; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); letter-spacing: 1px;">Checkout</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #2c5364, #4a7390); color: #ff6b6b; border: 2px solid #ff6b6b; border-radius: 10px; padding: 15px; margin-bottom: 20px; font-family: 'Poppins', sans-serif; font-size: 1rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $error): ?>
                    <li style="font-weight: 500; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['cart'])): ?>
        <form method="post">
            <div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -15px;">
                <div class="col-md-6" style="width: 50%; padding: 15px;">
                    <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.8rem; color: #e0e0e0; font-weight: 700; margin-bottom: 20px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Shipping Information</h3>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="street" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Street</label>
                        <input type="text" id="street" name="street" class="form-control" value="<?= htmlspecialchars($address['street']) ?>" required maxlength="50" style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                    </div>
                    <div class="form-row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
                        <div class="form-group col-md-6" style="width: 50%; padding: 10px; margin-bottom: 20px;">
                            <label for="city" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">City</label>
                            <input type="text" id="city" name="city" class="form-control" value="<?= htmlspecialchars($address['city']) ?>" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                        </div>
                        <div class="form-group col-md-6" style="width: 50%; padding: 10px; margin-bottom: 20px;">
                            <label for="state" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight:600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">State</label>
                            <select id="state" name="state" class="form-control" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                                <option value="">Select a state</option>
                                <?php
                                $states = [
                                    "AP" => "Andhra Pradesh", "AR" => "Arunachal Pradesh", "AS" => "Assam", 
                                    "BR" => "Bihar", "CT" => "Chhattisgarh", "GA" => "Goa", "GJ" => "Gujarat", 
                                    "HR" => "Haryana", "HP" => "Himachal Pradesh", "JH" => "Jharkhand", "KA" => "Karnataka",
                                    "KL" => "Kerala", "MP" => "Madhya Pradesh", "MH" => "Maharashtra", "MN" => "Manipur",
                                    "ML" => "Meghalaya", "MZ" => "Mizoram", "NL" => "Nagaland", "OR" => "Odisha", 
                                    "PB" => "Punjab", "RJ" => "Rajasthan", "SK" => "Sikkim", "TN" => "Tamil Nadu",
                                    "TS" => "Telangana", "UP" => "Uttar Pradesh", "UK" => "Uttarakhand", "WB" => "West Bengal"
                                ];
                                foreach ($states as $abbr => $full) {
                                    echo '<option value="' . $abbr . '"' . ($address['state'] == $abbr ? ' selected' : '') . '>' . $full . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
                        <div class="form-group col-md-6" style="width: 50%; padding: 10px; margin-bottom: 20px;">
                            <label for="postal_code" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" class="form-control" placeholder="110001" pattern="\d{6}" value="<?= htmlspecialchars($address['postal_code']) ?>" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                        </div>
                        <div class="form-group col-md-6" style="width: 50%; padding: 10px; margin-bottom: 20px;">
                            <label for="country" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Country</label>
                            <input type="text" id="country" name="country" class="form-control" value="India" readonly style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="phone" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter 10-digit phone number" pattern="[7-9][0-9]{9}" value="<?= htmlspecialchars($phone) ?>" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="payment_method" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Payment Method</label>
                        <select id="payment_method" name="payment_method" class="form-control" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
    <option value="" style="color: black;">Select a payment method</option>
    <option value="credit_card" style="color: black;">Credit Card</option>
    <option value="paypal" style="color: black;">PayPal</option>
</select>

                    </div>
                </div>

                <div class="col-md-6" style="width: 50%; padding: 15px;">
                    <h3 style="font-family: 'Poppins', sans-serif; font-size: 1.8rem; color: #e0e0e0; font-weight: 700; margin-bottom: 20px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Your Order</h3>
                    <table class="table table-bordered" style="background: linear-gradient(135deg, #2c5364, #4a7390); border: none; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
                        <thead style="background: linear-gradient(45deg, #0f2027, #2c5364); color: #e0e0e0; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 700; text-transform: uppercase;">
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
                                <?php if (!isset($products[$product_id])) continue; ?>
                                <?php
                                $product = $products[$product_id];
                                $subtotal = $product['price'] * $quantity;
                                $total += $subtotal;
                                ?>
                                <tr style="background: #2c5364;">
                                    <td style="padding: 15px; text-align: center; vertical-align: middle; border: none;">
                                        <img src="<?= htmlspecialchars($product['image_url']) ?>" class="img-fluid" style="height: 100px; width: auto; border-radius: 8px; object-fit: cover; filter: saturate(1.2) brightness(1.1); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                    </td>
                                    <td style="padding: 15px; text-align: center; vertical-align: middle; font-family: 'Poppins', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 600; border: none;"><?= htmlspecialchars($product['name']) ?></td>
                                    <td style="padding: 15px; text-align: center; vertical-align: middle; font-family: 'Poppins', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 600; border: none;">₹<?= number_format($product['price'], 2) ?></td>
                                    <td style="padding: 15px; text-align: center; vertical-align: middle; font-family: 'Poppins', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 600; border: none;"><?= htmlspecialchars($quantity) ?></td>
                                    <td style="padding: 15px; text-align: center; vertical-align: middle; font-family: 'Poppins', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 600; border: none;">₹<?= number_format($subtotal, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.5rem; color: #e0e0e0; font-weight: 800; text-align: right; margin-top: 20px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Total: ₹<?= number_format($total, 2) ?></h4>
                </div>
            </div>
            <div class="product pT2" style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn btn-success btn-lg" style="padding: 12px 30px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; border: none; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); transition: all 0.3s ease; animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';">Place Order</button>
            </div>
        </form>
    <?php else: ?>
        <p class="text-center lead" style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; color: #e0e0e0; font-weight: 600; margin: 30px 0; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">Your cart is empty.</p>
        <div class="product pT2 text-center" style="margin-bottom: 30px;">
            <a href="home.php" class="btn btn-primary btn-lg custom-button1" style="padding: 12px 30px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; text-decoration: none; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); transition: all 0.3s ease; animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';">Return to Home</a>
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