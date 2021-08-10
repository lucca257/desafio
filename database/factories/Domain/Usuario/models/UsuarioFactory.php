<?php

namespace Database\Factories\Domain\Usuario\models;

use App\Domain\Usuario\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Usuario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "nome" => $this->faker->name,
            "email" => $this->faker->email,
            "password" => $this->faker->password,
            "cpf_cnpj" => $this->faker->randomNumber(),
            "tipo_usuario_id" => mt_rand(1,2)
        ];
    }

    /**
     * @return UsuarioFactory
     */
    public function usuarioComum()
    {
        return $this->state(function (array $attributes) {
            return [
                "tipo_usuario_id" => 1
            ];
        });
    }

    /**
     * @return UsuarioFactory
     */
    public function usuarioLojista()
    {
        return $this->state(function (array $attributes) {
            return [
                "tipo_usuario_id" => 2
            ];
        });
    }
}
