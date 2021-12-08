<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubBannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'icon'   => $this->faker->url(),
            'title'  => $this->faker->title(),
            'period' => $this->faker->date(),
        ];
    }
}
