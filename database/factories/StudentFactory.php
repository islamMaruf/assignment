<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'department_id' => $this->faker->randomElement([1, 2, 3]),
            'batch_name' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'G']),
        ];
    }
}
