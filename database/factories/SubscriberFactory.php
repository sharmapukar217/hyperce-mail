<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Facades\HyperceMail;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriberFactory extends Factory
{
    /** @var string */
    protected $model = Subscriber::class;

    public function definition(): array
    {
        return [
            'workspace_id' => HyperceMail::currentWorkspaceId(),
            'hash' => $this->faker->uuid,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
        ];
    }
}
