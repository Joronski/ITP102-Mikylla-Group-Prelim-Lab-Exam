<?php
    # Include configuration file and start session
    include 'config.php';
    session_start();

    # Check if the form is submitted
    if (isset($_POST['submit'])) {
        
        # Escape special characters to prevent SQL injection
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password'])); # Using md5 for encryption (Consider more secure hashing like password_hash)

        # Query to check if the user exists
        $query = "SELECT * FROM `user_form` WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $query) or die('<h1>Query Failed</h1>');

        # If user found, start session and redirect to home page
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id'];
            header('location: home.php');
            exit();
        } else {
            $message[] = 'Incorrect Email or Password!';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eventsphere | Login</title>

        <!-- Favicon Links -->
        <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
        <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
        <link rel="manifest" href="favicon_io/site.webmanifest">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="css/style.css">

        <!-- Google reCAPTCHA & jQuery -->
        <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    </head>
    <body>
        <section class="form-container">
            <form action="" id="loginForm" method="post" enctype="multipart/form-data">
                <img src="img/default-avatar.jpg" alt="Eventsphere Logo" width="250" height="250">

                <h1>Welcome Back to Eventsphere!</h1>
                <br>
                <h2>Login</h2>

                <!-- Display error message if credentials are incorrect -->
                <?php 
                    if (!empty($message)) {
                        foreach ($message as $msg) {
                            echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
                        }
                    }
                ?>
                
                <!-- Email & Password Fields -->
                <input type="email" id="email" class="box" placeholder="Enter Email" name="email" required>
                <input type="password" id="password" class="box" placeholder="Enter Password" name="password" required>
                
                <!-- Google reCAPTCHA -->
                <div class="recaptcha-container">
                    <div class="g-recaptcha" data-sitekey="6Lcmzd0qAAAAABKw7t8tNTstpWsElSmRgsnU2EIL" data-action="LOGIN"></div>
                </div>
                
                <!-- Submit Button -->
                <input type="submit" id="login" name="submit" value="LOG IN" class="btn">
                
                <!-- Additional Links -->
                <p><a href="forgot_password.php">Forgot Password?</a></p>
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </section>

        <!-- External JavaScript Files -->
        <script src="js/script.js"></script>
        <script src="js/reCaptcha.js"></script>
    </body>
</html>