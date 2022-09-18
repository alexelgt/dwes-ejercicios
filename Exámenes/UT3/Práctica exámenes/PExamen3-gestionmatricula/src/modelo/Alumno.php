<?php

namespace App;

use \PDO as PDO;

use \DOMDocument as DOMDocument;

class Alumno {
    private int $id;
    private string $nombre;
    private string $apellido1;
    private string $apellido2;
    private int $edad;
    private string $sexo;
    private int $grupo_fk;
    
    public function __construct(int $id=null, string $nombre=null, string $apellido1=null, string $apellido2=null, int $edad=null, string $sexo=null, int $grupo_fk=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        
        if (!is_null($apellido1)) {
            $this->apellido1 = $apellido1;
        }
        
        if (!is_null($apellido2)) {
            $this->apellido2 = $apellido2;
        }
        
        if (!is_null($edad)) {
            $this->edad = $edad;
        }
        
        if (!is_null($sexo)) {
            $this->sexo = $sexo;
        }
        
        if (!is_null($grupo_fk)) {
            $this->grupo_fk = $grupo_fk;
        }
    }
    
    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellido1(): string {
        return $this->apellido1;
    }

    public function getApellido2(): string {
        return $this->apellido2;
    }

    public function getEdad(): int {
        return $this->edad;
    }

    public function getSexo(): string {
        return $this->sexo;
    }

    public function getGrupo_fk(): int {
        return $this->grupo_fk;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setApellido1(string $apellido1): void {
        $this->apellido1 = $apellido1;
    }

    public function setApellido2(string $apellido2): void {
        $this->apellido2 = $apellido2;
    }

    public function setEdad(int $edad): void {
        $this->edad = $edad;
    }

    public function setSexo(string $sexo): void {
        $this->sexo = $sexo;
    }

    public function setGrupo_fk(int $grupo_fk): void {
        $this->grupo_fk = $grupo_fk;
    }
    
    public static function obtenerPorGrupo(PDO $bd, int $grupo_fk): ?array {
        $sql = "SELECT * FROM alumnos WHERE grupo_fk = :grupo_fk ORDER BY id";
        $statement = $bd->prepare($sql);

        $statement->execute([":grupo_fk" => $grupo_fk]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Alumno::class);
        $alumnos = $statement->fetchAll();

        return $alumnos;
    }
    
    public static function obtenerPorId(PDO $bd, int $id) {
        $sql = "SELECT * FROM alumnos WHERE id = :id";
        $statement = $bd->prepare($sql);

        $statement->execute([":id" => $id]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Alumno::class);
        $alumno = ($statement->fetch()) ?: null;

        return $alumno;
    }

    public function insert(PDO $bd): bool {
        $sql = "INSERT INTO alumnos (nombre, apellido1, apellido2, edad, sexo, grupo_fk) values (:nombre, :apellido1, :apellido2, :edad, :sexo, :grupo_fk)";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":nombre" => $this->nombre, ":apellido1" => $this->apellido1, ":apellido2" => $this->apellido2, ":edad" => $this->edad, ":sexo" => $this->sexo, ":grupo_fk" => $this->grupo_fk]);

        if ($result) {
            $this->id = (int) $bd->lastInsertId();
        }

        return $result;
    }
    
    public function update(PDO $bd): bool {
        $sql = "UPDATE alumnos SET nombre = :nombre, apellido1 = :apellido1, apellido2 = :apellido2, edad = :edad, sexo = :sexo, grupo_fk = :grupo_fk WHERE id = :id";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":id" => $this->id, ":nombre" => $this->nombre, ":apellido1" => $this->apellido1, ":apellido2" => $this->apellido2, ":edad" => $this->edad, ":sexo" => $this->sexo, ":grupo_fk" => $this->grupo_fk]);
        
        return $result;
    }
    
    public function delete(PDO $bd): bool {
        $sql = "DELETE FROM alumnos WHERE id = :id";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":id" => $this->id]);

        return $result;
    }
    
    public static function obtenerXML(PDO $bd, int $grupo_fk) {
        $sql = "SELECT * FROM alumnos WHERE grupo_fk = :grupo_fk";
        $statement = $bd->prepare($sql);

        $statement->execute([":grupo_fk" => $grupo_fk]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Alumno::class);
        $alumnos = $statement->fetchAll();

        $doc = new DOMDocument('1.0', 'UTF-8');

        $elementos_element = $doc->createElement('alumnos');

        foreach ($alumnos as $alumno) {
            $elemento_element = $doc->createElement('alumno');

            $elemento_element->appendChild($doc->createElement('nombre', $alumno->getNombre()));
            $elemento_element->appendChild($doc->createElement('apellido1', $alumno->getApellido1()));
            $elemento_element->appendChild($doc->createElement('apellido2', $alumno->getApellido2()));
            $elemento_element->appendChild($doc->createElement('edad', $alumno->getEdad()));
            $elemento_element->appendChild($doc->createElement('sexo', $alumno->getSexo()));

            $elementos_element->appendChild($elemento_element);
        }

        $doc->appendChild($elementos_element);

        return $doc->saveXML();
    }
}

