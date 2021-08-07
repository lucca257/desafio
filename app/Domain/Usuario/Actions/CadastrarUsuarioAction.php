<?php


namespace App\Domain\Usuario\Actions;


use App\Domain\Usuario\DataTransferObjects\UsuarioData;
use App\Exceptions\CpfCnpjException;
use App\Domain\Usuario\Models\Usuario;
use App\Support\CpfCnpjValidacao;

/**
 * Class CadastrarUsuarioAction
 * @package App\Domain\Usuario\Actions
 */
final class CadastrarUsuarioAction
{
    /**
     * CadastrarUsuarioAction constructor.
     * @param Usuario $usuario
     * @param CpfCnpjValidacao $cpfCnpjValidacao
     */
    public function __construct(private Usuario $usuario, private CpfCnpjValidacao $cpfCnpjValidacao) {}

    /**
     * @param UsuarioData $usuarioData
     * @return Usuario
     * @throws CpfCnpjException
     */
    public function execute(UsuarioData $usuarioData): Usuario
    {
        $usuarioData->cpf_cnpj = $this->validarCpfCnpj($usuarioData->cpf_cnpj);
        return $this->usuario->create([
            "nome" => $usuarioData->nome,
            "email" => $usuarioData->email,
            "password" => $usuarioData->password,
            "cpf_cnpj" => $usuarioData->cpf_cnpj,
            "tipo_usuario_id" => $usuarioData->tipo_id
        ]);
    }

    /**
     * @param string $cpf_cnpj
     * @return string
     * @throws CpfCnpjException
     */
    private function validarCpfCnpj(string $cpf_cnpj): string
    {
        if(!$this->cpfCnpjValidacao->valida($cpf_cnpj)){
            throw new CpfCnpjException("o cpf/cnpj informado não é válido");
        }
        return $this->cpfCnpjValidacao->valor;
    }
}
