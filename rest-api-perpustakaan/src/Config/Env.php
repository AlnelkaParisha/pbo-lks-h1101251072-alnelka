<?php

namespace App\Config;

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("File .env tidak ditemukan");
        }

        $variables = parse_ini_file($path, false, INI_SCANNER_RAW);

        if ($variables === false) {
            throw new \RuntimeException("Gagal membaca file .env");
        }

        foreach ($variables as $key => $value) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}