<?php


namespace App\Domain\carteira\Actions;


use App\Domain\carteira\DataTransferObjects\CarteiraData;
use App\Domain\carteira\Models\Carteira;

class AlterarSaldoCarteiraAction
{
    public function __construct(private Carteira $carteira){}

    public function execute(CarteiraData $carteiraData): void
    {
        $this->carteira->where("usuario_id",$carteiraData->usuario_id)->update([
            "saldo" => $carteiraData->saldo,
        ]);
    }
}
