<?php

namespace Database\Factories;

use App\Models\Accompagnant;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccompagnantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Accompagnant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->name,
            'prenom' => $this->faker->firstName,
            'anniversaire' => $this->faker->date('Y-m-d'),
        ];
    }
}
