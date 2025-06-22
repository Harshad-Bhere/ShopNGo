<?php
ini_set('display_errors', 0);
error_reporting(0);
session_start();
$_SESSION['page'] = 'Orders';
include 'header.php';

// Fetch past orders
$orders = getOrdersByUserId();
?>

<body style="font-family: 'Inter', Arial, sans-serif; background: #5e88b2; min-height: 100vh; margin: 0; padding: 0;">
<div class="container orders" style="max-width: 1100px; margin: 90px auto; padding: 25px; background: linear-gradient(135deg, #0f2a44, #1e4d77); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);">
    <h1 class="text-center mb-4" style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #e0e0e0; font-weight: 800; margin-bottom: 30px; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); letter-spacing: 1px;">Past Orders</h1>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info text-center" style="background: linear-gradient(135deg, #2c5364, #4a7390); color: #e0e0e0; border: none; border-radius: 10px; padding: 15px; margin-bottom: 20px; font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 600; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">No past orders found.</div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card mb-4" style="background: linear-gradient(135deg, #2c5364, #4a7390); border: none; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.02)';" onmouseout="this.style.transform='scale(1)';">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(45deg, #0f2027, #2c5364); color: #e0e0e0; border-radius: 10px 10px 0 0; padding: 15px;">
                    <div>
                        <h5 style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; font-weight: 700; margin-bottom: 5px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);">Order ID: <?= htmlspecialchars($order['order_id']); ?></h5>
                        <p style="font-family: 'Inter', sans-serif; font-size: 0.9rem; margin: 0; color: #e0e0e0; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">Date: <?= htmlspecialchars($order['created_at']); ?></p>
                        <p style="font-family: 'Inter', sans-serif; font-size: 0.9rem; margin: 0; color: #e0e0e0; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">Total Price: ₹<?= number_format($order['total_price'], 2); ?></p>
                        <p style="font-family: 'Inter', sans-serif; font-size: 0.9rem; margin: 0; color: #e0e0e0; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">Status: <?= htmlspecialchars($order['status']); ?></p>
                    </div>
                    <a target="_blank" href="generate_invoice.php?order_id=<?= htmlspecialchars($order['order_id']); ?>" class="btn btn-primary" style="white-space: nowrap; padding: 8px 20px; background: linear-gradient(45deg, #0f2027, #2c5364); color: #ffffff; font-family: 'Poppins', sans-serif; font-size: 0.9rem; font-weight: 700; text-transform: uppercase; border-radius: 8px; border: none; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 6px 12px rgba(0, 0, 0, 0.4)';" onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 4px 8px rgba(0, 0, 0, 0.3)';">Get Invoice</a>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <h6 style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; color: #e0e0e0; font-weight: 700; margin-bottom: 15px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); letter-spacing: 1px;">Order Items:</h6>
                    <ul class="list-group" style="border: none;">
                        <?php
                        $orderItems = getOrderItemsByOrderId($order['order_id']);
                        $productIds = [];
                        foreach ($orderItems as $item) {
                            $productIds[] = $item['product_id'];
                        }
                        $products = getProducts($productIds);
                        foreach ($orderItems as $item): ?>
                            <li class="list-group-item" style="background: linear-gradient(135deg, #2c5364, #4a7390); border: none; border-radius: 8px; margin-bottom: 10px; padding: 15px; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.01)';" onmouseout="this.style.transform='scale(1)';">
                                <div class="row align-items-center" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
                                    <div class="col-md-4 text-center" style="width: 33.33%; padding: 10px;">
                                        <img src="<?= htmlspecialchars($products[$item['product_id']]['image_url']); ?>" alt="<?= htmlspecialchars($products[$item['product_id']]['name']); ?>" style="width: 277px; height: 80px; object-fit: cover; border-radius: 5px; filter: saturate(1.2) brightness(1.1); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">
                                    </div>
                                    <div class="col-md-8" style="width: 66.67%; padding: 10px;">
                                        <p style="font-family: 'Inter', sans-serif; font-size: 1rem; color: #e0e0e0; font-weight: 600; margin-bottom: 5px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"><strong><?= htmlspecialchars($products[$item['product_id']]['name']); ?></strong></p>
                                        <p style="font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #e0e0e0; margin: 0 0 5px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"><label style="color: #ffffff; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">Quantity:</label> <?= htmlspecialchars($item['quantity']); ?></p>
                                        <p style="font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #e0e0e0; margin: 0 0 5px; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"><label style="color: #ffffff; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">Price:</label> ₹<?= number_format($item['price'], 2); ?></p>
                                        <p style="font-family: 'Inter', sans-serif; font-size: 0.9rem; color: #e0e0e0; margin: 0; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);"><label style="color: #ffffff; font-weight: 600; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);">Description:</label> <?= htmlspecialchars($products[$item['product_id']]['description']); ?></p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
@keyframes glow {
    from {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    to {
        box-shadow: 0 6px 12px rgba(44, 83, 100, 0.4);
    }
}
</style>

<?php include 'footer.php'; ?>
</body>