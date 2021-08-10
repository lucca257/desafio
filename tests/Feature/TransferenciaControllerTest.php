<?php

namespace Tests\Feature;


use App\Domain\carteira\Models\Carteira;
use App\Domain\Usuario\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransferenciaControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_o_campo_pagador_deve_ser_obrigatorio()
    {
        $usuarios = Usuario::factory(2)->usuarioComum()->create();
        $mock_data = [
            "beneficiario" => $usuarios->last()->id,
            "valor" => 100
        ];
        $response = $this->post('api/v1/transferir', $mock_data);
        $response
            ->assertStatus(422)
            ->assertJson([
                "pagador" => ["The pagador field is required."]
            ]);
    }

    public function test_o_campo_pagador_deve_existir()
    {
        $usuarios = Usuario::factory(2)->usuarioComum()->create();
        $mock_data = [
            "pagador" => 999,
            "beneficiario" => $usuarios->last()->id,
            "valor" => 100
        ];
        $response = $this->post('api/v1/transferir', $mock_data);
        $response
            ->assertStatus(422)
            ->assertJson([
                "pagador" => ["The selected pagador is invalid."]
            ]);
    }

    public function test_o_campo_beneficiario_deve_ser_obrigatorio()
    {
        $usuarios = Usuario::factory(2)->usuarioComum()->create();
        $mock_data = [
            "pagador" => $usuarios->first()->id,
            "valor" => 100
        ];
        $response = $this->post('api/v1/transferir', $mock_data);
        $response
            ->assertStatus(422)
            ->assertJson([
                "beneficiario" => ["The beneficiario field is required."]
            ]);
    }

    public function test_o_campo_beneficiario_deve_existir()
    {
        $usuarios = Usuario::factory(2)->usuarioComum()->create();
        $mock_data = [
            "pagador" => $usuarios->first()->id,
            "beneficiario" => 99,
            "valor" => 100
        ];
        $response = $this->post('api/v1/transferir', $mock_data);
        $response
            ->assertStatus(422)
            ->assertJson([
                "beneficiario" => ["The selected beneficiario is invalid."]
            ]);
    }

    public function test_deve_retornar_200_ao_processar_transferencia()
    {
        $usuarios = Usuario::factory(2)->usuarioComum()->create();
        Carteira::factory(2)->saldoMaximo()->create();
        $mock_data = [
            "pagador" => $usuarios->first()->id,
            "beneficiario" => $usuarios->last()->id,
            "valor" => 1
        ];
        $response = $this->post('api/v1/transferir', $mock_data);
        $response->assertStatus(200);
    }
}
