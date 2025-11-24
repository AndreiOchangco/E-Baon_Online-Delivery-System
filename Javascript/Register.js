const regPasswordInput = document.getElementById("reg_password");
const toggleRegPassword = document.getElementById("toggleRegPassword");
const regEyeIcon = document.getElementById("regEyeIcon");

if (regPasswordInput && toggleRegPassword && regEyeIcon) {
    toggleRegPassword.addEventListener("click", () => {
        const hidden = regPasswordInput.type === "password";
        regPasswordInput.type = hidden ? "text" : "password";
        regEyeIcon.classList.remove("fa-eye", "fa-eye-slash");
        regEyeIcon.classList.add(hidden ? "fa-eye" : "fa-eye-slash");
    });
}

const successModal = document.getElementById("successModal");
const closeModal = document.getElementById("closeModal");

if (successModal && closeModal) {
    successModal.style.display = "flex";
    closeModal.addEventListener("click", () => {
        successModal.style.display = "none";
    });
}

const errorModal = document.getElementById("errorModal");
const closeErrorModal = document.getElementById("closeErrorModal");

if (errorModal && closeErrorModal) {
    errorModal.style.display = "flex";
    closeErrorModal.addEventListener("click", () => {
        errorModal.style.display = "none";
    });
}

const roleSelect = document.getElementById("roleSelect");
const roleArrow = document.getElementById("roleArrow");

if (roleSelect && roleArrow) {
    let arrowUp = false;

    roleSelect.addEventListener("click", e => {
        e.stopPropagation();
        arrowUp = !arrowUp;
        roleArrow.classList.toggle("up", arrowUp);
    });

    roleSelect.addEventListener("blur", () => {
        arrowUp = false;
        roleArrow.classList.remove("up");
    });

    document.addEventListener("click", () => {
        arrowUp = false;
        roleArrow.classList.remove("up");
    });
}
