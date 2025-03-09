<?php
    # Include the database configuration file
    include 'config.php';
    
    # Start the session
    session_start();
    
    # Get the user ID from session
    $user_id = $_SESSION['user_id'];

    # Check if the update profile form is submitted
    if (isset($_POST['update_profile'])) {
        # Sanitize user input for username and email
        $update_username = mysqli_real_escape_string($conn, $_POST['update_username']);
        $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);
        
        # Update username and email in the database
        mysqli_query($conn, "UPDATE `user_form` SET username = '$update_username', email = '$update_email' WHERE id = '$user_id'") or die('<h1>Query Failed</h1>');
        
        # Retrieve and encrypt password inputs
        $old_password = $_POST['old_password'];
        $update_password = mysqli_real_escape_string($conn, md5($_POST['update_password']));
        $new_password = mysqli_real_escape_string($conn, md5($_POST['new_password']));
        $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));
        
        # Password validation and update
        if (!empty($update_password) || !empty($new_password) || !empty($confirm_password)) {
            if ($update_password != $old_password) {
                $message[] = 'Old Password not matched!';
            } elseif ($new_password != $confirm_password) {
                $message[] = 'Confirm Password not matched!';
            } else {
                mysqli_query($conn, "UPDATE `user_form` SET password = '$confirm_password' WHERE id = '$user_id'") or die('<h1>Query Failed</h1>');
                $message[] = 'Password Updated Successfully!';
            }
        }
        
        # Image upload handling
        $update_image = $_FILES['update_image']['name'];
        $update_image_size = $_FILES['update_image']['size'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = 'uploaded_img/'.$update_image;
        
        if (!empty($update_image)) {
            if ($update_image_size > 2000000) {
                $message[] = 'Image Size is too large!';
            } else {
                # Update image in the database
                $image_update_query = mysqli_query($conn, "UPDATE `user_form` SET image = '$update_image' WHERE id = '$user_id'") or die('<h1>Query Failed</h1>');
                
                if ($image_update_query) {
                    move_uploaded_file($update_image_tmp_name, $update_image_folder);
                }
                
                $message[] = 'Image Updated Successfully!';
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Eventsphere | Update Profile</title>

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
        <section class="update-profile">
            <?php
                # Fetch user details from the database
                $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE id = '$user_id'") or die('<h1>Query Failed</h1>');
                
                if (mysqli_num_rows($select) > 0) {
                    $fetch = mysqli_fetch_assoc($select);
                }
            ?>
            
            <form action="" method="post" enctype="multipart/form-data">
                <h1>Update Profile</h1>
                
                <?php
                    # Display user profile image
                    if ($fetch['image'] == '') {
                        echo '<img src="images/default-avatar.png" alt="Profile Picture">';
                    } else {
                        echo '<img src="uploaded_img/'.$fetch['image'].'">';
                    }
                    
                    # Display messages
                    if (isset($message)) {
                        foreach ($message as $message) {
                            echo '<div class="message">'.$message.'</div>';
                        }
                    }
                ?>
                
                <div class="flex">
                    <div class="inputBox">
                        <label for="username">Username: </label>
                        <input type="text" id="username" name="update_username" value="<?php echo $fetch['username']; ?>" class="box" required>
                        
                        <label for="email">Email: </label>
                        <input type="email" id="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box" required>
                        
                        <label for="update_pic">Update your profile picture: </label>
                        <input type="file" id="update_pic" name="update_image" accept="image/jpg, image/jpeg, image/png" class="box">
                    </div>
                    
                    <div class="inputBox">
                        <input type="hidden" name="old_password" value="<?php echo $fetch['password']; ?>">
                        
                        <label for="prev_password">Old Password: </label>
                        <input type="password" id="prev_password" name="update_password" placeholder="Enter Previous Password" class="box" minlength="8">
                        
                        <label for="new_password">New Password: </label>
                        <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" class="box" minlength="8">
                        
                        <label for="confirm_password">Confirm Password: </label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" class="box" minlength="8">
                    </div>
                </div>
                
                <input type="submit" value="UPDATE PROFILE" name="update_profile" class="btn">
                <a href="home.php" class="delete-btn-up">BACK</a>
            </form>
        </section>
        
        <!-- Custom JavaScript File -->
        <script src="js/script.js"></script>
    </body>
</html>
