const forgotPasswordInput = document.getElementById("forgot_password");
const toggleForgotPassword = document.getElementById("toggleForgotPassword");
const forgotEyeIcon = document.getElementById("forgotEyeIcon");

if (forgotPasswordInput && toggleForgotPassword && forgotEyeIcon) {
    toggleForgotPassword.addEventListener("click", function () {
        const hidden = forgotPasswordInput.type === "password";
        forgotPasswordInput.type = hidden ? "text" : "password";
        forgotEyeIcon.classList.remove("fa-eye", "fa-eye-slash");
        forgotEyeIcon.classList.add(hidden ? "fa-eye" : "fa-eye-slash");
    });
}

const forgotSuccessModal = document.getElementById("forgotSuccessModal");
const forgotCloseModal = document.getElementById("forgotCloseModal");

if (forgotSuccessModal && forgotCloseModal) {
    forgotSuccessModal.style.display = "flex";
    forgotCloseModal.addEventListener("click", function () {
        forgotSuccessModal.style.display = "none";
        window.location.href = "Index.php";
    });
}

const forgotErrorModal = document.getElementById("forgotErrorModal");
const forgotErrorClose = document.getElementById("forgotErrorClose");

if (forgotErrorModal && forgotErrorClose) {
    forgotErrorModal.style.display = "flex";
    forgotErrorClose.addEventListener("click", function () {
        forgotErrorModal.style.display = "none";
    });
}
