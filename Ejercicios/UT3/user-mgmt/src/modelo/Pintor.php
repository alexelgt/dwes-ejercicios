<?php
namespace App;

use \PDO as PDO;

class Pintor {
    private int $id;
    private string $name;
    
    public function __construct(int $id=null, string $name=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        if (!is_null($name)) {
            $this->name = $name;
        }
    }
    
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public static function obtenerPintores(PDO $bd): ?array {
        //$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = "SELECT * FROM painters ORDER BY id";
        $sth = $bd->prepare($sql);
        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_CLASS, Pintor::class);
        $pintores = $sth->fetchAll();
        return $pintores;
    }

}