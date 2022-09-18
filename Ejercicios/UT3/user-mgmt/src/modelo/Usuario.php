<?php
namespace App;

use \PDO as PDO;

class Usuario {
    const REGISTRO_CORRECTO = 1;
    const REGISTRO_INCORRECTO = -1;
    const DATOS_MODIFICADOS = 2;
    const DATOS_NO_MODIFICADOS = -2;
    const USUARIO_EXISTENTE = -3;
    
    private int $id;
    private string $name;
    private string $password;
    private string $email;
    private int $painter_fk;
    
    public function __construct(int $id=null, string $name=null, string $password=null, string $email=null, int $painter_fk=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        if (!is_null($name)) {
            $this->name = $name;
        }
        
        if (!is_null($password)) {
            $this->password = $password;
        }
        
        if (!is_null($email)) {
            $this->email = $email;
        }
        
        if (!is_null($painter_fk)) {
            $this->painter_fk = $painter_fk;
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

    public function getEmail(): string {
        return $this->email;
    }

    public function getPainter_fk(): int {
        return $this->painter_fk;
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

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPainter_fk(int $painter_fk): void {
        $this->painter_fk = $painter_fk;
    }
    
    public static function getUsuarioByLoginData(PDO $bd, string $name, string $password): ?Usuario {
        //$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = "SELECT * FROM users WHERE name = :name AND password = :password";
        $sth = $bd->prepare($sql);
        $sth->execute([":name" => $name, ":password" => $password]);
        $sth->setFetchMode(PDO::FETCH_CLASS, Usuario::class);
        $usuario = ($sth->fetch()) ?: null;
        return $usuario;
    }
    
    public function persiste(PDO $bd) : bool {
        if (isset($this->id)) {
            $sql = "UPDATE users SET id = :id, name = :name, password = :password, email = :email, painter_fk = :painter_fk WHERE id = :id";
            $sth = $bd->prepare($sql);
            $result = $sth->execute([":id" => $this->id, ":name" => $this->name, ":password" => $this->password, ":email" => $this->email, ":painter_fk" => $this->painter_fk]);
        } else {
            $sql = "INSERT INTO users (name, password, email, painter_fk) values (:name, :password, :email, :painter_fk)";
            $sth = $bd->prepare($sql);
            $result = $sth->execute([":name" => $this->name, ":password" => $this->password, ":email" => $this->email, ":painter_fk" => $this->painter_fk]);
            if ($result) {
                $this->id = (int) $bd->lastInsertId();
            }
        }
        return ($result);
    }
    
    public function existeUsuario(PDO $bd) : int {
        $sql = "SELECT name FROM users WHERE name = :name";
        $sth = $bd->prepare($sql);
        $sth->execute([":name" => $this->name]);
        $sth->setFetchMode(PDO::FETCH_OBJ);
        $usuario_name = ($sth->fetch()) ?: null;
        
        if (is_null($usuario_name)) {
            return false;
        }
        else {
            return true;
        }
    }
    
    public function registrarUsuario(PDO $bd) : int {
        //$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        
        $existe_usuario = $this->existeUsuario($bd);
        
        if ($existe_usuario) {
            return self::USUARIO_EXISTENTE;
        }
        else {
            $result = $this->persiste($bd);
            
            return $result ? self::REGISTRO_CORRECTO : self::REGISTRO_INCORRECTO;
        }
    }
    
    public function modificarUsuario(PDO $bd, string $name_old) : int {
        //$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        
        $existe_usuario = $this->existeUsuario($bd);
        
        if ($existe_usuario && $this->name !== $name_old) {
            return self::USUARIO_EXISTENTE;
        }
        else {
            $result = $this->persiste($bd);
            
            return $result ? self::DATOS_MODIFICADOS : self::DATOS_NO_MODIFICADOS;
        }
    }
    
    public function elimina(PDO $bd) : bool {
        $sql = "DELETE FROM users WHERE id = :id";
        $sth = $bd->prepare($sql);
        $result = $sth->execute([":id" => $this->id]);
        return $result;
    }
}