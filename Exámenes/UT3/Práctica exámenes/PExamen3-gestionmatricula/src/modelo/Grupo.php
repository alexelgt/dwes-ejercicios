<?php

namespace App;

require 'Alumno.php';

use \PDO as PDO;
use \App\Alumno as Alumno;

class Grupo {
    private int $id;
    private string $nombre;
    private ?string $idioma;
    
    public function __construct(int $id=null, string $nombre=null, ?string $idioma=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
        
        if (!is_null($idioma)) {
            $this->idioma = $idioma;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getIdioma(): ?string {
        return $this->idioma;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setIdioma(?string $idioma): void {
        $this->idioma = $idioma;
    }

    public static function obtenerGrupos(PDO $bd): ?array {
        $sql = "SELECT * FROM grupos";
        $statement = $bd->prepare($sql);

        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, Grupo::class);
        $grupos = $statement->fetchAll();

        return $grupos;
    }
    
    public function obtenerAlumnos(PDO $bd): ?array {
        return Alumno::obtenerPorGrupo($bd, $this->id);
    }
}
