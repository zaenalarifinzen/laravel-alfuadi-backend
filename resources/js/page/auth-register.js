"use strict";

$(".pwstrength").pwstrength();

document.addEventListener("DOMContentLoaded", function () {
    const registerForm = document.querySelector("form[action$='/register']");

    if (!registerForm) {
        return;
    }

    registerForm.addEventListener("submit", function (event) {
        if (registerForm.dataset.submitting === "true") {
            event.preventDefault();
            return;
        }

        registerForm.dataset.submitting = "true";

        registerForm.querySelectorAll("button[type='submit']").forEach((button) => {
            button.disabled = true;
            button.dataset.originalText = button.textContent.trim();
            button.textContent = "Memproses...";
        });
    });
});
