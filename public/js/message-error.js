const messages = document.querySelectorAll('.message-error');

setTimeout(function() {
    messages.forEach(function(message) {
        message.style.display = "none";
    });
}, 7000);