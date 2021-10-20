<?php

namespace Database\Factories;

use App\Models\Nominate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NominateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Nominate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomElement(User::whereType('company')->pluck('id')),
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'is_promoted' => $this->faker->randomElement([true, false]),
            'reasons' => $this->faker->paragraph,
        ];
    }
}
