<?php 
ini_set('display_errors', 0);
error_reporting(0);
session_start();
$_SESSION['page'] = 'Login';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = htmlspecialchars(trim($_POST['password'] ?? ''));

    $userId = loginUser($email, $password);
    if (is_int($userId)) {
        $_SESSION['email'] = $email;
        $_SESSION['userId'] = $userId;
        $user = getUserByUserId($userId);
        $_SESSION['message'] = "Welcome " . $user['first_name'] . "!";
        if($_SESSION['redirect_url']){
            header('Location: ' . $_SESSION['redirect_url']);
        }else {
            header('Location: product.php');
        }
        exit;
    }else {
        $_SESSION['message'] = $userId;
    }

}
?>

<body style="margin: 0; background: linear-gradient(to right, #4e54c8, #8f94fb); font-family: 'Segoe UI', sans-serif;">
    <div class="container" style="max-width: 420px; margin: 120px auto; background-color: #ffffff; padding: 35px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
        <h2 style="text-align: center; margin-bottom: 25px; color: #343a40;">Login</h2>

        <?php if (isset($error)): ?>
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div style="margin-bottom: 20px;">
                <label for="email" style="font-weight: 600;">Email</label>
                <input type="email" name="email" id="email" value="<?php echo $_POST['email'] ?? ''; ?>" required
                    style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; margin-top: 5px;">
            </div>

            <div style="margin-bottom: 25px;">
                <label for="password" style="font-weight: 600;">Password</label>
                <input type="password" name="password" id="password" value="<?php echo $_POST['password'] ?? ''; ?>" required
                    style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; margin-top: 5px;">
            </div>

            <button type="submit"
                style="width: 100%; padding: 12px; background: linear-gradient(to right, #4e54c8, #8f94fb); border: none; color: white; font-weight: bold; border-radius: 6px; cursor: pointer;">
                Login
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <p style="font-size: 14px;">Don't have an account?
                <a href="register.php" style="color: #4e54c8; text-decoration: none; font-weight: 600;">Register here</a>.
            </p>
        </div>
    </div>
</body>


<?php include 'footer.php'; ?>
