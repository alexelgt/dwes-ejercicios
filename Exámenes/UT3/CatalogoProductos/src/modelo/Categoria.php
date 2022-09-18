<?php

namespace App;

use \PDO as PDO;

class Categoria {
    private int $id;
    private string $nombre;
    
    public function __construct(int $id=null, string $nombre=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }

        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public static function obtenerTodas(PDO $bd): ?array {
        $sql = "SELECT * FROM categorias ORDER BY id";
        $statement = $bd->prepare($sql);

        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, Categoria::class);
        $elementos = $statement->fetchAll();

        return $elementos;
    }
    
    public static function obtenerPorId(PDO $bd, int $id): ?Categoria {
        $sql = "SELECT * FROM categorias WHERE id = :id";
        $statement = $bd->prepare($sql);

        $statement->execute([":id" => $id]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Categoria::class);
        $elemento = ($statement->fetch()) ?: null;

        return $elemento;
    }
}

