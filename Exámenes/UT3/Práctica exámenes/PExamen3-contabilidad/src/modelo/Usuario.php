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
    
    public function __construct(int $id=null, string $name=null, string $password=null) {
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
    
    private function existe(PDO $bd): bool {
        $sql = "SELECT name FROM usuarios WHERE name = :name";
        $statement = $bd->prepare($sql);

        $statement->execute([":name" => $this->name]);

        $statement->setFetchMode(PDO::FETCH_OBJ);
        $usuario_name = ($statement->fetch()) ?: null;

        if (is_null($usuario_name)) {
            return false;
        } else {
            return true;
        }
    }
    
    private function add(PDO $bd): bool {
        $sql = "INSERT INTO usuarios (name, password) values (:name, :password)";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":name" => $this->name, ":password" => $this->password]);

        if ($result) {
            $this->id = (int) $bd->lastInsertId();
        }

        return $result;
    }
    
    public function registrar(PDO $bd): int {
        $existe = $this->existe($bd);
        
        if ($existe) {
            return self::USUARIO_EXISTE;
        }
        
        $resultado_add = $this->add($bd);
        
        if ($resultado_add) {
            return self::USUARIO_REGISTRADO;
        }
        else {
            return self::USUARIO_NO_REGISTRADO;
        }
    }
}