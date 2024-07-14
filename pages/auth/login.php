<link rel="stylesheet" type="text/css" href="/leads/css/pages/auth/login.css" />


<?php
session_start();

require_once '../../config/dbleads.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $database = new Database();
    $db = $database->getConnection();

    // Retrieve email and password from form input
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Fetch hashed password and is_admin from database based on email
        $query = "SELECT * FROM users WHERE email = ?";
        $statement = $db->prepare($query);
        $statement->execute([$email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        // Verify password using password_verify
        if ($user && password_verify($password, $user['password'])) {
            // Passwords match, login successful
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user['user_id'];                                               
            $_SESSION['is_active'] = $user['is_active'];         
            $_SESSION['access_level'] = $user['access_level'];         
            header("Location: /leads/");
            exit();
        } else {
            // Passwords do not match, login failed
            echo "Invalid email or password";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<section class="login">
    <div>
        <img src="../../images/almost-there.gif" />
    </div>
    <div>
        <form action="" method="post">
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required />
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <button type="submit">Login</button>
            </div>
        </form>
        <div>
            <span>Don't have an account?</span>
            <span><a href="/leads/pages/user/create_user.php">Sign up</a></span>
        </div>
        <div>
            <span><a href="">Forgot password</a></span>
        </div>
    </div>

</section>