<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Optional: list of Cambodia provinces
        $provinces = [
            'phnom-penh','banteay-meanchey','battambang','kampong-cham','kampong-chhnang',
            'kampong-speu','kampong-thom','kampot','kandal','kep','koh-kong','kratie',
            'mondulkiri','oddar-meanchey','pailin','preah-sihanouk','preah-vihear',
            'pursat','prey-veng','ratanakiri','siem-reap','stung-treng','svay-rieng',
            'takeo','tboung-khmum'
        ];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'phone' => fake()->phoneNumber(),
            'country' => 'Cambodia',                 // ✅ fixed country
            'city' => $provinces[array_rand($provinces)], // ✅ random province
            'type' => 'user',
            'status' => 'active',
            'is_online' => false,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
