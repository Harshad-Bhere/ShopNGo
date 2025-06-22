<?php
session_start();
require_once('../controllers/Utils.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop N Go</title>
    <link rel="icon" type="image/x-icon" href="../images/logo.jpg">

    <!-- Bootstrap + Google Fonts + Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../styles/style.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="../js/script.js"></script>

    <style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f0f2f5;
    }

    .navbar {
        background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        padding: 10px 20px;
    }

    .navbar .navbar-brand {
        font-weight: bold;
        font-size: 1.5rem;
        color: #ffffff !important;
        display: flex;
        align-items: center;
    }

    .navbar .navbar-brand img {
        height: 42px;
        width: 42px;
        object-fit: cover;
        border-radius: 50%;
        margin-right: 10px;
    }

    .navbar-nav .nav-link {
        color: #e0e0e0 !important;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: 6px;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff !important;
    }

    .nav-item.active .nav-link {
        background-color: rgba(255, 255, 255, 0.2);
        color: #ffffff !important;
        font-weight: 600;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top mb-4">
        <a class="navbar-brand d-flex align-items-center" href="home.php">
            <img src="../images/logo.jpg" alt="Shop N Go" class="logo"> Shop N Go
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse nav-font" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?= $_SESSION['page'] == 'Home' ? 'active' : '' ?>">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item <?= $_SESSION['page'] == 'About' ? 'active' : '' ?>">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
                <li class="nav-item <?= $_SESSION['page'] == 'Product' ? 'active' : '' ?>">
                    <a class="nav-link" href="product.php">Products</a>
                </li>
                <li class="nav-item <?= $_SESSION['page'] == 'Cart' ? 'active' : '' ?>">
                    <a class="nav-link" href="cart.php">Cart</a>
                </li>
                <li class="nav-item <?= $_SESSION['page'] == 'Checkout' ? 'active' : '' ?>">
                    <a class="nav-link" href="checkout.php">Checkout</a>
                </li>
                <li class="nav-item <?= $_SESSION['page'] == 'Orders' ? 'active' : '' ?>">
                    <a class="nav-link" href="orders.php">Orders</a>
                </li>
                <?php if ($_SESSION['userId']) : ?>
                    <li class="nav-item <?= $_SESSION['page'] == 'AddProduct' ? 'active' : '' ?>">
                        <a class="nav-link" href="addProduct.php">Add Product</a>
                    </li>
                    <li class="nav-item <?= $_SESSION['page'] == 'Logout' ? 'active' : '' ?>">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item <?= $_SESSION['page'] == 'Login' ? 'active' : '' ?>">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</body>