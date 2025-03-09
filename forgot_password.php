<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php'; # Include PHPMailer

    include 'config.php';
    session_start();

    $email_sent = false; # Default value

    if (isset($_POST['submit'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    
        # Check if the email exists in the database
        $check_email = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email'") or die("<h1>Query Failed</h1>");
    
        if (mysqli_num_rows($check_email) > 0) {
            # Generate a unique token
            $token = md5(rand().time());
            $update_token = mysqli_query($conn, "UPDATE `user_form` SET reset_token = '$token' WHERE email = '$email'") or die("<h1>Query Failed</h1>");
    
            # Get the domain dynamically
            $base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    
            # Password reset link
            $reset_link = $base_url . "/reset_password.php?token=" . $token;
    
            # Send email using PHPMailer
            $mail = new PHPMailer(true);
    
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.mailtrap.io';
                $mail->SMTPAuth = true;
                $mail->Username = '34e79b6c143a94';
                $mail->Password = '78333a9b100ff4';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 2525;
            
                $mail->setFrom('eventsphere@support.com', 'Eventsphere Support'); 
                $mail->addAddress($email); 
            
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = "Click the following link to reset your password: <a href='$reset_link'>$reset_link</a>";
            
                # Send email
                $mail->send();
                $message[] = 'Password reset email has been sent!';
                $email_sent = true; # Set the flag to true
            } catch (Exception $e) {
                $message[] = 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            }
        } else {
            $message[] = 'Email not found!';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eventsphere | Forgot Password</title>

        <!-- Favicon -->
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
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- Custom CSS File Link -->
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <section class="form-container">
            <form action="" method="post">
                <img src="img/default-avatar.jpg" alt="Eventsphere Logo" width="200" height="200">

                <h2>Forgot Password</h2>
                <p>Enter your email address to receive a password reset link.</p>
                
                <?php 
                    if (isset($message)) {
                        foreach ($message as $message) {
                            echo '<div class="message">'.$message.'</div>';
                        }
                    }
                ?>

                <input type="email" name="email" class="box" placeholder="Enter Email" required>
                <input type="submit" name="submit" value="Send Reset Link" class="btn">

                <p><a href="login.php">Back to Login</a></p>
            </form>
        </section>

        <!-- Custom JavaScript Internal -->
        <script>
            // Check if the email was sent (from PHP flag)
            var emailSent = <?php echo json_encode($email_sent); ?>;
            
            if (emailSent) {
                // Disable the email input field
                document.querySelector('input[name="email"]').disabled = true;

                // Disable the submit button
                document.querySelector('input[name="submit"]').disabled = true;
            }
        </script>
    </body>
</html>