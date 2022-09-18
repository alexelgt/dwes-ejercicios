<?php

namespace App;

use \PDO as PDO;

class Usuario {
    private int $id;
    private string $nombre;
    private string $password;

    public function __construct(int $id = null, string $nombre = null, string $password = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }

        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }

        if (!is_null($password)) {
            $this->password = $password;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public static function obtenerPorLogin(PDO $bd, string $nombre, string $password): ?Usuario {
        $sql = "SELECT * FROM usuarios WHERE nombre = :nombre AND password = :password";
        $statement = $bd->prepare($sql);

        $statement->execute([":nombre" => $nombre, ":password" => $password]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        $usuario = ($statement->fetch()) ?: null;

        return $usuario;
    }
}

