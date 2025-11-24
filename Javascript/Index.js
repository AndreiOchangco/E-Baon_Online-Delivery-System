const passInput = document.getElementById("login_password");
const toggleEye = document.getElementById("toggleEye");
const eyeIcon = document.getElementById("eyeIcon");

if (passInput && toggleEye && eyeIcon) {
    toggleEye.addEventListener("click", function () {
        const isHidden = passInput.type === "password";
        passInput.type = isHidden ? "text" : "password";
        eyeIcon.classList.remove("fa-eye", "fa-eye-slash");
        eyeIcon.classList.add(isHidden ? "fa-eye" : "fa-eye-slash");
    });
}

const modal = document.getElementById("loginModal");
const modalText = document.getElementById("loginModalText");
const modalBtn = document.getElementById("loginModalBtn");

if (modal && modalText && modalBtn) {
    if (typeof phpLoginError === "string" && phpLoginError.trim() !== "") {
        modalText.textContent = phpLoginError;
        modal.style.display = "flex";
    }

    modalBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });
}

const bottomText = document.getElementById("bottomText");
const btLink = document.getElementById("btLink");

if (typeof phpLoginError === "string" && phpLoginError.trim() !== "") {
    if (bottomText && btLink) {
        bottomText.textContent = "";
        btLink.textContent = "Forgot Password?";
        btLink.href = "Forgot.php";
        bottomText.appendChild(btLink);
    }
}
