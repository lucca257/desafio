<?php


use App\Domain\carteira\DataTransferObjects\CarteiraData;
use App\Domain\carteira\Models\Carteira;
use App\Domain\Usuario\Models\Usuario;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $action = resolve(\App\Domain\carteira\Actions\SaldoCarteiraAction::class);
        $mock_usuario_id = 1;
        $saldoUsuario = $action->execute($mock_usuario_id);
        $this->assertNotSame(0,$saldoUsuario);
    }
}
