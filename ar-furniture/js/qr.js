// Dynamic QR Code loader using public API fallback or client rendering
document.addEventListener('DOMContentLoaded', () => {
    const qrContainer = document.getElementById('qrcode');
    if (qrContainer) {
        const currentUrl = window.encodeURIComponent(window.location.href);
        qrContainer.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${currentUrl}" alt="Scan QR to open on mobile" class="img-fluid rounded shadow-sm">`;
    }
});