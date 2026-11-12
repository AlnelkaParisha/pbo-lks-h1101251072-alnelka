<?php namespace App\Repositories;

use App\Contracts\BookRepositoryInterface;
use App\Models\Book;
use PDO;

class MySQLBookRepository implements BookRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM books ORDER BY judul ASC"
        );

        $books = [];

        while ($row = $stmt->fetch()) {
            $books[] = new Book(
                (int) $row['id'],
                $row['judul'],
                $row['penulis'],
                (int) $row['tahun'],
                (int) $row['stok']
            );
        }

        return $books;
    }

    public function findById(int $id): ?Book
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM books WHERE id = :id"
        );

        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new Book(
            (int) $row['id'],
            $row['judul'],
            $row['penulis'],
            (int) $row['tahun'],
            (int) $row['stok']
        );
    }

    public function create(Book $book): int
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO books (judul, penulis, tahun, stok)
             VALUES (:judul, :penulis, :tahun, :stok)"
        );

        $stmt->execute([
            'judul' => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun' => $book->getTahun(),
            'stok' => $book->getStok()
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(Book $book): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE books
             SET judul = :judul,
                 penulis = :penulis,
                 tahun = :tahun,
                 stok = :stok
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $book->getId(),
            'judul' => $book->getJudul(),
            'penulis' => $book->getPenulis(),
            'tahun' => $book->getTahun(),
            'stok' => $book->getStok()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM books WHERE id = :id"
        );

        return $stmt->execute(['id' => $id]);
    }
}
