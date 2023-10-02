<?php

namespace Database\Factories\Auth;

use App\Models\Assets\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /** @var \App\Models\Assets\Language $language */
        $language = Language::inRandomOrder()->first();

        return [
            'status'         => fake()->boolean,
            'contact_name'   => fake()->name,
            'email'          => fake()->email,
            'password'       => 'password',
            'about'          => fake()->words,
            'language_id'    => $language->id,
        ];
    }
}
