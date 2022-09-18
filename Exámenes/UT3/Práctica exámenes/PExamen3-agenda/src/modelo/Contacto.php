<?php

namespace App;

use \PDO as PDO;
use \DOMDocument as DOMDocument;

class Contacto {
    private int $id;
    private string $nombre;
    private string $apellido;
    private int $phone_1;
    private int $phone_2;
    private string $descripcion;
    private int $usuario_fk;
    
    public function __construct(int $id=null, string $nombre=null, string $apellido=null, int $phone1=null, int $phone2=null, string $descripcion=null, int $usuario_fk=null) {
        if(!is_null($id)) {
            $this->id = $id;
        }
        
        if(!is_null($nombre)) {
            $this->nombre = $nombre;
        }

        if(!is_null($apellido)) {
            $this->apellido = $apellido;
        }

        if(!is_null($phone1)) {
            $this->phone_1 = $phone1;
        }
        
        if(!is_null($phone2)) {
            $this->phone_2 = $phone2;
        }

        if(!is_null($descripcion)) {
            $this->descripcion = $descripcion;
        }

        if(!is_null($usuario_fk)) {
            $this->usuario_fk = $usuario_fk;
        }
    }
    
    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellido(): string {
        return $this->apellido;
    }

    public function getPhone1(): int {
        return $this->phone_1;
    }

    public function getPhone2(): int {
        return $this->phone_2;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function getUsuario_fk(): int {
        return $this->usuario_fk;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setApellido(string $apellido): void {
        $this->apellido = $apellido;
    }

    public function setPhone1(int $phone1): void {
        $this->phone1 = $phone1;
    }

    public function setPhone2(int $phone2): void {
        $this->phone2 = $phone2;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function setUsuario_fk(int $usuario_fk): void {
        $this->usuario_fk = $usuario_fk;
    }

    public static function obtenerContactosUsuario(PDO $bd, int $usuario_fk): ?array {
        $sql = "SELECT * FROM contacto WHERE usuario_fk = :usuario_fk ORDER BY id";
        $statement = $bd->prepare($sql);
        $statement->execute([":usuario_fk" => $usuario_fk]);
        $statement->setFetchMode(PDO::FETCH_CLASS, Contacto::class);
        $contactos = $statement->fetchAll();
        return $contactos;
    }
    
    public static function obtenerContactoId(PDO $bd, int $id): ?Contacto {
        $sql = "SELECT * FROM contacto WHERE id = :id";
        $statement = $bd->prepare($sql);
        $statement->execute([":id" => $id]);
        $statement->setFetchMode(PDO::FETCH_CLASS, Contacto::class);
        $contacto = ($statement->fetch()) ?: null;
        return $contacto;
    }
    
    public function addContacto(PDO $bd): bool {
        $sql = "INSERT INTO contacto (nombre, apellido, phone_1, phone_2, descripcion, usuario_fk) values (:nombre, :apellido, :phone1, :phone2, :descripcion, :usuario_fk)";
        $statement = $bd->prepare($sql);
        $result = $statement->execute([":nombre" => $this->nombre, ":apellido" => $this->apellido, ":phone1" => $this->phone_1, ":phone2" => $this->phone_2, ":descripcion" => $this->descripcion, ":usuario_fk" => $this->usuario_fk]);
        if ($result) {
            $id = (int) $bd->lastInsertId();
        }
        
        return $result;
    }
    
    public function borrarContacto(PDO $bd): bool {
        $sql = "DELETE FROM contacto WHERE id = :id";
        $statement = $bd->prepare($sql);
        $result = $statement->execute([":id" => $this->id]);
        return $result;
    }
    
    public static function obtenerXML(PDO $bd, int $usuario_fk): string {
        $sql = "SELECT * FROM contacto WHERE usuario_fk = :usuario_fk";
        $statement = $bd->prepare($sql);
        $statement->execute([":usuario_fk" => $usuario_fk]);
        $statement->setFetchMode(PDO::FETCH_CLASS, Contacto::class);
        $contactos = $statement->fetchAll();
        
        $doc = new DOMDocument('1.0', 'UTF-8');
        
        $contactos_element = $doc->createElement('contactos');
        
        foreach ($contactos as $contacto) {
            $contacto_element = $doc->createElement('contacto');
            
            $contacto_element->appendChild($doc->createElement('nombre', $contacto->getNombre()));
            $contacto_element->appendChild($doc->createElement('apellido', $contacto->getApellido()));
            $contacto_element->appendChild($doc->createElement('phone_1', $contacto->getPhone1()));
            $contacto_element->appendChild($doc->createElement('phone_2', $contacto->getPhone2()));
            $contacto_element->appendChild($doc->createElement('descripcion', $contacto->getDescripcion()));
            
            $contactos_element->appendChild($contacto_element);
        }
        
        $doc->appendChild($contactos_element);
        
        return $doc->saveXML();
    }
}

