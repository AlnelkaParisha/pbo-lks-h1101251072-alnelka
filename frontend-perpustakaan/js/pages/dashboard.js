// Halaman Dashboard
async function renderDashboard() {
    const app = document.getElementById('app');

    app.innerHTML = `
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                Dashboard
            </h2>
            <p class="text-gray-500 mt-1">
                Ringkasan data perpustakaan
            </p>
        </div>

        <div class="text-center py-10">
            <p class="text-gray-500">Memuat data dashboard...</p>
        </div>
    `;

    try {
        const [bookResponse, categoryResponse] = await Promise.all([
            BookAPI.getAll(),
            CategoryAPI.getAll()
        ]);

        const books = bookResponse.data || [];
        const categories = categoryResponse.data || [];

        const latestBooks = [...books]
            .sort((a, b) => b.id - a.id)
            .slice(0, 5);

        app.innerHTML = `
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">
                    Dashboard
                </h2>
                <p class="text-gray-500 mt-1">
                    Ringkasan data perpustakaan
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div class="bg-blue-600 text-white rounded-xl p-6 shadow">
                    <p class="text-blue-100">Total Buku</p>
                    <h3 class="text-4xl font-bold mt-2">
                        ${books.length}
                    </h3>
                </div>

                <div class="bg-orange-500 text-white rounded-xl p-6 shadow">
                    <p class="text-orange-100">Kategori</p>
                    <h3 class="text-4xl font-bold mt-2">
                        ${categories.length}
                    </h3>
                </div>

            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    5 Buku Terbaru
                </h3>

                ${
                    latestBooks.length > 0
                    ? `
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b text-gray-500 text-sm">
                                        <th class="py-3">Judul</th>
                                        <th class="py-3">Penulis</th>
                                        <th class="py-3">Tahun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${latestBooks.map(book => `
                                        <tr class="border-b">
                                            <td class="py-3 font-medium">
                                                ${escapeHtml(book.judul)}
                                            </td>
                                            <td class="py-3 text-gray-600">
                                                ${escapeHtml(book.penulis)}
                                            </td>
                                            <td class="py-3 text-gray-600">
                                                ${escapeHtml(book.tahun)}
                                            </td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `
                    : `
                        <p class="text-gray-500">
                            Belum ada data buku.
                        </p>
                    `
                }
            </div>
        `;

    } catch (error) {
        app.innerHTML = `
            <div class="bg-red-100 text-red-700 p-4 rounded-lg">
                Gagal memuat dashboard: ${escapeHtml(error.message)}
            </div>
        `;
    }
}