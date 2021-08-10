<?php


namespace App\Api\Transferencia\Controllers;


use App\Api\Transferencia\Requests\TransferenciaRequest;
use App\Domain\carteira\Models\Carteira;
use App\Domain\Transferencia\Actions\CriarJobTransferenciaAction;
use App\Domain\Transferencia\Exception\SaldoInsuficiente;
use App\Domain\Transferencia\Exception\TipoContaNaoPermitida;
use App\Domain\Usuario\Models\Usuario;
use Http;
use Illuminate\Http\Request;

class TransferenciaController
{
    public function transferencia(TransferenciaRequest $request, CriarJobTransferenciaAction $transferenciaAction)
    {
        try {
            $transferenciaData = new \App\Domain\Transferencia\DataTransferObjects\TransferenciaData(...$request->validated());
            $transferenciaAction->execute($transferenciaData);
            return response()->json([
                "status" => "successo",
                "message" => "transferencia processada com sucesso"
            ]);
        } catch (TipoContaNaoPermitida | SaldoInsuficiente $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
        catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => "Ocorreu um erro ao processar a transação."
            ], 500);
        }
    }
}
