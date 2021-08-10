<?php


use App\Domain\Transferencia\Actions\CriarTransferenciaAction;
use App\Domain\Transferencia\Exception\SaldoInsuficiente;
use App\Domain\Transferencia\Exception\TipoContaNaoPermitida;
use App\Domain\Usuario\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class TransferenciaTest extends TestCase
{
    use DatabaseMigrations;

    public function test_deve_criar_uma_transferencia() {
        $usuarios = Usuario::factory(1)->create();

        $action = resolve(CriarTransferenciaAction::class);
        $mock_transferencia = new \App\Domain\Transferencia\DataTransferObjects\TransferenciaData(
            usuario_origem : $usuarios->first()->id,
            usuario_destino : $usuarios->last()->id,
            valor : 123,
        );
        $action->execute($mock_transferencia);
        $this->assertDatabaseHas('transferencias', [
            "usuario_origem" => $usuarios->first()->id,
            "usuario_destino" => $usuarios->last()->id,
            "valor" => 123,
        ]);
    }

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
        $action = resolve(\App\Domain\Transferencia\Actions\ProcessarTransferenciaAction::class);
        $transferenciaMock = new \App\Domain\Transferencia\DataTransferObjects\TransferenciaData(
            usuario_origem:  $usuarios->first()->id,
            usuario_destino: $usuarios->last()->id,
            valor: 99
        );
        $action->execute($transferenciaMock);
    }
}
