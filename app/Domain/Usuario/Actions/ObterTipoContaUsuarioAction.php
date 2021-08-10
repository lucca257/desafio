<?php


namespace App\Domain\Usuario\Actions;


use App\Domain\Usuario\Models\TipoUsuario;
use App\Domain\Usuario\Models\Usuario;

class ObterTipoContaUsuarioAction
{
    public function __construct(private Usuario $usuario)
    {
    }

    public function execute(int $usuarioId): TipoUsuario
    {
        return $this->usuario->with('tipo')->find($usuarioId)->tipo;
    }
}
