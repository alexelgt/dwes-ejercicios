<?php

namespace App;

use \PDO as PDO;

class Usuario {
    const USUARIO_EXISTE = -2;
    const USUARIO_REGISTRADO = 1;
    const USUARIO_NO_REGISTRADO = -1;

    private int $id;
    private string $name;
    private string $password;


    public function __construct(int $id = null, string $name = null, string $password = null) {
        if(!is_null($id)) {
            $this->id = $id;
        }


        if(!is_null($name)) {
            $this->name = $name;
        }


        if(!is_null($password)) {
            $this->password = $password;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public static function obtenerPorLogin(PDO $bd, string $name, string $password): ?Usuario {
        $sql = "SELECT * FROM usuarios WHERE name = :name AND password = :password";
        $statement = $bd->prepare($sql);

        $statement->execute([":name" => $name, ":password" => $password]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        $usuario = ($statement->fetch()) ?: null;

        return $usuario;
    }

}
