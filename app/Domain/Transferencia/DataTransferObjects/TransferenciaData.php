<?php


namespace App\Domain\Transferencia\DataTransferObjects;


class TransferenciaData
{
    /**
     * TransferenciaData constructor.
     * @param string $pagador
     * @param string $beneficiario
     * @param float $valor
     */
    public function __construct(public string $pagador, public string $beneficiario, public float $valor){}
}
