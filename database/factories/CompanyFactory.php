<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->companyEmail(),
            'address' => $this->faker->address(),
            'website' => $this->faker->url(),
            'logo' => $this->faker->imageUrl(200, 200, 'business', true, 'logo'),
            'created_at' => $created = $this->faker->dateTimeBetween('-5 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween($created, 'now'),
        ];
    }
}
