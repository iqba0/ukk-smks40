// Function to handle form submission
document.querySelector("form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission
    // You can add form validation or other actions here
});

// Function to toggle password visibility
function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}

// Example of other JavaScript functions
function myFunction() {
    // Perform some actions here
}
