<?php

namespace App\Api\Usuarios\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Domain\Usuario\Actions\CadastrarUsuarioAction;
use App\Domain\Usuario\DataTransferObjects\UsuarioData;
use App\Api\Usuarios\Requests\CriarUsuarioRequest;

class UsuarioController extends Controller
{
    public function store(CriarUsuarioRequest $request, CadastrarUsuarioAction $action)
    {
        try {
            $usuarioData = new UsuarioData(...$request->validated());
            $action->execute($usuarioData);
            return response()->json($usuarioData);
        } catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }
}
