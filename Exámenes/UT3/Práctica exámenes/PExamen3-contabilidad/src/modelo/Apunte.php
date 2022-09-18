<?php

namespace App;

use \PDO as PDO;
use \DOMDocument as DOMDocument;

class Apunte {
    private int $id;
    private int $ingreso;
    private string $concepto;
    private float $cantidad;
    private string $fecha;
    private int $usuario_fk;
    
    public function __construct(int $id=null, int $ingreso=null, string $concepto=null, float $cantidad=null, string $fecha=null, int $usuario_fk=null) {
        if(!is_null($id)) {
            $this->id = $id;
        }
        
        if(!is_null($ingreso)) {
            $this->ingreso = $ingreso;
        }
        
        if(!is_null($concepto)) {
            $this->concepto = $concepto;
        }
        
        if(!is_null($cantidad)) {
            $this->cantidad = $cantidad;
        }
        
        if(!is_null($fecha)) {
            $this->fecha = $fecha;
        }
        
        if(!is_null($usuario_fk)) {
            $this->usuario_fk = $usuario_fk;
        }
    }
    
    public function getId(): int {
        return $this->id;
    }

    public function getIngreso(): int {
        return $this->ingreso;
    }

    public function getConcepto(): string {
        return $this->concepto;
    }

    public function getCantidad(): float {
        return $this->cantidad;
    }

    public function getFecha(): string {
        return $this->fecha;
    }

    public function getUsuario_fk(): int {
        return $this->usuario_fk;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setIngreso(int $ingreso): void {
        $this->ingreso = $ingreso;
    }

    public function setConcepto(string $concepto): void {
        $this->concepto = $concepto;
    }

    public function setCantidad(float $cantidad): void {
        $this->cantidad = $cantidad;
    }

    public function setFecha(string $fecha): void {
        $this->fecha = $fecha;
    }

    public function setUsuario_fk(int $usuario_fk): void {
        $this->usuario_fk = $usuario_fk;
    }

    public static function obtenerPorUsuario(PDO $bd, int $usuario_fk): ?array {
        $sql = "SELECT * FROM apuntes WHERE usuario_fk = :usuario_fk ORDER BY id";
        $statement = $bd->prepare($sql);

        $statement->execute([":usuario_fk" => $usuario_fk]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Apunte::class);
        $apuntes = $statement->fetchAll();

        return $apuntes;
    }
    
    public static function obtenerIngresosPorUsuario(PDO $bd, int $usuario_fk): ?array {
        $sql = "SELECT * FROM apuntes WHERE usuario_fk = :usuario_fk AND ingreso = 1 ORDER BY id";
        $statement = $bd->prepare($sql);

        $statement->execute([":usuario_fk" => $usuario_fk]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Apunte::class);
        $apuntes = $statement->fetchAll();

        return $apuntes;
    }
    
    public static function obtenerGastosPorUsuario(PDO $bd, int $usuario_fk): ?array {
        $sql = "SELECT * FROM apuntes WHERE usuario_fk = :usuario_fk AND ingreso = 0 ORDER BY id";
        $statement = $bd->prepare($sql);

        $statement->execute([":usuario_fk" => $usuario_fk]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Apunte::class);
        $apuntes = $statement->fetchAll();

        return $apuntes;
    }
    
    private static function sumaAPuntes($carry, $item) {
        $carry += $item->getCantidad();
        
        return $carry;
    }
    
    public static function obtenerSaldoUsuario(PDO $bd, int $usuario_fk): float {
        $ingresos = self::obtenerIngresosPorUsuario($bd, $usuario_fk);
        $gastos = self::obtenerGastosPorUsuario($bd, $usuario_fk);
        
        $ingresos_valor = array_reduce($ingresos, "self::sumaAPuntes");
        $gastos_valor = array_reduce($gastos, "self::sumaAPuntes");
        
        return $ingresos_valor - $gastos_valor;
    }
    
    public function add(PDO $bd): bool {
        $sql = "INSERT INTO apuntes (ingreso, concepto, cantidad, fecha, usuario_fk) values (:ingreso, :concepto, :cantidad, :fecha, :usuario_fk)";
        $statement = $bd->prepare($sql);

        $result = $statement->execute([":ingreso" => $this->ingreso, ":concepto" => $this->concepto, ":cantidad" => $this->cantidad, ":fecha" => $this->fecha, ":usuario_fk" => $this->usuario_fk]);

        if ($result) {
            $id = (int) $bd->lastInsertId();
        }

        return $result;
    }
    
    public static function obtenerXML(PDO $bd, int $usuario_fk): string {
        $sql = "SELECT * FROM apuntes WHERE usuario_fk = :usuario_fk";
        $statement = $bd->prepare($sql);

        $statement->execute([":usuario_fk" => $usuario_fk]);

        $statement->setFetchMode(PDO::FETCH_CLASS, Apunte::class);
        $apuntes = $statement->fetchAll();

        $doc = new DOMDocument('1.0', 'UTF-8');

        $elementos_element = $doc->createElement('apuntes');

        foreach ($apuntes as $apunte) {
            $elemento_element = $doc->createElement('apunte');

            $elemento_element->appendChild($doc->createElement('ingreso', $apunte->getIngreso()));
            $elemento_element->appendChild($doc->createElement('concepto', $apunte->getConcepto()));
            $elemento_element->appendChild($doc->createElement('cantidad', $apunte->getCantidad()));
            $elemento_element->appendChild($doc->createElement('fecha', $apunte->getFecha()));

            $elementos_element->appendChild($elemento_element);
        }

        $doc->appendChild($elementos_element);

        return $doc->saveXML();
    }
}