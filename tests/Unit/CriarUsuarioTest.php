<?php


use App\Domain\Usuario\Actions\CadastrarUsuarioAction;
use App\Domain\Usuario\DataTransferObjects\UsuarioData;
use App\Domain\Usuario\Exception\CpfCnpjException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CriarUsuarioTest extends TestCase
{
    use RefreshDatabase;

    public function test_deve_criar_um_usuario()
    {
        $action = resolve(CadastrarUsuarioAction::class);
        $usuarioData = new UsuarioData(
            nome : "pedro lucca leonardo de almeida",
            cpf_cnpj : "13864107725",
            email : "pedrolucca257@gmail.com",
            tipo_id : 1,
            password : "any_password"
        );
        $action->execute($usuarioData);
        $this->assertDatabaseHas('usuarios', [
            "nome" => $usuarioData->nome,
            "email" => $usuarioData->email,
            "password" => $usuarioData->password,
            "cpf_cnpj" => $usuarioData->cpf_cnpj,
            "tipo_usuario_id" => $usuarioData->tipo_id
        ]);
    }

    /**
     * @throws CpfCnpjException
     */
    public function test_uma_exception_deve_ocorrer_ao_informar_um_cpf_cnpj_invalido_ao_criar_usuario()
    {
        $this->withoutExceptionHandling();
        $this->expectException(CpfCnpjException::class);
        $this->expectExceptionMessage('o cpf/cnpj informado não é válido');
        $action = resolve(CadastrarUsuarioAction::class);
        $usuarioData = new UsuarioData("pedro lucca leonardo de almeida","00000","pedrolucca257@gmail.com",1,"any_password");
        $action->execute($usuarioData);
    }
}
