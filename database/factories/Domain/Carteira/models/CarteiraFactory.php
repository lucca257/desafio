<?php

namespace Database\Factories\Domain\Carteira\models;

use App\Domain\carteira\Models\Carteira;
use App\Domain\Usuario\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarteiraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Carteira::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $carteira = Carteira::all()->pluck('usuario_id');
        $usuarios = Usuario::whereNotIn('id', $carteira)->pluck('id');
        return [
            "usuario_id" =>  $this->faker->unique()->randomElement($usuarios),
            "saldo" => mt_rand(1,9999)
        ];
    }

    /**
     * @return CarteiraFactory
     */
    public function saldoZerado(): CarteiraFactory
    {
        return $this->state(function (array $attributes) {
            return [
                "saldo" => 0
            ];
        });
    }

    /**
     * @return CarteiraFactory
     */
    public function saldoMaximo(): CarteiraFactory
    {
        return $this->state(function (array $attributes) {
            return [
                "saldo" => 9999
            ];
        });
    }
}
