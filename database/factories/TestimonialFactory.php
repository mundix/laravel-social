<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Testimonial::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author' => $this->faker->name,
            'name' => $this->faker->name,
            'job_title' => $this->faker->jobTitle,
            'location' => $this->faker->city,
            'content' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'draft', 'approved', 'rejected'])
        ];;
    }
}
