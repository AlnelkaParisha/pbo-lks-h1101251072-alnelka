<?php

// Konfigurasi CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Menangani preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Env;
use App\Core\Container;
use App\Core\Request;
use App\Core\Router;
use App\Database\Connection;

use App\Contracts\CategoryRepositoryInterface;

use App\Repositories\MySQLBookRepository;
use App\Repositories\MySQLCategoryRepository;

use App\Services\BookService;
use App\Services\CategoryService;

use App\Controllers\BookController;
use App\Controllers\CategoryController;

// Memuat konfigurasi environment
Env::load(dirname(__DIR__) . '/.env');

// Membuat Dependency Injection Container
$container = new Container();

// Database Connection
$container->bind(PDO::class, fn($c) => Connection::get());

// Repository Buku
$container->bind(MySQLBookRepository::class, function ($c) {
    return new MySQLBookRepository(
        $c->make(PDO::class)
    );
});

// Repository Kategori
$container->bind(CategoryRepositoryInterface::class, function ($c) {
    return new MySQLCategoryRepository(
        $c->make(PDO::class)
    );
});

// Service Buku
$container->bind(BookService::class, function ($c) {
    return new BookService(
        $c->make(MySQLBookRepository::class)
    );
});

// Service Kategori
$container->bind(CategoryService::class, function ($c) {
    return new CategoryService(
        $c->make(CategoryRepositoryInterface::class)
    );
});

// Controller Buku
$container->bind(BookController::class, function ($c) {
    return new BookController(
        $c->make(BookService::class)
    );
});

// Controller Kategori
$container->bind(CategoryController::class, function ($c) {
    return new CategoryController(
        $c->make(CategoryService::class)
    );
});

// Membuat objek Controller
$controller = $container->make(BookController::class);

$categoryController = $container->make(CategoryController::class);

// Membuat Router
$router = new Router();

// Route Buku
$router->get('/api/books', fn() => $controller->index());
$router->get('/api/books/{id}', fn(int $id) => $controller->show($id));
$router->post('/api/books', fn() => $controller->store());
$router->put('/api/books/{id}', fn(int $id) => $controller->update($id));
$router->delete('/api/books/{id}', fn(int $id) => $controller->destroy($id));

// Route Kategori
$router->get('/api/categories', fn() => $categoryController->index());
$router->get('/api/categories/{id}', fn(int $id) => $categoryController->show($id));
$router->post('/api/categories', fn() => $categoryController->store());
$router->put('/api/categories/{id}', fn(int $id) => $categoryController->update($id));
$router->delete('/api/categories/{id}', fn(int $id) => $categoryController->destroy($id));

// Menjalankan Router
$router->dispatch(new Request());
