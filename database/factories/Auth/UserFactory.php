<?php

namespace Database\Factories\Auth;

use App\Models\Assets\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $language = Language::inRandomOrder()->first();

        return [
            'username'     => fake()->userName,
            'is_root'      => fake()->boolean,
            'status'       => fake()->boolean,
            'contact_name' => fake()->name,
            'email'        => fake()->email,
            'password'     => 'password',
            'about'        => fake()->words,
            'language_id'  => $language->id,
        ];
    }
}
