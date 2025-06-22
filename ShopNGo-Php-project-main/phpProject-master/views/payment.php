<?php
ini_set('display_errors', 0);
error_reporting(0);

session_start();
$_SESSION['page'] = 'Payment';
include 'header.php';

if (!isset($_SESSION['email'])) {
    $_SESSION['message'] = "Please login!";
    $_SESSION['redirect_url'] = "payment.php";
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['checkout_data']) || !isset($_SESSION['cart'])) {
    $_SESSION['message'] = "Invalid checkout data. Please start over.";
    header('Location: checkout.php');
    exit;
}

$payment_method = $_SESSION['checkout_data']['payment_method'] ?? [];
$products = $_SESSION['checkout_data']['products'] ?? [];
$errors = [];
$show_otp_form = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['otp'])) {
        // Handle OTP submission
        $otp = htmlspecialchars(trim($_POST['otp'] ?? ''));
        if (!preg_match('/^\d{6}$/', $otp)) {
            $errors[] = 'OTP must be a 6-digit number.';
        } else {
            // Valid OTP, complete order
            unset($_SESSION['cart'], $_SESSION['cartId'], $_SESSION['checkout_data']);
            $_SESSION['message'] = "Order successfully placed!";
            header('Location: orders.php');
            exit;
        }
    } else {
        // Handle payment details submission
        if ($payment_method === 'credit_card') {
            $card_number = htmlspecialchars(trim($_POST['card_number'] ?? ''));
            $expiry = htmlspecialchars(trim($_POST['expiry'] ?? ''));
            $cvv = htmlspecialchars(trim($_POST['cvv'] ?? ''));

            // Validate credit card details
            if (!preg_match('/^\d{16}$/', $card_number)) {
                $errors[] = 'Card number must be 16 digits.';
            }
            if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiry)) {
                $errors[] = 'Expiry must be in MM/YY format.';
            }
            if (!preg_match('/^\d{3}$/', $cvv)) {
                $errors[] = 'CVV must be 3 digits.';
            }
        } elseif ($payment_method === 'paypal') {
            $paypal_email = htmlspecialchars(trim($_POST['paypal_email'] ?? ''));

            // Validate PayPal email
            if (!filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Please enter a valid email address for PayPal.';
            }
        } else {
            $errors[] = 'Invalid payment method.';
        }

        if (empty($errors)) {
            $show_otp_form = true; // Show OTP form if payment details are valid
        }
    }
}

// Calculate total for display
$total = 0;
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    if (isset($products[$product_id])) {
        $total += $products[$product_id]['price'] * $quantity;
    }
}
?>

<body style="font-family: 'Inter', Arial, sans-serif; background: #5e88b2; min-height: 100vh; margin: 0; padding: 0;">
<div class="container" style="max-width: 600px; margin: 90px auto; padding: 25px; background: linear-gradient(135deg, #0f2a44, #1e4d77); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
    <h1 class="text-center" style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #e0e0e0; margin-bottom: 30px; font-weight: 800; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); letter-spacing: 1px;">
        <?php echo $show_otp_form ? 'Verify OTP' : 'Payment Details'; ?>
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #2c5364, #4a7390); color: #ff6b6b; border: 2px solid #ff6b6b; border-radius: 10px; padding: 15px; margin-bottom: 20px; font-family: 'Poppins', sans-serif; font-size: 1rem; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach ($errors as $error): ?>
                    <li style="font-weight: 500; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success text-center" style="background: linear-gradient(135deg, #2c5364, #4a7390); color: #e0e0e0; border: none; border-radius: 10px; padding: 15px; margin-bottom: 20px; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 600; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <h4 style="font-family: 'Poppins', sans-serif; font-size: 1.5rem; color: #e0e0e0; font-weight: 800; text-align: center; margin-bottom: 20px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Order Total: ₹<?= number_format($total, 2) ?></h4>

    <?php if ($show_otp_form): ?>
        <form method="post">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="otp" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Enter OTP</label>
                <input type="text" id="otp" name="otp" class="form-control" placeholder="123456" pattern="\d{6}" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
            </div>
            <div class="text-center" style="margin-top: 30px;">
                <button type="submit" class="btn btn-success btn-lg" style="padding: 12px 30px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; border: none; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); transition: all 0.3s ease; animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';">Verify OTP</button>
            </div>
        </form>
    <?php else: ?>
        <form method="post">
            <?php if ($payment_method === 'credit_card'): ?>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="card_number" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Card Number</label>
                    <input type="text" id="card_number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" pattern="\d{16}" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                </div>
                <div class="form-row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
                    <div class="form-group col-md-6" style="width: 50%; padding: 10px; margin-bottom: 20px;">
                        <label for="expiry" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Expiry (MM/YY)</label>
                        <input type="text" id="expiry" name="expiry" class="form-control" placeholder="MM/YY" pattern="(0[1-9]|1[0-2])/[0-9]{2}" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                    </div>
                    <div class="form-group col-md-6" style="width: 50%; padding: 10px; margin-bottom: 20px;">
                        <label for="cvv" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">CVV</label>
                        <input type="text" id="cvv" name="cvv" class="form-control" placeholder="123" pattern="\d{3}" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                    </div>
                </div>
            <?php elseif ($payment_method === 'paypal'): ?>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="paypal_email" style="display: block; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; margin-bottom: 8px; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">PayPal Email</label>
                    <input type="email" id="paypal_email" name="paypal_email" class="form-control" placeholder="example@paypal.com" required style="width: 100%; padding: 10px; border: 2px solid #2c5364; border-radius: 8px; font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; background: linear-gradient(135deg, #2c5364, #4a7390); transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);" onfocus="this.style.borderColor='#4a7390'; this.style.transform='scale(1.02)';" onblur="this.style.borderColor='#2c5364'; this.style.transform='scale(1)';">
                </div>
            <?php else: ?>
                <p class="text-center" style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; color: #ff6b6b; font-weight: 600;">Invalid payment method selected.</p>
            <?php endif; ?>
            <div class="text-center" style="margin-top: 30px;">
                <button type="submit" class="btn btn-success btn-lg" style="padding: 12px 30px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; border: none; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); transition: all 0.3s ease; animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 8px 15px rgba(0, 0, 0, 0.5)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';">Proceed to OTP</button>
            </div>
        </form>
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