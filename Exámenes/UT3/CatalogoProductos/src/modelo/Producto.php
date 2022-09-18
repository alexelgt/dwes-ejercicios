<?php

namespace App;

use \PDO as PDO;

class Producto {
    private int $id;
    private string $nombre;
    private float $precio;
    private int $id_categoria;
    
    public function __construct(int $id=null, string $nombre=null, float $precio=null, int $id_categoria=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        
        if (!is_null($precio)) {
            $this->precio = $precio;
        }
        
        if (!is_null($id_categoria)) {
            $this->id_categoria = $id_categoria;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getPrecio(): float {
        return $this->precio;
    }

    public function getId_categoria(): int {
        return $this->id_categoria;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setPrecio(float $precio): void {
        $this->precio = $precio;
    }

    public function setId_categoria(int $id_categoria): void {
        $this->id_categoria = $id_categoria;
    }

    public static function obtenerPorCategoria(PDO $bd, int $id_categoria): ?array {
        $sql = "SELECT * FROM productos WHERE id_categoria = :id_categoria ORDER BY id";
        $statement = $bd->prepare($sql);

        $statement->execute([":id_categoria" => $id_categoria]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $elementos = $statement->fetchAll();

        return $elementos;
    }
    
    public static function obtenerPorId(PDO $bd, int $id): ?Producto {
        $sql = "SELECT * FROM productos WHERE id = :id";
        $statement = $bd->prepare($sql);

        $statement->execute([":id" => $id]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Producto::class);
        $elemento = ($statement->fetch()) ?: null;

        return $elemento;
    }
    
    public function add(PDO $bd) {
        $sql = "INSERT INTO productos (nombre, precio, id_categoria) values (:nombre, :precio, :id_categoria)";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":nombre" => $this->nombre, ":precio" => $this->precio, ":id_categoria" => $this->id_categoria]);

        if ($result) {
            $this->id = (int) $bd->lastInsertId();
        }

        return $result;
    }
    
    public function update(PDO $bd) {
        $sql = "UPDATE productos SET nombre = :nombre, precio = :precio, id_categoria = :id_categoria WHERE id = :id";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":id" => $this->id, ":nombre" => $this->nombre, ":precio" => $this->precio, ":id_categoria" => $this->id_categoria]);

        return $result;
    }
}

