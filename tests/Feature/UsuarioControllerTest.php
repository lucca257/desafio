<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsuarioControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_campo_nome_obrigatorio_ao_criar_usuario()
    {
        $mock_usuario = [
            "cpf_cnpj" => "13864107725",
            "email" => "pedrolucca257@gmail.com",
            "password" => "any_password",
            "tipo_id" => 1,
        ];
        $response = $this->post('/api/v1/usuario', $mock_usuario);
        $response
            ->assertStatus(422)
            ->assertJson([
                "nome" => ["The nome field is required."]
            ]);
    }

    public function test_campo_email_obrigatorio_ao_criar_usuario()
    {
        $mock_usuario = [
            "nome" => "pedro lucca leonardo de almeida",
            "cpf_cnpj" => "13864107725",
            "password" => "any_password",
            "tipo_id" => 1,
        ];
        $response = $this->post('/api/v1/usuario', $mock_usuario);
        $response
            ->assertStatus(422)
            ->assertJson([
                "email" => ["The email field is required."]
            ]);
    }

    public function test_campo_cpf_cnpj_obrigatorio_ao_criar_usuario()
    {
        $mock_usuario = [
            "nome" => "pedro lucca leonardo de almeida",
            "email" => "pedrolucca257@gmail.com",
            "password" => "any_password",
            "tipo_id" => 1,
        ];
        $response = $this->post('/api/v1/usuario', $mock_usuario);
        $response
            ->assertStatus(422)
            ->assertJson([
                "cpf_cnpj" => ["The cpf cnpj field is required."]
            ]);
    }

    public function test_campo_email_deve_ser_unico_ao_criar_usuario()
    {
        $mock_usuario = [
            "nome" => "pedro lucca leonardo de almeida",
            "cpf_cnpj" => "13864107725",
            "email" => "pedrolucca257@gmail.com",
            "password" => "any_password",
            "tipo_id" => 1,
        ];
        $this->post('/api/v1/usuario', $mock_usuario);
        $mock_usuario["cpf_cnpj"] = "36.350.988/0001-66";
        $response = $this->post('/api/v1/usuario', $mock_usuario);
        $response
            ->assertStatus(422)
            ->assertJson([
                "email" => ["The email has already been taken."]
            ]);
    }

    public function test_campo_cpf_cnpj_deve_ser_unico_ao_criar_usuario()
    {
        $mock_usuario = [
            "nome" => "pedro lucca leonardo de almeida",
            "cpf_cnpj" => "13864107725",
            "email" => "pedrolucca257@gmail.com",
            "password" => "any_password",
            "tipo_id" => 1,
        ];
        $this->post('/api/v1/usuario', $mock_usuario);
        $mock_usuario["email"] = "any_email@gmail.com";
        $response = $this->post('/api/v1/usuario', $mock_usuario);
        $response
            ->assertStatus(422)
            ->assertJson([
                "cpf_cnpj" => ["The cpf cnpj has already been taken."]
            ]);
    }

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

    public function test_deve_retornar_500_ao_criar_usuario_com_cpf_cnpj_invalido()
    {
        $mock_usuario = [
            "nome" => "pedro lucca leonardo de almeida",
            "cpf_cnpj" => "13864107720",
            "email" => "pedrolucca257@gmail.com",
            "password" => "any_password",
            "tipo_id" => 1,
        ];
        $response = $this->post('/api/v1/usuario', $mock_usuario);
        $response
            ->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'o cpf/cnpj informado não é válido'
            ]);
    }
}
