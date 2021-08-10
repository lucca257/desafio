<?php


namespace App\Domain\Transferencia\Actions;


use App\Domain\carteira\Actions\AlterarSaldoCarteiraAction;
use App\Domain\carteira\Actions\ObterSaldoCarteiraAction;
use App\Domain\carteira\DataTransferObjects\CarteiraData;
use App\Domain\Transferencia\DataTransferObjects\TransferenciaData;
use App\Domain\Transferencia\Exception\SaldoInsuficiente;

class ProcessarTransferenciaAction
{
    public function __construct(private AlterarSaldoCarteiraAction $alterarSaldoCarteira, private ObterSaldoCarteiraAction $obterSaldoCarteira){}

    /**
     * @throws SaldoInsuficiente
     */
    public function execute(TransferenciaData $transferenciaData)
    {
        $saldoUsuarioOrigem = $this->obterSaldoCarteira->execute($transferenciaData->pagador);
        if($saldoUsuarioOrigem < $transferenciaData->valor)
        {
            throw new SaldoInsuficiente("Saldo insuficiente para realizar transferência");
        }
        $saldoUsuarioOrigem -= $transferenciaData->valor;

        $usuarioOrigem = new CarteiraData(
            usuario_id: $transferenciaData->pagador,
            saldo: $saldoUsuarioOrigem
        );
        $this->alterarSaldoCarteira->execute($usuarioOrigem);

        $saldoUsuarioDestino = $this->obterSaldoCarteira->execute($transferenciaData->beneficiario);
        $saldoUsuarioDestino += $transferenciaData->valor;

        $usuarioDestino = new CarteiraData(
            usuario_id: $transferenciaData->beneficiario,
            saldo: $saldoUsuarioDestino
        );
        $this->alterarSaldoCarteira->execute($usuarioDestino);
    }
}
