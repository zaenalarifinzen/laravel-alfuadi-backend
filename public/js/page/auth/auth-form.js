document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".toggle-password-icon");

    toggles.forEach(toggle => {
        const parent = toggle.closest(".form-group");
        const input = parent.querySelector("input[type='password'], input[type='text']");

        toggle.addEventListener("click", function () {
            const icon = this.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        })
    })
})
