// Halaman Buku
async function renderBooks() {
    const app = document.getElementById('app');

    app.innerHTML = `
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Data Buku</h2>
                <p class="text-gray-500 mt-1">Kelola data buku perpustakaan</p>
            </div>

            <button onclick="openBookModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                + Tambah Buku
            </button>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-gray-500 text-center py-8">Memuat data buku...</p>
        </div>
    `;

    try {
        const response = await BookAPI.getAll();
        const books = response.data || [];

        app.innerHTML = `
            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Data Buku</h2>
                    <p class="text-gray-500 mt-1">Kelola data buku perpustakaan</p>
                </div>

                <button onclick="openBookModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    + Tambah Buku
                </button>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                            <tr>
                                <th class="px-5 py-4">ID</th>
                                <th class="px-5 py-4">Judul</th>
                                <th class="px-5 py-4">Penulis</th>
                                <th class="px-5 py-4">Tahun</th>
                                <th class="px-5 py-4">Stok</th>
                                <th class="px-5 py-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            ${
                                books.length > 0
                                ? books.map(book => `
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-5 py-4">${book.id}</td>
                                        <td class="px-5 py-4 font-medium">
                                            ${escapeHtml(book.judul)}
                                        </td>
                                        <td class="px-5 py-4">
                                            ${escapeHtml(book.penulis)}
                                        </td>
                                        <td class="px-5 py-4">${escapeHtml(book.tahun)}</td>
                                        <td class="px-5 py-4">${escapeHtml(book.stok)}</td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <button
                                                onclick="openBookModal(${book.id})"
                                                class="text-blue-600 hover:underline mr-3">
                                                Edit
                                            </button>

                                            <button
                                                onclick="deleteBook(${book.id})"
                                                class="text-red-600 hover:underline">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                `).join('')
                                : `
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-gray-500">
                                            Belum ada data buku.
                                        </td>
                                    </tr>
                                `
                            }
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="book-modal"></div>
        `;

    } catch (error) {
        app.innerHTML = `
            <div class="bg-red-100 text-red-700 p-4 rounded-lg">
                Gagal memuat data buku: ${escapeHtml(error.message)}
            </div>
        `;
    }
}

// Membuka modal tambah atau edit buku
async function openBookModal(id = null) {
    let book = {
        judul: '',
        penulis: '',
        tahun: '',
        stok: ''
    };

    if (id !== null) {
        try {
            const response = await BookAPI.getById(id);
            book = response.data;
        } catch (error) {
            showToast(error.message, 'error');
            return;
        }
    }

    const modal = document.getElementById('book-modal');

    modal.innerHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold">
                        ${id ? 'Edit Buku' : 'Tambah Buku'}
                    </h3>

                    <button onclick="closeBookModal()"
                        class="text-gray-500 text-2xl">
                        &times;
                    </button>
                </div>

                <form onsubmit="saveBook(event, ${id})">
                    <div class="mb-4">
                        <label class="block mb-2 font-medium">Judul Buku</label>
                        <input type="text" name="judul"
                            value="${escapeHtml(book.judul)}"
                            class="w-full border rounded-lg p-3"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-medium">Penulis</label>
                        <input type="text" name="penulis"
                            value="${escapeHtml(book.penulis)}"
                            class="w-full border rounded-lg p-3"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-medium">Tahun</label>
                        <input type="number" name="tahun"
                            value="${escapeHtml(book.tahun)}"
                            class="w-full border rounded-lg p-3"
                            required>
                    </div>

                    <div class="mb-6">
                        <label class="block mb-2 font-medium">Stok</label>
                        <input type="number" name="stok"
                            value="${escapeHtml(book.stok)}"
                            class="w-full border rounded-lg p-3"
                            min="0" required>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeBookModal()"
                            class="bg-gray-200 px-4 py-2 rounded-lg">
                            Batal
                        </button>

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
}

// Menutup modal
function closeBookModal() {
    document.getElementById('book-modal').innerHTML = '';
}

// Menyimpan buku baru atau perubahan buku
async function saveBook(event, id) {
    event.preventDefault();

    const form = event.target;

    const data = {
        judul: form.judul.value.trim(),
        penulis: form.penulis.value.trim(),
        tahun: Number(form.tahun.value),
        stok: Number(form.stok.value)
    };

    try {
        if (id) {
            await BookAPI.update(id, data);
            showToast('Buku berhasil diperbarui');
        } else {
            await BookAPI.create(data);
            showToast('Buku berhasil ditambahkan');
        }

        renderBooks();

    } catch (error) {
        showToast(error.message, 'error');
    }
}

// Menghapus buku
async function deleteBook(id) {
    const confirmed = confirm('Apakah kamu yakin ingin menghapus buku ini?');

    if (!confirmed) return;

    try {
        await BookAPI.delete(id);
        showToast('Buku berhasil dihapus');
        renderBooks();

    } catch (error) {
        showToast(error.message, 'error');
    }
}