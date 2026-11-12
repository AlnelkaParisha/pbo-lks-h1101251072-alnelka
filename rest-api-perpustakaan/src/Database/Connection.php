<?php

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;

class Connection
{
    private static ?PDO $instance = null;

    private function __construct()
    {
    }

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT');
            $database = getenv('DB_DATABASE');
            $username = getenv('DB_USERNAME');
            $password = getenv('DB_PASSWORD');

            $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                throw new RuntimeException(
                    "Koneksi database gagal: " . $e->getMessage()
                );
            }
        }

        return self::$instance;
    }
}