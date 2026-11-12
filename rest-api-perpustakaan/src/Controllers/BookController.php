<?php

namespace App\Controllers;

use App\Core\Response;
use App\Core\Request;
use App\Services\BookService;
use InvalidArgumentException;
use Throwable;

class BookController
{
    private BookService $service;

    public function __construct(BookService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        Response::json([
            'success' => true,
            'message' => 'Daftar buku',
            'data' => $this->service->list()
        ]);
    }

    public function show(int $id): void
    {
        try {
            $book = $this->service->find($id);

            Response::json([
                'success' => true,
                'message' => 'Detail buku',
                'data' => $book
            ]);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function store(): void
    {
        $data = (new Request())->getBody();

        try {
            $book = $this->service->create($data);

            Response::json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan',
                'data' => $book
            ], 201);
        } catch (InvalidArgumentException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(int $id): void
    {
        $data = (new Request())->getBody();

        try {
            $book = $this->service->update($id, $data);

            Response::json([
                'success' => true,
                'message' => 'Buku berhasil diperbarui',
                'data' => $book
            ]);
        } catch (InvalidArgumentException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function destroy(int $id): void
    {
        try {
            $this->service->delete($id);

            Response::json([
                'success' => true,
                'message' => 'Buku berhasil dihapus'
            ]);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
