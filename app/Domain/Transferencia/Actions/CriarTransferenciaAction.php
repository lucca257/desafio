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
            "usuario_origem" => $transferenciaData->usuario_origem,
            "usuario_destino" => $transferenciaData->usuario_destino,
            "valor" => $transferenciaData->valor,
        ]);
    }
}
