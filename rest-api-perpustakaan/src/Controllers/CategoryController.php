<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Services\CategoryService;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class CategoryController
{
    private CategoryService $service;

    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(): void
    {
        try {
            Response::json([
                'success' => true,
                'data' => $this->service->list()
            ]);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): void
    {
        try {
            Response::json([
                'success' => true,
                'data' => $this->service->find($id)
            ]);
        } catch (RuntimeException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function store(): void
    {
        try {
            $request = new Request();

            $category = $this->service->create($request->getBody());

            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category
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
        try {
            $request = new Request();

            $category = $this->service->update(
                $id,
                $request->getBody()
            );

            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $category
            ]);
        } catch (InvalidArgumentException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (RuntimeException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id): void
    {
        try {
            $this->service->delete($id);

            Response::json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);
        } catch (RuntimeException $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (Throwable $e) {
            Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
