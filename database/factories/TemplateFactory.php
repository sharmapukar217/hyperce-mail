<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Facades\HyperceMail;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    /** @var string */
    protected $model = Template::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'workspace_id' => HyperceMail::currentWorkspaceId(),
            'content' => '{{content}}',
        ];
    }
}
