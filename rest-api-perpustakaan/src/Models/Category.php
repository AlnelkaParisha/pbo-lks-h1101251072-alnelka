<?php

namespace App\Models;

class Category
{
    private int $id;
    private string $nama;
    private ?string $deskripsi;

    public function __construct(
        int $id,
        string $nama,
        ?string $deskripsi
    ) {
        $this->id = $id;
        $this->nama = $nama;
        $this->deskripsi = $deskripsi;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getDeskripsi(): ?string
    {
        return $this->deskripsi;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
        ];
    }
}