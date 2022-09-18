<?php

namespace App;

use \PDO as PDO;
use \DOMDocument as DOMDocument;

class Profesor {
    private int $id;
    private string $nombre;
    private string $password;


    public function __construct(int $id = null, string $nombre = null, string $password = null) {
        if (!is_null($id)) {
            $this->id = $id;
        }

        if (!is_null($nombre)) {
            $this->nombre = $nombre;
        }

        if (!is_null($password)) {
            $this->password = $password;
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->nombre;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public static function obtenerPorLogin(PDO $bd, string $nombre, string $password): ?Profesor {
        $sql = "SELECT * FROM profesores WHERE nombre = :nombre AND password = :password";
        $statement = $bd->prepare($sql);

        $statement->execute([":nombre" => $nombre, ":password" => $password]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Profesor::class);
        $usuario = ($statement->fetch()) ?: null;

        return $usuario;
    }
    
    public function obtenerXML(PDO $bd): string {
        $alumnos = Alumno::obtenerPorProfesor($bd, $this->id);
        $asignaturas = Asignatura::obtenerTodas($bd);

        $doc = new DOMDocument('1.0', 'UTF-8');

        $elementos_element = $doc->createElement('alumnos');

        foreach ($alumnos as $alumno) {
            $elemento_element = $doc->createElement('alumno');

            $elemento_element->appendChild($doc->createElement('nombre', $alumno->getNombre()));
            $elemento_element->appendChild($doc->createElement('apellido1', $alumno->getApellido1()));
            $elemento_element->appendChild($doc->createElement('apellido2', $alumno->getApellido2()));
            
            foreach ($asignaturas as $asignatura) {
                $nota = $alumno->obtenerValorNota($bd, $asignatura->getId());
                
                $elemento_element->appendChild($doc->createElement($asignatura->getName(), $nota));
            }

            $elementos_element->appendChild($elemento_element);
        }

        $doc->appendChild($elementos_element);

        return $doc->saveXML();
    }
}
