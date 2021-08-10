<?php


use App\Domain\carteira\DataTransferObjects\CarteiraData;
use App\Domain\carteira\Models\Carteira;
use App\Domain\Transferencia\Exception\SaldoInsuficiente;
use App\Domain\Transferencia\Exception\TipoContaNaoPermitida;
use App\Domain\Transferencia\Models\Transferencia;
use App\Domain\Usuario\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CarteiraTest extends TestCase
{
    use DatabaseMigrations;
    public function test_deve_criar_uma_carteira_para_o_usuario()
    {
        $action = resolve(\App\Domain\carteira\Actions\CriarCarteiraAction::class);
        $carteiraData = new CarteiraData(
            usuario_id: 1,
            saldo: 9999
        );
        $action->execute($carteiraData);
        $this->assertDatabaseHas('carteiras', [
            "usuario_id" => $carteiraData->usuario_id,
            "saldo" => $carteiraData->saldo
        ]);
    }

    public function test_deve_retornar_o_saldo_da_carteira_do_usuario()
    {
        Usuario::factory(1)->create();
        Carteira::factory(1)->create();
        $action = resolve(\App\Domain\carteira\Actions\ObterSaldoCarteiraAction::class);
        $mock_usuario_id = 1;
        $saldoUsuario = $action->execute($mock_usuario_id);
        $this->assertNotSame(0,$saldoUsuario);
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
