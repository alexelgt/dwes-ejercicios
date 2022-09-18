<?php

namespace App;

use \PDO as PDO;

class Usuario {
    const USUARIO_EXISTE = -3;
    const REGISTRO_CORRECTO = 1;
    const REGISTRO_INCORRECTO = -1;
    
    
    private int $id;
    private string $nombre;
    private string $password;
    
    public function __construct(int $id=null, string $nombre=null, string $password=null) {
        if(!is_null($id)) {
            $this->id = $id;
        }

        if(!is_null($nombre)) {
            $this->nombre = $nombre;
        }

        if(!is_null($password)) {
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

    public function addUsuario(PDO $bd): bool {
        $sql = "INSERT INTO usuario (nombre, password) values (:nombre, :password)";
        $statement = $bd->prepare($sql);
        $result = $statement->execute([":nombre" => $this->nombre, ":password" => $this->password]);
        if ($result) {
            $id = (int) $bd->lastInsertId();
        }
        
        return $result;
    }
    
    public static function obtenerUsuarioPorLogin(PDO $bd, string $nombre, string $clave): ?Usuario {
        $sql = "SELECT * FROM usuario WHERE nombre = :nombre AND password = :password";
        $statement = $bd->prepare($sql);
        $statement->execute([":nombre" => $nombre, ":password" => $clave]);
        $statement->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        $variable = ($statement->fetch()) ?: null;
        return $variable;
    }
    
    public function existeUsuario(PDO $bd): bool {
        $sql = "SELECT nombre FROM usuario WHERE nombre = :nombre";
        $statement = $bd->prepare($sql);
        $statement->execute([":nombre" => $this->nombre]);
        $statement->setFetchMode(PDO::FETCH_OBJ);
        $usuario_name = ($statement->fetch()) ?: null;

        if (is_null($usuario_name)) {
            return false;
        } else {
            return true;
        }
    }
    
    public function registrarUsuario(PDO $bd): int {
        $existe_usuario = $this->existeUsuario($bd);
        
        if ($existe_usuario) {
            return self::USUARIO_EXISTE;
        }
        
        $resultado_registro = $this->addUsuario($bd);
        
        return $resultado_registro ? self::REGISTRO_CORRECTO : self::REGISTRO_INCORRECTO;
    }
}