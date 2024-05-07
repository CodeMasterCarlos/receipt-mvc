const flasher = document.getElementById('flasherMessage');

if (flasher !== null) {
    initFlasherMessage();
}

function initFlasherMessage() {
    console.log(flasher)
    const message = flasher.getAttribute('data-message');
    const status = flasher.getAttribute('data-status');
    const time = flasher.getAttribute('data-time');

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: time,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: status,
        title: message,
    });
}