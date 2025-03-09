<?php
    # Include configuration file
    include 'config.php';
    
    # Start session
    session_start();
    
    # Check if token is provided
    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        # Query to check if token exists in the database
        $check_token = mysqli_query($conn, "SELECT * FROM `user_form` WHERE reset_token = '$token'") or die('<h1>Query Failed</h1>');

        # If token is found, get user email
        if (mysqli_num_rows($check_token) > 0) {
            $row = mysqli_fetch_assoc($check_token);
            $email = $row['email'];
        } else {
            die('<h1>Invalid or Expired Token</h1>');
        }
    } else {
        die('<h1>No Token Provided</h1>');
    }

    # Handle password reset
    if (isset($_POST['submit'])) {
        $new_password = $_POST['password'];
        
        # Validate password strength
        if (!preg_match('/[A-Z]/', $new_password) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $new_password)) {
            $message[] = "Password must contain at least one uppercase letter and one special character!";
        } else {
            # Hash the new password
            $hashed_password = mysqli_real_escape_string($conn, md5($new_password));
            
            # Update password in the database and reset the token
            $update_password = mysqli_query($conn, "UPDATE `user_form` SET password = '$hashed_password', reset_token = NULL WHERE email = '$email'") or die('<h1>Query Failed</h1>');
    
            # If update is successful, notify user
            if ($update_password) {
                $message[] = "Password Reset Successful! Return to <a href='login.php' class='redirect'>LOG IN</a>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eventsphere | Reset Password</title>

        <!-- Favicon Links -->
        <link rel="android-chrome" sizes="512x512" href="favicon_io/android-chrome-512x512.png">
        <link rel="android-chrome" sizes="192x192" href="favicon_io/android-chrome-512x512.png">
        <link rel="apple-touch-icon" sizes="180x180" href="favicon_io/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
        <link rel="icon" type="image/x-icon" href="favicon_io/favicon.ico">
        <link rel="manifest" href="favicon_io/site.webmanifest">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Custom CSS File Link -->
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <section class="form-container">
            <form action="" method="post">
                <img src="img/default-avatar.jpg" alt="Eventsphere Logo" width="200" height="200">
                <h2>Reset Password</h2>
                <p>Enter your new password below.</p>

                <!-- Display messages if any -->
                <?php 
                    if (isset($message)) {
                        foreach ($message as $message) {
                            echo '<div class="message">'.$message.'</div>';
                        }
                    }
                ?>

                <input type="password" name="password" class="box" placeholder="Enter New Password" required minlength="8">
                <input type="submit" name="submit" value="RESET PASSWORD" class="btn">
            </form>
        </section>

        <!-- Custom JavaScript External File -->
        <script src="js/script.js"></script>
    </body>
</html>