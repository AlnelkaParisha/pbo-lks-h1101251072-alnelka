<?php namespace App\Services;

use App\Models\Book;
use App\Contracts\BookRepositoryInterface;
use InvalidArgumentException;
use RuntimeException;

class BookService
{
    private BookRepositoryInterface $repository;

    public function __construct(BookRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list(): array
    {
        return array_map(
            fn(Book $book) => $book->toArray(),
            $this->repository->findAll()
        );
    }

    public function find(int $id): array
    {
        $book = $this->repository->findById($id);

        if ($book === null) {
            throw new RuntimeException(
                "Buku dengan ID {$id} tidak ditemukan"
            );
        }

        return $book->toArray();
    }

    public function create(array $data): array
    {
        $this->validate($data);

        $book = new Book(
            0,
            trim($data['judul']),
            trim($data['penulis']),
            (int) $data['tahun'],
            (int) $data['stok']
        );

        $id = $this->repository->create($book);

        if (!$id) {
            throw new RuntimeException('Gagal membuat buku');
        }

        return $this->find($id);
    }

    public function update(int $id, array $data): array
    {
        $this->find($id);

        $this->validate($data);

        $book = new Book(
            $id,
            trim($data['judul']),
            trim($data['penulis']),
            (int) $data['tahun'],
            (int) $data['stok']
        );

        if (!$this->repository->update($book)) {
            throw new RuntimeException('Gagal memperbarui buku');
        }

        return $this->find($id);
    }

    public function delete(int $id): bool
    {
        $this->find($id);

        if (!$this->repository->delete($id)) {
            throw new RuntimeException('Gagal menghapus buku');
        }

        return true;
    }

    private function validate(array $data): void
    {
        if (empty(trim($data['judul'] ?? ''))) {
            throw new InvalidArgumentException('Judul wajib diisi');
        }

        if (empty(trim($data['penulis'] ?? ''))) {
            throw new InvalidArgumentException('Penulis wajib diisi');
        }

        if (!isset($data['tahun']) || !is_numeric($data['tahun'])) {
            throw new InvalidArgumentException('Tahun wajib diisi');
        }

        if (!isset($data['stok']) || !is_numeric($data['stok'])) {
            throw new InvalidArgumentException('Stok wajib diisi');
        }
    }
}
