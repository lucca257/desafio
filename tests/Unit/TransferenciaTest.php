<?php


use App\Domain\Transferencia\Actions\CriarTransferenciaAction;
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
}
