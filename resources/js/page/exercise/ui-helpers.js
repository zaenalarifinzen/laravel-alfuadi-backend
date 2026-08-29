/* ==========================================================================
   UI Helpers — loading overlay, submit button state, dan dialog konfirmasi.
   Semua fungsi di sini stateless (tidak menyimpan state apa pun),
   jadi bisa dipanggil langsung tanpa perlu di-init.
   ========================================================================== */

export function showLoading() {
    $("#loading-overlay").css({
        visibility: "visible",
        opacity: "1",
    });
}

export function hideLoading() {
    $("#loading-overlay").css({
        visibility: "hidden",
        opacity: "0",
    });
}

export function changeSubmitButton(id, label, type) {
    const submitBtn = document.querySelector(`button[name="btn-submit"]`);
    if (!submitBtn) return;

    submitBtn.id = id;
    submitBtn.textContent = label;
    submitBtn.classList.remove(
        "btn-success",
        "btn-secondary",
        "btn-primary",
        "btn-danger",
    );
    submitBtn.classList.add(`btn-${type}`);
}

export function showEditConfirmation() {
    return swal({
        icon: "warning",
        title: "Perubahan belum disimpan",
        text: "Abaikan perubahan yang sudah ada?",
        buttons: {
            cancel: {
                text: "Kembali",
                visible: true,
            },
            confirm: {
                text: "Abaikan",
                visible: true,
                className: "btn-success",
            },
        },
    });
}

export function showExerciseUnavailableDialog() {
    swal({
        title: "Soal tidak tersedia",
        text: "Silakan coba soal lainnya.",
        icon: "error",
        buttons: {
            confirm: {
                text: "Tutup",
                visible: true,
            },
        },
    });
}