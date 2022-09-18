<?php

namespace App;

class PartidaGuessANumber {
    
    const PARTIDA_GANADA = 1;
    const PARTIDA_PERDIDA = 0;
    const NUMERO_MAYOR = 2;
    const NUMERO_MENOR = 3;
    
    private int $numeroIntentos;
    private int $limiteInferior;
    private int $limiteSuperior;
    
    private int $numeroAleatorio;
    
    private bool $partidaIniciada = false;
    
    public function __construct(int $numeroIntentos=5, int $limiteInferior=1, int $limiteSuperior=10) {
        $this->setNumeroIntentos($numeroIntentos);
        $this->setLimites($limiteInferior, $limiteSuperior);
        
        $this->setNumeroAleatorio();
    }
    
    public function getNumeroIntentos(): int {
        return $this->numeroIntentos;
    }

    public function getLimiteInferior(): int {
        return $this->limiteInferior;
    }

    public function getLimiteSuperior(): int {
        return $this->limiteSuperior;
    }
    
    public function getNumeroAleatorio(): int {
        return $this->numeroAleatorio;
    }
    
    public function getPartidaIniciada(): bool {
        return $this->partidaIniciada;
    }

    private function setNumeroIntentos(int $numeroIntentos): void {
        $this->numeroIntentos = $numeroIntentos;
    }
    
    private function restarNumeroIntentos(): bool {
        $this->setNumeroIntentos($this->getNumeroIntentos() - 1);
        
        return $this->getNumeroIntentos() > 0;
    }
    
    private function setLimites(int $limiteInferior, int $limiteSuperior): void {
        // Decido que si max < min también sea correcto pero intercambio los valores
        if ($limiteSuperior < $limiteInferior) {
            $this->setLimiteInferior($limiteSuperior);
            $this->setLimiteSuperior($limiteInferior);
        }
        else {
            $this->setLimiteInferior($limiteInferior);
            $this->setLimiteSuperior($limiteSuperior);
        }
    }

    private function setLimiteInferior(int $limiteInferior): void {
        $this->limiteInferior = $limiteInferior;
    }

    private function setLimiteSuperior(int $limiteSuperior): void {
        $this->limiteSuperior = $limiteSuperior;
    }

    private function setNumeroAleatorio(): void {
        $this->numeroAleatorio = mt_rand($this->getLimiteInferior(), $this->getLimiteSuperior());
    }

    public function realizarJugada(int $numero): int {
        if (!$this->partidaIniciada) {
            $this->partidaIniciada = true;
        }
        
        if ($numero === $this->getNumeroAleatorio()) {
            return self::PARTIDA_GANADA;
        }
        
        $quedan_intentos = $this->restarNumeroIntentos();
        
        if (!$quedan_intentos) {
            return self::PARTIDA_PERDIDA;
        }
        
        if ($numero > $this->getNumeroAleatorio()) {
            if ($numero < $this->getLimiteSuperior()) {
                $this->setLimiteSuperior($numero);
            }
            
            return self::NUMERO_MAYOR;
        }
        else {
            if ($numero > $this->getLimiteInferior()) {
                $this->setLimiteInferior($numero);
            }
            
            return self::NUMERO_MENOR;
        }
    }
}

