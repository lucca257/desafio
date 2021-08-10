<?php


namespace App\Domain\Transferencia\Actions;

use App\Domain\Transferencia\DataTransferObjects\TransferenciaData;
use App\Domain\Transferencia\Models\Transferencia;


class CriarTransferenciaAction
{
    public function __construct(private Transferencia $transferencia){}

    public function execute(TransferenciaData $transferenciaData): Transferencia
    {
        return $this->transferencia->create([
            "usuario_origem" => $transferenciaData->pagador,
            "usuario_destino" => $transferenciaData->beneficiario,
            "valor" => $transferenciaData->valor,
        ]);
    }
}
