document.addEventListener("DOMContentLoaded", function () {
    var passwordInput = document.querySelector(".auth-password-input");
    var toggleButton = document.querySelector(".auth-password-toggle");
    var rememberCheckbox = document.querySelector("#rememberMe");
    var emailInput = document.querySelector("#email");

    if (toggleButton && passwordInput) {
        toggleButton.addEventListener("click", function () {
            var type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            toggleButton.textContent = type === "password" ? "Show" : "Hide";
        });
    }

    if (emailInput && rememberCheckbox) {
        var savedEmail = localStorage.getItem("eb_register_email");
        if (savedEmail) {
            emailInput.value = savedEmail;
            rememberCheckbox.checked = true;
        }

        rememberCheckbox.addEventListener("change", function () {
            if (rememberCheckbox.checked) {
                localStorage.setItem("eb_register_email", emailInput.value);
            } else {
                localStorage.removeItem("eb_register_email");
            }
        });

        emailInput.addEventListener("input", function () {
            if (rememberCheckbox.checked) {
                localStorage.setItem("eb_register_email", emailInput.value);
            }
        });
    }
});
