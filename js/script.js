// Password Validation for Registration Form
// Adding event listener to validate password before form submission
document.getElementById("registerForm").addEventListener("submit", function(event) {
    let password = document.getElementById("password").value;
    let capitalLetterRegex = /[A-Z]/;  // Regex to check for uppercase letter
    let specialCharacterRegex = /[!@#$%^&*(),.?":{}|<>]/;  // Regex to check for special character

    // Check if password length is at least 8 characters
    if (password.length < 8) {
        alert("Password must be at least 8 characters long!");
        event.preventDefault();
    } 
    // Check if password contains at least one uppercase letter
    else if (!capitalLetterRegex.test(password)) {
        alert("Password must contain at least one uppercase letter!");
        event.preventDefault();
    } 
    // Check if password contains at least one special character
    else if (!specialCharacterRegex.test(password)) {
        alert("Password must contain at least one special character!");
        event.preventDefault();
    }
});

// Function to validate password and confirm password fields
function validatePassword() {
    let newPassword = document.getElementById("new_password").value;
    let confirmPassword = document.getElementById("confirm_password").value;
    let capitalLetterRegex = /[A-Z]/;  // Regex to check for uppercase letter
    let specialCharacterRegex = /[!@#$%^&*(),.?":{}|<>]/;  // Regex to check for special character

    // Check if password length is at least 8 characters
    if (newPassword.length < 8) {
        alert("Password must be at least 8 characters long.");
        return false; // Prevent form submission
    }

    // Check if password contains at least one uppercase letter
    if (!capitalLetterRegex.test(newPassword)) {
        alert("Password must contain at least one uppercase letter!");
        return false;
    }

    // Check if password contains at least one special character
    if (!specialCharacterRegex.test(newPassword)) {
        alert("Password must contain at least one special character!");
        return false;
    }

    // Check if password and confirm password match
    if (newPassword !== confirmPassword) {
        alert("New password and confirm password do not match.");
        return false; // Prevent form submission
    }

    return true; // Allow form submission if validation passes
}

// Function to validate password for reset password functionality
function validateResetPassword() {
    let passwordInput = document.querySelector("input[name='password']"); // Select password input field
    let password = passwordInput.value;
    
    // Check if password length is at least 8 characters
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        passwordInput.focus(); // Focus back on the password field
        return false;
    }
    return true;
}