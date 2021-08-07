<?php


namespace App\Domain\Usuario\DataTransferObjects;


final class UsuarioData
{

    /**
     * UsuarioData constructor.
     * @param string $nome
     * @param string $cpf_cnpj
     * @param string $email
     * @param int $tipo_id
     * @param string|null $password
     */
    public function __construct(public string $nome, public string $cpf_cnpj,  public string $email, public int $tipo_id, public ?string $password){}
}
