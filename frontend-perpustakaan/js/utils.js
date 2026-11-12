// Mengamankan teks sebelum ditampilkan ke HTML
function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, function (char) {
        const entities = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return entities[char];
    });
}

// Menampilkan notifikasi
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');

    if (!toast) return;

    toast.textContent = message;
    toast.classList.remove('hidden');

    toast.style.backgroundColor =
        type === 'error' ? '#dc2626' : '#16a34a';

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}