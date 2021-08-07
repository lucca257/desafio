<?php


use App\Domain\Usuario\Actions\CadastrarUsuarioAction;
use App\Domain\Usuario\DataTransferObjects\UsuarioData;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CriarUsuarioTest extends TestCase
{
    use RefreshDatabase;
    public function test_deve_criar_um_usuario()
    {
        $action = resolve(CadastrarUsuarioAction::class);
        $usuarioData = new UsuarioData("pedro lucca leonardo de almeida","13864107725","pedrolucca257@gmail.com",1,"any_password");
        $action->execute($usuarioData);
        $this->assertDatabaseHas('usuarios', [
            "nome" => $usuarioData->nome,
            "email" => $usuarioData->email,
            "password" => $usuarioData->password,
            "cpf_cnpj" => $usuarioData->cpf_cnpj,
            "tipo_usuario_id" => $usuarioData->tipo_id
        ]);
    }
}
