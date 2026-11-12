// Router aplikasi menggunakan hash URL

function navigateTo(page) {
    window.location.hash = page;
}

// Menampilkan halaman berdasarkan hash
function handleRoute() {
    const page = window.location.hash.replace('#', '') || 'dashboard';

    // Mengatur menu aktif
    document.querySelectorAll('.menu-link').forEach(link => {
        link.classList.remove('text-blue-600', 'border-blue-600');
    });

    const activeLink = document.querySelector(
        `.menu-link[href="#${page}"]`
    );

    if (activeLink) {
        activeLink.classList.add('text-blue-600', 'border-blue-600');
    }

    // Menampilkan halaman
    switch (page) {
        case 'dashboard':
            renderDashboard();
            break;

        case 'books':
            renderBooks();
            break;

        case 'categories':
            renderCategories();
            break;

        default:
            document.getElementById('app').innerHTML = `
                <div class="text-center py-16">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Halaman tidak ditemukan
                    </h2>
                </div>
            `;
    }
}

// Menjalankan router ketika hash berubah
window.addEventListener('hashchange', handleRoute);

// Menjalankan aplikasi ketika halaman selesai dimuat
document.addEventListener('DOMContentLoaded', () => {
    handleRoute();
});
