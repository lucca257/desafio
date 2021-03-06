<?php

namespace App\Api\Usuarios\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Domain\Usuario\Actions\CriarUsuarioAction;
use App\Domain\Usuario\Actions\RegistrarUsuarioAction;
use App\Domain\Usuario\DataTransferObjects\UsuarioData;
use App\Api\Usuarios\Requests\CriarUsuarioRequest;
use App\Domain\Usuario\Exception\CpfCnpjException;

class UsuarioController extends Controller
{
    public function store(CriarUsuarioRequest $request, RegistrarUsuarioAction $action)
    {
        try {
            $usuarioData = new UsuarioData(...$request->validated());
            $action->execute($usuarioData);
            return response()->json([
                'success' => true,
                'message' => 'Usuário criado com successo'
            ]);
        } catch (CpfCnpjException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao criar usuário'
            ], 500);
        }
    }
}
