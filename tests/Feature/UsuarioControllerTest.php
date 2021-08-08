<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_deve_retornar_200_ao_criar_usuario()
    {
        $mock_usuario = [
            "nome" => "pedro lucca leonardo de almeida",
            "cpf_cnpj" => "13864107725",
            "email" => "pedrolucca257@gmail.com",
            "password" => "any_password",
            "tipo_id" => 1,
        ];
        $response = $this->post('/api/v1/usuario', $mock_usuario);
        $response
            ->assertStatus(200)
            ->assertJson([
            'success' => true,
            'message' => 'Usuário criado com successo'
        ]);
    }
}
