<?php


namespace App\Domain\Transferencia\DataTransferObjects;


class TransferenciaData
{
    /**
     * TransferenciaData constructor.
     * @param string $usuario_origem
     * @param string $usuario_destino
     * @param float $valor
     */
    public function __construct(public string $usuario_origem, public string $usuario_destino, public float $valor){}
}
