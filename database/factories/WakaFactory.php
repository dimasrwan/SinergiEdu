<?php

namespace Database\Factories;

use App\Models\Waka;
use Illuminate\Database\Eloquent\Factories\Factory;

class WakaFactory extends Factory
{
    protected $model = Waka::class;

    public function definition(): array
    {
        return [
            'school_id' => 1,
            'user_id' => 1,
            'nip' => $this->faker->unique()->numerify('19##########'),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
        ];
    }
}
