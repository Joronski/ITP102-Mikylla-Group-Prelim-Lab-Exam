<?php
    session_start();
    include 'config.php';

    # Initialize an array to store messages
    $message = [];

    # Check if the form is submitted
    if (isset($_POST['submit'])) {
        # Secure input data to prevent SQL injection
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
        
        # Handle image upload
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/' . $image;

        # Validate password: At least one uppercase letter, one special character, and minimum length of 6
        if (!preg_match('/^(?=.*[A-Z])(?=.*\W).{6,}$/', $password)) {
            $message[] = 'Password must contain at least one uppercase letter and one special character!';
        } elseif ($password !== $cpassword) {
            $message[] = 'Confirm password does not match!';
        } elseif ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            # Encrypt password before storing
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            # Insert user data into the database
            $insert = mysqli_query($conn, "INSERT INTO `user_form` (username, email, password, image) VALUES ('$username', '$email', '$hashed_password', '$image')");
            
            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $_SESSION['success'] = 'Registered Successfully! Welcome to Eventsphere!';
                header('Location: login.php');
                exit();
            } else {
                $message[] = 'Registration Failed!';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eventsphere | Register</title>
        
        <!-- Favicon Links for different devices -->
        <link rel="icon" type="image/png" sizes="32x32" href="favicon_io/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="favicon_io/favicon-16x16.png">
        <link rel="manifest" href="favicon_io/site.webmanifest">

        <!-- Google Fonts for styling -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Custom Stylesheet for page design -->
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <section class="form-container">
            <form action="" id="registerForm" method="post" enctype="multipart/form-data">
                <!-- Display the logo -->
                <img src="img/default-avatar.jpg" alt="Eventsphere Logo" width="250" height="250">
                
                <!-- Form title and subtitle -->
                <h1>Welcome to Eventsphere!</h1>
                <br>
                <h2>Register</h2>
                
                <!-- Display error or success messages -->
                <?php if (!empty($message)): ?>
                    <div class="message-box">
                        <?php foreach ($message as $msg): ?>
                            <div class="message"> <?= htmlspecialchars($msg) ?> </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Input fields for user registration -->
                <input type="text" id="username" class="box" placeholder="Enter Username" name="username" required>
                <input type="email" id="email" class="box" placeholder="Enter Email" name="email" required>
                <input type="password" id="password" class="box" placeholder="Enter Password" name="password" required>
                <input type="password" id="cpassword" class="box" placeholder="Confirm Password" name="cpassword" required>
                
                <!-- Image upload field -->
                <label for="image">Choose Profile Picture</label>
                <input type="file" id="image" class="box" accept="image/jpg, image/jpeg, image/png" name="image" required>
                
                <!-- Submit button for form submission -->
                <input type="submit" name="submit" value="REGISTER" class="btn">
                
                <!-- Redirect link for existing users -->
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
        </section>

        <!-- Custom Script for additional functionality -->
        <script src="js/script.js"></script>
    </body>
</html>