<?php


namespace App\Domain\carteira\Actions;


use App\Domain\carteira\DataTransferObjects\CarteiraData;
use App\Domain\carteira\Models\Carteira;

class ObterSaldoCarteiraAction
{
    public function __construct(private Carteira $carteira){}

    public function execute(int $usuario_id): float
    {
        return $this->carteira->where("usuario_id", $usuario_id)->first()->saldo ?? 0;
    }
}
