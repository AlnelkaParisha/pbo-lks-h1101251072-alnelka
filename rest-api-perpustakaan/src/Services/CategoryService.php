<?php

namespace App\Services;

use App\Models\Category;
use App\Contracts\CategoryRepositoryInterface;
use InvalidArgumentException;
use RuntimeException;

class CategoryService
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list(): array
    {
        return array_map(
            fn(Category $category) => $category->toArray(),
            $this->repository->findAll()
        );
    }

    public function find(int $id): array
    {
        $category = $this->repository->findById($id);

        if ($category === null) {
            throw new RuntimeException(
                "Kategori dengan ID {$id} tidak ditemukan"
            );
        }

        return $category->toArray();
    }

    public function create(array $data): array
    {
        $this->validate($data);

        $category = new Category(
            0,
            trim($data['nama']),
            isset($data['deskripsi']) && $data['deskripsi'] !== ''
                ? trim($data['deskripsi'])
                : null
        );

        $id = $this->repository->create($category);

        if (!$id) {
            throw new RuntimeException('Gagal membuat kategori');
        }

        return $this->find($id);
    }

    public function update(int $id, array $data): array
    {
        $this->find($id);
        $this->validate($data);

        $category = new Category(
            $id,
            trim($data['nama']),
            isset($data['deskripsi']) && $data['deskripsi'] !== ''
                ? trim($data['deskripsi'])
                : null
        );

        if (!$this->repository->update($category)) {
            throw new RuntimeException('Gagal memperbarui kategori');
        }

        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $this->find($id);

        if (!$this->repository->delete($id)) {
            throw new RuntimeException('Gagal menghapus kategori');
        }

        return true;
    }

    private function validate(array $data): void
    {
        if (empty(trim($data['nama'] ?? ''))) {
            throw new InvalidArgumentException('Nama kategori wajib diisi');
        }
    }
}
