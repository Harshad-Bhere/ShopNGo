<?php 
ini_set('display_errors', 0);
error_reporting(0);
session_start();
$_SESSION['page'] = 'Home';
include 'header.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1250"> <!-- Fixed width, non-responsive -->
    <title>Shop N Go - Home</title>
    <link rel="icon" type="image/x-icon" href="../images/logo.jpg">
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background: linear-gradient(135deg, #f0f4f8, #d9e2ec); min-height: 100vh; margin: 0;">
    <!-- Hero Section -->
    <div style="position: relative; background: url('../images/logo.jpg') no-repeat center center; background-size: cover; padding: 100px 0; text-align: center; overflow: hidden; width: 100%; height: 1208px;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(15, 42, 68, 0.7), rgba(30, 77, 119, 0.7)); z-index: 1;"></div>
        <div style="max-width: 1250px; margin: 365px auto; position: relative; z-index: 2; padding: 0 30px;">
            <h1 style="font-family: 'Playfair Display', serif; font-size: 4rem; color: #FFD700; font-weight: 800; text-shadow: 0 4px 8px rgba(0, 0, 0, 0.6); margin-bottom: 20px; letter-spacing: 2px; text-transform: uppercase;">Welcome to Shop N Go</h1>
            <p style="font-family: 'Poppins', sans-serif; font-size: 1.8rem; color: #F5F5DC; font-weight: 600; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4); margin-bottom: 30px;">Your one-stop shop for all your grocery needs!</p>
            <a href="product.php" style="display: inline-block; padding: 14px 30px; background: linear-gradient(45deg, #228B22, #FFD700); color: #0f2a44; font-family: 'Poppins', sans-serif; font-size: 1.2rem; font-weight: 800; text-transform: uppercase; border-radius: 10px; text-decoration: none; transition: all 0.4s ease; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); animation: glow 1.5s infinite alternate;" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">Browse Products</a>
        </div>
    </div>

    <!-- Featured Categories -->
    <div style="max-width: 1250px; margin: 60px auto; padding: 0 30px;">
        <h2 style="text-align: center; font-family: 'Playfair Display', serif; font-size: 2.5rem; color: #0f2a44; margin-bottom: 40px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); letter-spacing: 1px;">Featured Categories</h2>
        <div style="display: flex; justify-content: space-between; gap: 30px;">
            <div style="width: 380px; background: linear-gradient(135deg, #F5F5DC, #d4d4a3); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); overflow: hidden; transition: transform 0.4s ease, box-shadow 0.4s ease;">
                <img src="../images/vegetables.jpg" alt="Vegetables" style="width: 100%; height: 220px; object-fit: cover; border-radius: 12px 12px 0 0; filter: saturate(1.2) brightness(1.1); transition: transform 0.4s ease;">
                <div style="padding: 20px; text-align: center; background: #ffffff; border-top: 3px solid #FFD700;">
                    <h5 style="font-family: 'Poppins', sans-serif; font-size: 1.6rem; color: #0f2a44; margin-bottom: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Vegetables</h5>
                    <p style="font-family: 'Inter', sans-serif; font-size: 1rem; color: #2d3748; margin-bottom: 20px; line-height: 1.6; height: 60px; overflow: hidden;">Fresh and organic vegetables for a healthy lifestyle.</p>
                    <a href="product.php?category=Vegetables" style="display: inline-block; padding: 10px 25px; background: linear-gradient(45deg, #228B22, #FFD700); color: #0f2a44; font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 800; text-transform: uppercase; border-radius: 8px; text-decoration: none; transition: all 0.4s ease; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">Shop Now</a>
                </div>
            </div>
            <div style="width: 380px; background: linear-gradient(135deg, #F5F5DC, #d4d4a3); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); overflow: hidden; transition: transform 0.4s ease, box-shadow 0.4s ease;">
                <img src="../images/fruits.jpg" alt="Fruits" style="width: 100%; height: 220px; object-fit: cover; border-radius: 12px 12px 0 0; filter: saturate(1.2) brightness(1.1); transition: transform 0.4s ease;">
                <div style="padding: 20px; text-align: center; background: #ffffff; border-top: 3px solid #FFD700;">
                    <h5 style="font-family: 'Poppins', sans-serif; font-size: 1.6rem; color: #0f2a44; margin-bottom: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Fruits</h5>
                    <p style="font-family: 'Inter', sans-serif; font-size: 1rem; color: #2d3748; margin-bottom: 20px; line-height: 1.6; height: 60px; overflow: hidden;">A variety of fresh fruits packed with vitamins.</p>
                    <a href="product.php?category=Fruits" style="display: inline-block; padding: 10px 25px; background: linear-gradient(45deg, #228B22, #FFD700); color: #0f2a44; font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 800; text-transform: uppercase; border-radius: 8px; text-decoration: none; transition: all 0.4s ease; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">Shop Now</a>
                </div>
            </div>
            <div style="width: 380px; background: linear-gradient(135deg, #F5F5DC, #d4d4a3); border-radius: 12px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); overflow: hidden; transition: transform 0.4s ease, box-shadow 0.4s ease;">
                <img src="../images/dairy.jpg" alt="Dairy" style="width: 100%; height: 220px; object-fit: cover; border-radius: 12px 12px 0 0; filter: saturate(1.2) brightness(1.1); transition: transform 0.4s ease;">
                <div style="padding: 20px; text-align: center; background: #ffffff; border-top: 3px solid #FFD700;">
                    <h5 style="font-family: 'Poppins', sans-serif; font-size: 1.6rem; color: #0f2a44; margin-bottom: 15px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Dairy</h5>
                    <p style="font-family: 'Inter', sans-serif; font-size: 1rem; color: #2d3748; margin-bottom: 20px; line-height: 1.6; height: 60px; overflow: hidden;">High-quality dairy products for your daily needs.</p>
                    <a href="product.php?category=Dairy" style="display: inline-block; padding: 10px 25px; background: linear-gradient(45deg, #228B22, #FFD700); color: #0f2a44; font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 800; text-transform: uppercase; border-radius: 8px; text-decoration: none; transition: all 0.4s ease; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);" onmouseover="this.style.transform='scale(1.05)';" onmouseout="this.style.transform='scale(1)';">Shop Now</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php include 'footer.php'; ?>