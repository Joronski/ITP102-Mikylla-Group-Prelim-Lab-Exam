// reCAPTCHA Verification
// This script ensures that users complete the reCAPTCHA verification before submitting the login form.

document.getElementById("loginForm").addEventListener("submit", function (event) {
    // Get the reCAPTCHA response
    var response = grecaptcha.getResponse();
    
    // Check if the reCAPTCHA is completed
    if (response.length === 0) {
        // Alert the user if reCAPTCHA is not completed
        alert("Please complete the reCAPTCHA verification.");
        
        // Prevent form submission
        event.preventDefault();
    }
});