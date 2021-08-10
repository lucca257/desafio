<?php


namespace App\Domain\Transferencia\Jobs;


use App\Domain\carteira\Actions\AlterarSaldoCarteiraAction;
use App\Domain\carteira\DataTransferObjects\CarteiraData;
use App\Domain\Transferencia\Actions\ConsultarAutorizadorAction;
use App\Domain\Transferencia\Actions\ProcessarTransferenciaAction;
use App\Domain\Transferencia\DataTransferObjects\TransferenciaData;
use App\Domain\Transferencia\Models\Transferencia;
use App\Support\EnviarEmail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class CriarTransferenciaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * numero de tentativas de retry
     * @var int
     */
    public $tries = 3;


    public function __construct(private Transferencia $transferencia)
    {
        $this->onQueue('transferencias');
    }


    /**
     * @throws Exception
     */
    public function handle(ConsultarAutorizadorAction $autorizadorAction, ProcessarTransferenciaAction $processarTransferencia, EnviarEmail $enviarEmail)
    {
        $response = $autorizadorAction->execute();
        if ($response["message"] != "Autorizado")
        {
            throw new Exception("Transferencia não autorizada");
        }
        $transferenciaData = new TransferenciaData($this->transferencia->usuario_origem, $this->transferencia->usuario_destino, $this->transferencia->valor);
        $processarTransferencia->execute($transferenciaData);
        $this->transferencia->update([
            "status" => "processado"
        ]);
        $enviarEmail->execute();
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        $this->transferencia->update([
            "status" => "cancelado"
        ]);
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array
     */
    public function backoff()
    {
        return [1, 5, 10];
    }
}
