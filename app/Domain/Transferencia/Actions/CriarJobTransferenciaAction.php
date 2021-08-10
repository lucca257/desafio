<?php


namespace App\Domain\Transferencia\Actions;


use App\Domain\carteira\Actions\ObterSaldoCarteiraAction;
use App\Domain\Transferencia\DataTransferObjects\TransferenciaData;
use App\Domain\Transferencia\Exception\SaldoInsuficiente;
use App\Domain\Transferencia\Exception\TipoContaNaoPermitida;
use App\Domain\Transferencia\Jobs\CriarTransferenciaJob;
use App\Domain\Usuario\Actions\CriarUsuarioAction;
use App\Domain\Usuario\Actions\ObterTipoContaUsuarioAction;

class CriarJobTransferenciaAction
{
    public function __construct(private CriarTransferenciaAction $transferenciaAction, private ObterSaldoCarteiraAction $saldoCarteiraAction, private ObterTipoContaUsuarioAction $tipoContaUsuarioAction){}

    /**
     * @throws TipoContaNaoPermitida|SaldoInsuficiente
     */
    public function execute(TransferenciaData $transferenciaData): void
    {
        $tipoConta = $this->tipoContaUsuarioAction->execute($transferenciaData->pagador);
        if($tipoConta->tipo_conta === "lojista"){
            throw new TipoContaNaoPermitida("Esta conta não pode realizar transferência");
        }

        $saldo = $this->saldoCarteiraAction->execute($transferenciaData->pagador);

        if($saldo < $transferenciaData->valor){
            throw new SaldoInsuficiente("Saldo insuficiente para realizar transferência");
        }
        $transferencia = $this->transferenciaAction->execute($transferenciaData);
        dispatch(new CriarTransferenciaJob($transferencia));
    }
}
