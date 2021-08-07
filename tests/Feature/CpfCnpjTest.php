<?php

namespace Tests\Feature;

use App\Support\CpfCnpjValidacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

class CpfCnpjTest extends TestCase
{
    private CpfCnpjValidacao $cpfCnpjValidacao;

    public function setup() : void
    {
        $this->cpfCnpjValidacao = new CpfCnpjValidacao();
    }

   public function test_deve_retornar_sucesso_ao_validar_cpf()
   {
        $mock_cpf = "138.641.077-25";
        $validacao = $this->cpfCnpjValidacao->valida($mock_cpf);
        $this->assertEquals(true, $validacao);
   }

    public function test_deve_retornar_sucesso_ao_validar_cnpj()
    {
        $mock_cnpj = "70.922.330/0001-10";
        $validacao = $this->cpfCnpjValidacao->valida($mock_cnpj);
        $this->assertEquals(true, $validacao);
    }

    public function test_deve_retornar_falso_ao_validar_cpf()
    {
        $mock_cpf = "138.641.077-00";
        $validacao = $this->cpfCnpjValidacao->valida($mock_cpf);
        $this->assertEquals(false, $validacao);
    }

    public function test_deve_retornar_falso_ao_validar_cnpj()
    {
        $mock_cnpj = "70.922.330/0001-00";
        $validacao = $this->cpfCnpjValidacao->valida($mock_cnpj);
        $this->assertEquals(false, $validacao);
    }
}
