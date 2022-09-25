<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(20),
            'description' => $this->faker->realText(50),
            'category_id' => Category::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'collection_token_id' => $this->faker->unique()->numberBetween(1, 1000000),
            'facebook_url' => $this->faker->url,
            'twitter_url' => $this->faker->url,
            'is_sensitive_content' => $this->faker->boolean,
        ];
    }
}
