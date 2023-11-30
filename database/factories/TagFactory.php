<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Facades\HyperceMail;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /** @var string */
    protected $model = Tag::class;

    public function definition(): array
    {
        return [
            'workspace_id' => HyperceMail::currentWorkspaceId(),
            'name' => ucwords($this->faker->unique()->word),
        ];
    }
}
