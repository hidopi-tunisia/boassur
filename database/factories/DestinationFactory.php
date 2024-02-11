<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

class DestinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Destination::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom' => $this->faker->country,
            'alpha2' => $this->faker->countryCode,
            'alpha3' => $this->faker->countryISOAlpha3,
            'code' => null,
            'article' => null,
        ];
    }
}
