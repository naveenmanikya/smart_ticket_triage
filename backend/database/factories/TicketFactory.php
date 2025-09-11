<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];

        return [
            'id' => (string) Str::ulid(),
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'category' => $this->faker->randomElement(['Billing', 'Technical', 'General Inquiry', 'Feature Request']),
            'explanation' => $this->faker->optional()->paragraph(),
            'confidence' => $this->faker->optional()->randomFloat(2, 0.5, 1.0),
            'note' => $this->faker->optional()->paragraph(),
        ];
    }
}