<!-- Footer -->
<?php 
ini_set('display_errors', 0);
error_reporting(0);
if(isset($_SESSION['message'])) { ?>
    <div class="alert alert-primary text-center" role="alert" style="font-family: 'Poppins', sans-serif; font-size: 1rem; font-weight: 500;">
        <?= $_SESSION['message'] ?>
    </div>
<?php 
    unset($_SESSION['message']);
} ?>

<footer class="bg-dark text-white py-4" style="font-family: 'Poppins', sans-serif; background: linear-gradient(145deg, #1a1a1a, #0f0f0f); border-top: 1px solid #444;">
    <div class="container text-center">
        <p class="mb-2" style="font-size: 1rem; font-weight: 400;">&copy; 2024 <strong style="color: #FFD700;">Shop N Go</strong>. All rights reserved.</p>
        
        <ul class="list-inline mb-3">
            <li class="list-inline-item mx-2">
                <a href="privacy_policy.php" class="text-white" style="text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='white'">Privacy Policy</a>
            </li>
            <li class="list-inline-item mx-2">
                <a href="terms_of_service.php" class="text-white" style="text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='white'">Terms of Service</a>
            </li>
            <li class="list-inline-item mx-2">
                <a href="faq.php" class="text-white" style="text-decoration: none; transition: color 0.3s ease;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='white'">FAQ</a>
            </li>
        </ul>
        
        <div class="social-icons mt-2">
            <a href="#" style="color: #fff; margin: 0 10px; font-size: 1.2rem; transition: color 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='white'"><i class="fab fa-facebook-f"></i></a>
            <a href="#" style="color: #fff; margin: 0 10px; font-size: 1.2rem; transition: color 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='white'"><i class="fab fa-twitter"></i></a>
            <a href="#" style="color: #fff; margin: 0 10px; font-size: 1.2rem; transition: color 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='white'"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</footer>

<!-- Include JS Libraries -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Include Font Awesome for social icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
