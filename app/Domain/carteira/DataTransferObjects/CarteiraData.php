<?php


namespace App\Domain\carteira\DataTransferObjects;


class CarteiraData
{
    /**
     * CarteiraData constructor.
     * @param int $usuario_id
     * @param float $saldo
     */
    public function __construct(public int $usuario_id, public float $saldo){}
}
