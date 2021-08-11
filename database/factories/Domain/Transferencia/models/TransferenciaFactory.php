<?php

namespace Database\Factories\Domain\Transferencia\models;

use App\Domain\Transferencia\Models\Transferencia;
use App\Domain\Usuario\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferenciaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transferencia::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $transferencias = Transferencia::all()->pluck('usuario_origem');
        $usuarios = Usuario::whereNotIn('id', $transferencias)->pluck('id');

        return [
            "usuario_origem" => $this->faker->unique()->randomElement($usuarios),
            "usuario_destino" => $this->faker->unique()->randomElement($usuarios),
            "valor" => mt_rand(1,9999)
        ];
    }
}
