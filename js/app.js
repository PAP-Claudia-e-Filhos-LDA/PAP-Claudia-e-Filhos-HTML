// Adicione este código no seu script app.js
document.addEventListener('DOMContentLoaded', function () {
    // Certifique-se de que o DOM foi completamente carregado
    if (typeof showNotification === 'function' && typeof notificationMessage !== 'undefined') {
        showNotification(notificationMessage);
    }
});

// Restante do seu script app.js...
