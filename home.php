<?php
    # Include the configuration file for database connection
    include 'config.php';
    
    # Start the session to maintain user login state
    session_start();
    
    # Retrieve the user ID from session
    $user_id = $_SESSION['user_id'];
    
    # Redirect to login page if user is not logged in
    if (!isset($user_id)) {
        header('location: login.php');
    };
    
    # Handle user logout and destroy session
    if (isset($_GET['logout'])) {
        unset($user_id);
        session_destroy();
        header(('location: login.php'));
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eventsphere | Home</title>

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

        <!-- Custom CSS File -->
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <section class="container">
            <div class="profile">
                <!-- Default Profile Image -->
                <img src="img/default-avatar.jpg" alt="Eventsphere Logo" width="200" height="200">
                
                <h3>Welcome to Eventsphere!</h3>
                <br>
                
                <?php
                    # Fetch user data from the database
                    $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('<h1>Query Failed</h1>');
                    
                    # Check if user data is available
                    if (mysqli_num_rows($select) > 0) {
                        $fetch = mysqli_fetch_assoc($select);
                    }
                    
                    # Display user profile image if available, otherwise use default
                    if ($fetch['image'] == '') {
                        echo '<img src="images/default-avatar.png" alt="Profile Picture">';
                    } else {
                        echo '<img src="uploaded_img/'.$fetch['image'].'" alt="Profile Picture">';
                    }
                ?>
                
                <!-- Display Username -->
                <h3><?php echo $fetch['username']; ?></h3>
                
                <!-- Profile Update and Logout Buttons -->
                <a href="update_profile.php" class="btn-home">UPDATE PROFILE</a>
                <a href="home.php?logout=<?php echo $user_id; ?>" class="delete-btn-home">LOG OUT</a>
                
                <!-- Login and Register Links -->
                <p>New <a href="login.php">Login</a> or <a href="register.php">Register</a></p>
            </div>
        </section>
    </body>
</html>