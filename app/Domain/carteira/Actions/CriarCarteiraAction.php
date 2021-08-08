<?php


namespace App\Domain\carteira\Actions;


use App\Domain\carteira\DataTransferObjects\CarteiraData;
use App\Domain\carteira\Models\Carteira;

class CriarCarteiraAction
{
    public function __construct(private Carteira $carteira){}

    public function execute(CarteiraData $carteiraData): Carteira
    {
        return $this->carteira->create([
            "usuario_id" => $carteiraData->usuario_id,
            "saldo" => $carteiraData->saldo,
        ]);
    }
}
