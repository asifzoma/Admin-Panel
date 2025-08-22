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
        $created = fake()->dateTimeBetween('-5 years', 'now');
        
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'address' => fake()->address(),
            'website' => fake()->url(),
            'logo' => fake()->imageUrl(200, 200, 'business', true, 'logo'),
            'created_at' => $created,
            'updated_at' => fake()->dateTimeBetween($created, 'now'),
        ];
    }
}
