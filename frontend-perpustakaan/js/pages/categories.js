
// Halaman Kategori
async function renderCategories() {
    const app = document.getElementById('app');

    app.innerHTML = `
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                Data Kategori
            </h2>
            <p class="text-gray-500 mt-1">
                Kelola kategori buku perpustakaan
            </p>
        </div>

        <div class="text-center py-10">
            <p class="text-gray-500">Memuat data kategori...</p>
        </div>
    `;

    try {
        const response = await CategoryAPI.getAll();
        const categories = response.data || [];

        app.innerHTML = `
            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        Data Kategori
                    </h2>
                    <p class="text-gray-500 mt-1">
                        Kelola kategori buku perpustakaan
                    </p>
                </div>

                <button onclick="openCategoryModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    + Tambah Kategori
                </button>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-600 text-sm">
                            <tr>
                                <th class="px-5 py-4">ID</th>
                                <th class="px-5 py-4">Nama Kategori</th>
                                <th class="px-5 py-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            ${
                                categories.length > 0
                                ? categories.map(category => `
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-5 py-4">
                                            ${category.id}
                                        </td>

                                        <td class="px-5 py-4 font-medium">
                                            ${escapeHtml(category.nama)}
                                        </td>

                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <button
                                                onclick="openCategoryModal(${category.id})"
                                                class="text-blue-600 hover:underline mr-3">
                                                Edit
                                            </button>

                                            <button
                                                onclick="deleteCategory(${category.id})"
                                                class="text-red-600 hover:underline">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                `).join('')
                                : `
                                    <tr>
                                        <td colspan="3"
                                            class="text-center py-8 text-gray-500">
                                            Belum ada data kategori.
                                        </td>
                                    </tr>
                                `
                            }
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="category-modal"></div>
        `;

    } catch (error) {
        app.innerHTML = `
            <div class="bg-red-100 text-red-700 p-4 rounded-lg">
                Gagal memuat kategori:
                ${escapeHtml(error.message)}
            </div>
        `;
    }
}

// Membuka modal tambah atau edit kategori
async function openCategoryModal(id = null) {
    let category = {
        nama: ''
    };

    if (id !== null) {
        try {
            const response = await CategoryAPI.getById(id);
            category = response.data;
        } catch (error) {
            showToast(error.message, 'error');
            return;
        }
    }

    const modal = document.getElementById('category-modal');

    modal.innerHTML = `
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6">

                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold">
                        ${id ? 'Edit Kategori' : 'Tambah Kategori'}
                    </h3>

                    <button onclick="closeCategoryModal()"
                        class="text-gray-500 text-2xl">
                        &times;
                    </button>
                </div>

                <form onsubmit="saveCategory(event, ${id})">
                    <div class="mb-6">
                        <label class="block mb-2 font-medium">
                            Nama Kategori
                        </label>

                        <input
                            type="text"
                            name="nama"
                            value="${escapeHtml(category.nama)}"
                            placeholder="Masukkan nama kategori"
                            class="w-full border rounded-lg p-3"
                            required>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            onclick="closeCategoryModal()"
                            class="bg-gray-200 px-4 py-2 rounded-lg">
                            Batal
                        </button>

                        <button
                            type="submit"
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
function closeCategoryModal() {
    document.getElementById('category-modal').innerHTML = '';
}

// Menyimpan kategori baru atau perubahan kategori
async function saveCategory(event, id) {
    event.preventDefault();

    const form = event.target;

    const data = {
        nama: form.nama.value.trim()
    };

    try {
        if (id) {
            await CategoryAPI.update(id, data);
            showToast('Kategori berhasil diperbarui');
        } else {
            await CategoryAPI.create(data);
            showToast('Kategori berhasil ditambahkan');
        }

        renderCategories();

    } catch (error) {
        showToast(error.message, 'error');
    }
}

// Menghapus kategori
async function deleteCategory(id) {
    const confirmed = confirm(
        'Apakah kamu yakin ingin menghapus kategori ini?'
    );

    if (!confirmed) return;

    try {
        await CategoryAPI.delete(id);
        showToast('Kategori berhasil dihapus');
        renderCategories();

    } catch (error) {
        showToast(error.message, 'error');
    }
}
