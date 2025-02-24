<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Test;
use App\Enums\TestsStatusEnum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    protected $model = Test::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->userName . time() . '@example.com',           
            'phone' => '+7' . $this->faker->unique()->numerify('###-###-##-##'),
            'description' => $this->faker->text, 
            'comment' => $this->faker->text,
            'status' => $this->faker->randomElement(TestsStatusEnum::cases()),
        ];
    }
}
