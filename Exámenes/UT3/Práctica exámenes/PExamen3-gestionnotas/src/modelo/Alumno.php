<?php

namespace App;

use \PDO as PDO;

class Alumno {
    private int $id;
    private string $nombre;
    private string $apellido1;
    private string $apellido2;
    private int $id_profesor;
    
    public function __construct(int $id=null, string $nombre=null, string $apellido1=null, string $apellido2=null, int $id_profesor=null) {
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
        
        if (!is_null($id_profesor)) {
            $this->id_profesor = $id_profesor;
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

    public function getProfesor_fk(): int {
        return $this->id_profesor;
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

    public function setProfesor_fk(int $id_profesor): void {
        $this->id_profesor = $id_profesor;
    }
    
    public static function obtenerPorProfesor(PDO $bd, int $id_profesor): ?array {
        $sql = "SELECT * FROM alumnos WHERE id_profesor = :id_profesor ORDER BY id";
        $statement = $bd->prepare($sql);

        $statement->execute([":id_profesor" => $id_profesor]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Alumno::class);
        $alumnos = $statement->fetchAll();

        return $alumnos;
    }
    
    public function obtenerValorNota(PDO $bd, int $asignatura_fk): string {
        $nota = Nota::obtenerPorFK($bd, $this->id, $asignatura_fk);
        
        if (is_null($nota)) {
            return "";
        }
        
        return $nota->getValor();
    }
}