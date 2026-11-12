<?php namespace App\Contracts;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Book;

    public function create(Book $book): int;

    public function update(Book $book): bool;

    public function delete(int $id): bool;
}
