<?php


namespace App\Domain\Usuario\Actions;


use App\Domain\Usuario\DataTransferObjects\UsuarioData;
use App\Domain\Usuario\Models\Usuario;

/**
 * Class CadastrarUsuarioAction
 * @package App\Domain\Usuario\Actions
 */
final class CadastrarUsuarioAction
{
    /**
     * CadastrarUsuarioAction constructor.
     * @param Usuario $usuario
     */
    public function __construct(private Usuario $usuario) {}

    /**
     * @param UsuarioData $usuarioData
     * @return Usuario
     */
    public function execute(UsuarioData $usuarioData): Usuario
    {
        return $this->usuario->create([
            "nome" => $usuarioData->nome,
            "email" => $usuarioData->email,
            "password" => $usuarioData->password,
            "cpf_cnpj" => $usuarioData->cpf_cnpj,
            "tipo_usuario_id" => $usuarioData->tipo_id
        ]);
    }
}
