<?php
ini_set('display_errors', 0);
error_reporting(0);
session_start();
$_SESSION['page'] = 'About';
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Shop N Go</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&family=Playfair+Display:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            height: 500px;
            background: url('../images/grocery_hero.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            margin-top: 70px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(15, 32, 39, 0.7), rgba(32, 58, 67, 0.7));
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            animation: fadeIn 1.5s ease-in-out;
        }

        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            color: #FFD700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
            letter-spacing: 2px;
        }

        .hero-content p {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 40px;
            background: linear-gradient(135deg, #F5F5DC, #d4d4a3);
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        /* Section Headings */
        .section-heading {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            color: #228B22;
            font-weight: 800;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
            text-align: center;
            letter-spacing: 1px;
        }

        /* Mission Section */
        .mission-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: center;
            margin-bottom: 60px;
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease-out;
        }

        .mission-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .mission-text p {
            font-size: 1.2rem;
            color: #0f2a44;
            line-height: 1.8;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .mission-image img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
            filter: saturate(1.2) brightness(1.1);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .mission-image img:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

        /* Team Section */
        .team-section {
            margin-bottom: 60px;
        }

        .team-carousel .carousel-item {
            padding: 20px;
        }

        .team-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .team-card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        }

        .team-card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #FFD700;
            margin-bottom: 15px;
            filter: saturate(1.2) brightness(1.1);
        }

        .team-card h5 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            color: #0f2a44;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .team-card p {
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            color: #0f2a44;
        }

        /* Values Section */
        .values-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 60px;
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease-out;
        }

        .values-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .value-card {
            background: linear-gradient(135deg, #ffffff, #f0f4f8);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .value-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }

        .value-card h5 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.3rem;
            color: #228B22;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .value-card p {
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            color: #0f2a44;
        }

        /* Contact Section */
        .contact-section {
            text-align: center;
            background: rgba(15, 32, 39, 0.9);
            border-radius: 15px;
            padding: 30px;
            color: #fff;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease-out;
        }

        .contact-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .contact-section p {
            font-family: 'Inter', sans-serif;
            font-size: 1.1rem;
            margin-bottom: 10px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .contact-button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(45deg, #228B22, #FFD700);
            color: #0f2a44;
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            animation: pulse 2s infinite;
            transition: all 0.4s ease;
        }

        .contact-button:hover {
            transform: scale(1.1) translateY(-3px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5);
            animation: none;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(255, 215, 0, 0); }
            100% { box-shadow: 0 0 0 0 rgba(255, 215, 0, 0); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.2rem;
            }

            .mission-section {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .values-section {
                grid-template-columns: 1fr;
            }

            .team-card img {
                width: 100px;
                height: 100px;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1>About Shop N Go</h1>
            <p>Your Premier Destination for Fresh Groceries</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Mission Section -->
        <section class="mission-section">
            <div class="mission-text">
                <h2 class="section-heading">Our Mission</h2>
                <p>
                    At Shop N Go, we are dedicated to revolutionizing grocery shopping by delivering fresh, high-quality products directly to your doorstep. Our platform, built with cutting-edge technology, offers a seamless experience for discovering fruits, vegetables, and dairy, all priced in Indian Rupees (₹). We strive to uphold sustainability, customer satisfaction, and innovation, ensuring every order is a step toward a healthier, happier you.
                </p>
            </div>
            <div class="mission-image">
                <img src="../images/groceryimg.jpg" alt="Fresh Groceries">
            </div>
        </section>

       

        <!-- Values Section -->
        <section class="values-section">
            <div class="value-card">
                <h5>Quality</h5>
                <p>We source only the freshest groceries from trusted suppliers to ensure top-notch quality in every order.</p>
            </div>
            <div class="value-card">
                <h5>Convenience</h5>
                <p>Shop anytime, anywhere, with our intuitive platform designed for effortless browsing and ordering.</p>
            </div>
            <div class="value-card">
                <h5>Sustainability</h5>
                <p>We prioritize eco-friendly practices, from sustainable sourcing to minimal packaging.</p>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="contact-section">
            <h2 class="section-heading">Get in Touch</h2>
            <p><strong>Email:</strong> support@shopngo.in</p>
            <p><strong>Phone:</strong> +91 98765 43210</p>
            <p><strong>Address:</strong> 123 Market Street, Mumbai, Maharashtra 400001, India</p>
            
        </section>
    </div>

    <!-- JavaScript for Scroll Animations -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('.mission-section, .values-section, .contact-section');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            sections.forEach(section => observer.observe(section));
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php include 'footer.php'; ?>
</body>
</html>