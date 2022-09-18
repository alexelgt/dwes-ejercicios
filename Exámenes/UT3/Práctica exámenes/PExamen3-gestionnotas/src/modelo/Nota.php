<?php

namespace App;

use \PDO as PDO;

class Nota {
    private int $id;
    private int $valor;
    private int $id_alumno;
    private int $id_asignatura;
    
    public function __construct(int $id=null, int $valor=null, int $id_alumno=null, int $id_asignatura=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        if (!is_null($valor)) {
            $this->valor = $valor;
        }
        
        if (!is_null($id_alumno)) {
            $this->id_alumno = $id_alumno;
        }
        
        if (!is_null($id_asignatura)) {
            $this->id_asignatura = $id_asignatura;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getValor(): int {
        return $this->valor;
    }

    public function getAlumno_fk(): int {
        return $this->id_alumno;
    }

    public function getAsignatura_fk(): int {
        return $this->id_asignatura;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setValor(int $valor): void {
        $this->valor = $valor;
    }

    public function setAlumno_fk(int $id_alumno): void {
        $this->id_alumno = $id_alumno;
    }

    public function setAsignatura_fk(int $id_asignatura): void {
        $this->id_asignatura = $id_asignatura;
    }

    public static function obtenerPorFK(PDO $bd, int $id_alumno, int $id_asignatura): ?Nota {
        $sql = "SELECT * FROM notas WHERE id_alumno = :id_alumno AND id_asignatura = :id_asignatura";
        $statement = $bd->prepare($sql);

        $statement->execute([":id_alumno" => $id_alumno, ":id_asignatura" => $id_asignatura]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Nota::class);
        $nota = ($statement->fetch()) ?: null;

        return $nota;
    }
    
    public function insert(PDO $bd) {
        $sql = "INSERT INTO notas (valor, id_alumno, id_asignatura) values (:valor, :id_alumno, :id_asignatura)";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":valor" => $this->valor, ":id_alumno" => $this->id_alumno, ":id_asignatura" => $this->id_asignatura]);

        if ($result) {
            $this->id = (int) $bd->lastInsertId();
        }

        return $result;
    }
    
    public function update(PDO $bd) {
        $sql = "UPDATE notas SET valor = :valor, id_alumno = :id_alumno, id_asignatura = :id_asignatura WHERE id = :id";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":id" => $this->id, ":valor" => $this->valor, ":id_alumno" => $this->id_alumno, ":id_asignatura" => $this->id_asignatura]);

        return $result;
    }
}