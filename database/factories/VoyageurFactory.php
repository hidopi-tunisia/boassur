<?php

namespace Database\Factories;

use App\Models\Voyageur;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoyageurFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Voyageur::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'civilite' => $this->faker->randomElement([0, 1]),
            'nom' => $this->faker->name,
            'prenom' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'telephone' => $this->faker->phoneNumber,
            'anniversaire' => $this->faker->date('Y-m-d'),
            'cp' => $this->faker->postcode,
            'ville' => $this->faker->city,
            'adresse' => $this->faker->address,
        ];
    }
}
