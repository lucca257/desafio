<?php


use App\Domain\carteira\Models\Carteira;
use App\Domain\Transferencia\Actions\CriarTransferenciaAction;
use App\Domain\Transferencia\Exception\SaldoInsuficiente;
use App\Domain\Transferencia\Exception\TipoContaNaoPermitida;
use App\Domain\Transferencia\Jobs\CriarTransferenciaJob;
use App\Domain\Transferencia\Jobs\EnviarEmailTransferenciaJob;
use App\Domain\Transferencia\Models\Transferencia;
use App\Domain\Usuario\Models\Usuario;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;


class TransferenciaTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @throws SaldoInsuficiente
     * @throws TipoContaNaoPermitida
     */
    public function test_deve_ocorrer_uma_exception_ao_processar_transferencia_quando_usuario_for_do_tipo_lojista()
    {
        $this->withoutExceptionHandling();
        $this->expectException(TipoContaNaoPermitida::class);
        $this->expectExceptionMessage("Esta conta não pode realizar transferência");
        $usuarios = Usuario::factory(2)->usuarioLojista()->create();
        Carteira::factory(2)->create();
        $action = resolve(\App\Domain\Transferencia\Actions\CriarJobTransferenciaAction::class);
        $transferenciaMock = new \App\Domain\Transferencia\DataTransferObjects\TransferenciaData(
            pagador:  $usuarios->first()->id,
            beneficiario: $usuarios->last()->id,
            valor: 99
        );
        $action->execute($transferenciaMock);
    }

    /**
     * @throws SaldoInsuficiente
     * @throws TipoContaNaoPermitida
     */
    public function test_deve_ocorrer_uma_exception_ao_processar_transferencia_quando_usuario_nao_tiver_saldo_suficiente()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SaldoInsuficiente::class);
        $this->expectExceptionMessage("Saldo insuficiente para realizar transferência");
        $usuarios = Usuario::factory(2)->usuarioComum()->create();
        Carteira::factory(2)->saldoZerado()->create();
        $action = resolve(\App\Domain\Transferencia\Actions\CriarJobTransferenciaAction::class);
        $transferenciaMock = new \App\Domain\Transferencia\DataTransferObjects\TransferenciaData(
            pagador:  $usuarios->first()->id,
            beneficiario: $usuarios->last()->id,
            valor: 99
        );
        $action->execute($transferenciaMock);
    }

    public function test_deve_criar_uma_transferencia() {
        $usuarios = Usuario::factory(2)->create();
        $action = resolve(CriarTransferenciaAction::class);
        $mock_transferencia = new \App\Domain\Transferencia\DataTransferObjects\TransferenciaData(
            pagador: $usuarios->first()->id,
            beneficiario: $usuarios->last()->id,
            valor : 123,
        );
        $action->execute($mock_transferencia);
        $this->assertDatabaseHas('transferencias', [
            "usuario_origem" => $usuarios->first()->id,
            "usuario_destino" => $usuarios->last()->id,
            "valor" => 123,
        ]);
    }

    public function test_deve_altera_o_saldo_das_carteiras_ao_processar_transferencia()
    {
        $usuarios = Usuario::factory(2)->usuarioComum()->create();
        Carteira::factory(2)->saldoMaximo()->create();
        $action = resolve(\App\Domain\Transferencia\Actions\ProcessarTransferenciaAction::class);
        $pagador = $usuarios->first()->id;
        $beneficiario = $usuarios->last()->id;
        $mock_data = new \App\Domain\Transferencia\DataTransferObjects\TransferenciaData(
            pagador : $pagador,
            beneficiario : $beneficiario,
            valor : 100
        );
        $action->execute($mock_data);
        $saldoPagador = 9899;
        $saldoBeneficiario = 10099;
        $this->assertEquals($saldoPagador, Carteira::where('usuario_id', $pagador)->first()->saldo);
        $this->assertEquals($saldoBeneficiario, Carteira::where('usuario_id', $beneficiario)->first()->saldo);
    }

    public function test_deve_verificar_se_usuario_possui_saldo_ao_processar_transferencia()
    {
        $this->withoutExceptionHandling();
        $this->expectException(SaldoInsuficiente::class);
        $this->expectExceptionMessage("Saldo insuficiente para realizar transferência");
        $usuarios = Usuario::factory(2)->usuarioComum()->create();
        Carteira::factory(2)->saldoZerado()->create();
        $action = resolve(\App\Domain\Transferencia\Actions\ProcessarTransferenciaAction::class);
        $pagador = $usuarios->first()->id;
        $beneficiario = $usuarios->last()->id;
        $mock_data = new \App\Domain\Transferencia\DataTransferObjects\TransferenciaData(
            pagador : $pagador,
            beneficiario : $beneficiario,
            valor : 100
        );
        $action->execute($mock_data);
    }

    public function test_um_job_deve_ir_para_fila_ao_processar_transferencia()
    {
        Queue::fake();
        Usuario::factory(2)->create();
        $transferencia = Transferencia::factory(1)->create()->first();
        CriarTransferenciaJob::dispatch($transferencia);
        Queue::assertPushed(CriarTransferenciaJob::class);
    }
}
