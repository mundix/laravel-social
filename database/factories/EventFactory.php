<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
	        'participants' => $this->faker->numberBetween(5,30),
	        'global_amount' => $this->faker->randomFloat(2,1000,20000),
	        'total_amount' => $this->faker->randomFloat(2, 100, 4000),
	        'due_date' => $this->faker->date(),
	        'status' => 'enabled'
        ];
    }
}
