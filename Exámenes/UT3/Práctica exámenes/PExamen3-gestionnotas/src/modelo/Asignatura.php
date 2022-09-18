<?php

namespace App;

use \PDO as PDO;

class Asignatura {
    private int $id;
    private string $nombre;
    
    public function __construct(int $id=null, string $nombre=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->nombre;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $nombre): void {
        $this->nombre = $nombre;
    }

    public static function obtenerTodas(PDO $bd): ?array {
        $sql = "SELECT * FROM asignaturas";
        $statement = $bd->prepare($sql);

        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_CLASS, Asignatura::class);
        $asignaturas = $statement->fetchAll();

        return $asignaturas;
    }
}