<?php


namespace App\Domain\Usuario\Actions;


use App\Domain\carteira\Actions\CriarCarteiraAction;
use App\Domain\carteira\DataTransferObjects\CarteiraData;
use App\Domain\Usuario\DataTransferObjects\UsuarioData;
use App\Domain\Usuario\Exception\CpfCnpjException;
use App\Domain\Usuario\Models\Usuario;

final class RegistrarUsuarioAction
{
    /**
     * CadastrarUsuarioAction constructor.
     * @param CriarUsuarioAction $criarUsuarioAction
     * @param CriarCarteiraAction $criarCarteiraAction
     */
    public function __construct(private CriarUsuarioAction $criarUsuarioAction,  private CriarCarteiraAction $criarCarteiraAction){}

    /**
     * @param UsuarioData $usuarioData
     * @return Usuario
     * @throws CpfCnpjException
     */
    public function execute(UsuarioData $usuarioData): Usuario
    {
        $usuarioCriado = $this->criarUsuarioAction->execute($usuarioData);
        $this->criarCarteiraAction->execute(new CarteiraData(
            usuario_id: $usuarioCriado->id,
            saldo: 0
        ));
        return $usuarioCriado;
    }
}
