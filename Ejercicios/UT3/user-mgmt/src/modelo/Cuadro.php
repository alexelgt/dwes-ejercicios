<?php
namespace App;

use \PDO as PDO;

class Cuadro {
    private int $id;
    private string $title;
    private string $img;
    private string $description;
    private string $period;
    private string $technique;
    private int $year;
    private int $painter_fk;
    
    public function __construct(int $id=null, string $title=null, string $img=null, string $description=null, string $period=null, string $technique=null, int $year=null, int $painter_fk=null) {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        if (!is_null($title)) {
            $this->title = $title;
        }
        
        if (!is_null($img)) {
            $this->img = $img;
        }
        
        if (!is_null($description)) {
            $this->description = $description;
        }
        
        if (!is_null($period)) {
            $this->period = $period;
        }
        
        if (!is_null($technique)) {
            $this->technique = $technique;
        }
        
        if (!is_null($year)) {
            $this->year = $year;
        }
        
        if (!is_null($painter_fk)) {
            $this->painter_fk = $painter_fk;
        }
    }
    
    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getImg(): string {
        return $this->img;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPeriod(): string {
        return $this->period;
    }

    public function getTechnique(): string {
        return $this->technique;
    }

    public function getYear(): int {
        return $this->year;
    }

    public function getPainter_fk(): int {
        return $this->painter_fk;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function setImg(string $img): void {
        $this->img = $img;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setPeriod(string $period): void {
        $this->period = $period;
    }

    public function setTechnique(string $technique): void {
        $this->technique = $technique;
    }

    public function setYear(string $year): void {
        $this->year = $year;
    }

    public function setPainter_fk(int $painter_fk): void {
        $this->painter_fk = $painter_fk;
    }

    public static function obtenerCuadroPorPintor(PDO $bd, int $painter_fk): ?array {
        //$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = "SELECT * FROM paintings WHERE painter_fk = :painter_fk ORDER BY id";
        $sth = $bd->prepare($sql);
        $sth->execute([":painter_fk" => $painter_fk]);
        $sth->setFetchMode(PDO::FETCH_CLASS, Cuadro::class);
        $pintores = $sth->fetchAll();
        return $pintores;
    }
    
    public static function obtenerCuadroPorId(PDO $bd, int $id): ?Cuadro {
        //$bd->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $sql = "SELECT * FROM paintings WHERE id = :id";
        $sth = $bd->prepare($sql);
        $sth->execute([":id" => $id]);
        $sth->setFetchMode(PDO::FETCH_CLASS, Cuadro::class);
        $usuario = ($sth->fetch()) ?: null;
        return $usuario;
    }

}