<?php

namespace App\Repositories;

use App\Contracts\CategoryRepositoryInterface;
use App\Models\Category;
use PDO;

class MySQLCategoryRepository implements CategoryRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM categories ORDER BY nama ASC"
        );

        $categories = [];

        while ($row = $stmt->fetch()) {
            $categories[] = new Category(
                (int) $row['id'],
                $row['nama'],
                $row['deskripsi']
            );
        }

        return $categories;
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM categories WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Category(
            (int) $row['id'],
            $row['nama'],
            $row['deskripsi']
        );
    }

    public function create(Category $category): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO categories (nama, deskripsi)
             VALUES (:nama, :deskripsi)"
        );

        $stmt->execute([
            'nama' => $category->getNama(),
            'deskripsi' => $category->getDeskripsi()
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Category $category): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE categories
             SET nama = :nama, deskripsi = :deskripsi
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $category->getId(),
            'nama' => $category->getNama(),
            'deskripsi' => $category->getDeskripsi()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM categories WHERE id = :id"
        );

        return $stmt->execute(['id' => $id]);
    }
}
